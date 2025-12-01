<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\I18n\FrozenTime;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * DatabaseBackup Controller
 *
 * Handles database backup and restore operations
 */
class DatabaseBackupController extends AppAdminController
{
    /**
     * Index method - List all backups
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $backupDir = ROOT . DS . 'backups';
        $folder = new Folder($backupDir, true, 0755);
        
        $files = $folder->find('.*\.sql', true);
        $backups = [];
        
        foreach ($files as $file) {
            $filePath = $backupDir . DS . $file;
            $fileObj = new File($filePath);
            
            $backups[] = [
                'filename' => $file,
                'size' => $fileObj->size(),
                'modified' => new FrozenTime($fileObj->lastChange()),
                'path' => $filePath
            ];
        }
        
        // Sort by modified date descending
        usort($backups, function($a, $b) {
            return $b['modified']->toUnixString() <=> $a['modified']->toUnixString();
        });
        
        $this->set(compact('backups'));
    }

    /**
     * Create backup
     *
     * @return \Cake\Http\Response|null Redirects on success
     */
    public function create()
    {
        $this->request->allowMethod(['post']);
        
        try {
            $config = \Cake\Datasource\ConnectionManager::get('default')->config();
            
            $backupDir = ROOT . DS . 'backups';
            $folder = new Folder($backupDir, true, 0755);
            
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $filepath = $backupDir . DS . $filename;
            
            // Build mysqldump command
            $host = $config['host'] ?? 'localhost';
            $username = $config['username'] ?? 'root';
            $password = $config['password'] ?? '';
            $database = $config['database'];
            $port = isset($config['port']) ? (int)$config['port'] : null;

            // Resolve mysqldump path (Windows/XAMPP friendly)
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $mysqldump = 'mysqldump';
            if ($isWindows) {
                $candidate = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
                if (file_exists($candidate)) {
                    $mysqldump = '"' . $candidate . '"';
                }
            }
            
            // Construct command without relying on shell expansion for arguments
            $cmdParts = [
                $mysqldump,
                '--host=' . escapeshellarg($host),
                '--user=' . escapeshellarg($username),
                '--password=' . escapeshellarg($password),
                '--single-transaction',
                '--routines',
                '--triggers',
            ];
            if ($port) {
                $cmdParts[] = '--port=' . (int)$port;
            }
            $cmdParts[] = escapeshellarg($database);
            
            $redirection = '> ' . escapeshellarg($filepath);
            $command = implode(' ', $cmdParts) . ' ' . $redirection;

            // On Windows, ensure redirection works by invoking via cmd /C
            if ($isWindows) {
                $command = 'cmd /C ' . $command;
            }
            
            // Execute backup
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            // If command failed or file is empty, clean up and report
            $fileOk = file_exists($filepath) && filesize($filepath) > 0;
            if ($returnCode === 0 && $fileOk) {
                // Log the backup
                $this->loadModel('AuditLogs');
                $session = $this->request->getSession();
                $user = $session->read('Auth.User');
                
                $this->AuditLogs->save($this->AuditLogs->newEntity([
                    'user_id' => $user['id'] ?? null,
                    'action' => 'backup',
                    'model' => 'Database',
                    'description' => "Database backup created: {$filename}",
                    'ip_address' => $this->request->clientIp(),
                    'user_agent' => $this->request->getHeaderLine('User-Agent')
                ]));
                
                $this->Flash->success("Database backup created successfully: {$filename}");
            } else {
                // Remove empty/failed file if present to avoid cluttering list
                if (file_exists($filepath) && filesize($filepath) === 0) {
                    @unlink($filepath);
                }
                $errMsg = 'Backup command failed';
                if ($isWindows && strpos(strtolower($command), 'mysqldump') !== false && !file_exists('C:\\xampp\\mysql\\bin\\mysqldump.exe')) {
                    $errMsg .= ' (mysqldump not found; ensure MySQL bin is in PATH or install XAMPP and set C:\\xampp\\mysql\\bin)';
                }
                throw new \Exception($errMsg);
            }
        } catch (\Exception $e) {
            $this->Flash->error('Failed to create backup: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Download backup file
     *
     * @param string|null $filename The backup filename
     * @return \Cake\Http\Response
     */
    public function download($filename = null)
    {
        $this->request->allowMethod(['get']);
        
        if (!$filename) {
            $this->Flash->error('Invalid backup file');
            return $this->redirect(['action' => 'index']);
        }
        
        $backupDir = ROOT . DS . 'backups';
        $filepath = $backupDir . DS . $filename;
        
        if (!file_exists($filepath)) {
            $this->Flash->error('Backup file not found');
            return $this->redirect(['action' => 'index']);
        }
        
        $this->response = $this->response->withFile(
            $filepath,
            ['download' => true, 'name' => $filename]
        );
        
        return $this->response;
    }

    /**
     * Restore from backup
     *
     * @param string|null $filename The backup filename
     * @return \Cake\Http\Response|null Redirects on success
     */
    public function restore($filename = null)
    {
        $this->request->allowMethod(['post']);
        
        if (!$filename) {
            $this->Flash->error('Invalid backup file');
            return $this->redirect(['action' => 'index']);
        }
        
        try {
            $backupDir = ROOT . DS . 'backups';
            $filepath = $backupDir . DS . $filename;
            
            if (!file_exists($filepath)) {
                throw new \Exception('Backup file not found');
            }
            
            $config = \Cake\Datasource\ConnectionManager::get('default')->config();
            
            $host = $config['host'] ?? 'localhost';
            $username = $config['username'] ?? 'root';
            $password = $config['password'] ?? '';
            $database = $config['database'];
            $port = isset($config['port']) ? (int)$config['port'] : null;
            
            // Resolve mysql path (Windows/XAMPP friendly)
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $mysqlBin = 'mysql';
            if ($isWindows) {
                $candidate = 'C:\\xampp\\mysql\\bin\\mysql.exe';
                if (file_exists($candidate)) {
                    $mysqlBin = '"' . $candidate . '"';
                }
            }
            
            // Build mysql restore command
            $cmdParts = [
                $mysqlBin,
                '--host=' . escapeshellarg($host),
                '--user=' . escapeshellarg($username),
                '--password=' . escapeshellarg($password),
            ];
            if ($port) {
                $cmdParts[] = '--port=' . (int)$port;
            }
            $cmdParts[] = escapeshellarg($database);
            $redirection = '< ' . escapeshellarg($filepath);
            $command = implode(' ', $cmdParts) . ' ' . $redirection;
            if ($isWindows) {
                $command = 'cmd /C ' . $command;
            }
            
            // Execute restore
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                // Log the restore
                $this->loadModel('AuditLogs');
                $session = $this->request->getSession();
                $user = $session->read('Auth.User');
                
                $this->AuditLogs->save($this->AuditLogs->newEntity([
                    'user_id' => $user['id'] ?? null,
                    'action' => 'restore',
                    'model' => 'Database',
                    'description' => "Database restored from: {$filename}",
                    'ip_address' => $this->request->clientIp(),
                    'user_agent' => $this->request->getHeaderLine('User-Agent')
                ]));
                
                $this->Flash->success("Database restored successfully from: {$filename}");
            } else {
                $errMsg = 'Restore command failed';
                if ($isWindows && strpos(strtolower($command), 'mysql') !== false && !file_exists('C:\\xampp\\mysql\\bin\\mysql.exe')) {
                    $errMsg .= ' (mysql not found; ensure MySQL bin is in PATH or install XAMPP and set C:\\xampp\\mysql\\bin)';
                }
                throw new \Exception($errMsg);
            }
        } catch (\Exception $e) {
            $this->Flash->error('Failed to restore backup: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete backup file
     *
     * @param string|null $filename The backup filename
     * @return \Cake\Http\Response|null Redirects on success
     */
    public function delete($filename = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        if (!$filename) {
            $this->Flash->error('Invalid backup file');
            return $this->redirect(['action' => 'index']);
        }
        
        try {
            $backupDir = ROOT . DS . 'backups';
            $filepath = $backupDir . DS . $filename;
            
            if (!file_exists($filepath)) {
                throw new \Exception('Backup file not found');
            }
            
            $file = new File($filepath);
            if ($file->delete()) {
                // Log the deletion
                $this->loadModel('AuditLogs');
                $session = $this->request->getSession();
                $user = $session->read('Auth.User');
                
                $this->AuditLogs->save($this->AuditLogs->newEntity([
                    'user_id' => $user['id'] ?? null,
                    'action' => 'delete',
                    'model' => 'DatabaseBackup',
                    'description' => "Backup deleted: {$filename}",
                    'ip_address' => $this->request->clientIp(),
                    'user_agent' => $this->request->getHeaderLine('User-Agent')
                ]));
                
                $this->Flash->success("Backup deleted successfully: {$filename}");
            } else {
                throw new \Exception('Failed to delete backup file');
            }
        } catch (\Exception $e) {
            $this->Flash->error('Failed to delete backup: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'index']);
    }
}
