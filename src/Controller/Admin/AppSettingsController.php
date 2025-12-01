<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Utility\Hash;

/**
 * AppSettings Controller
 *
 * Manage application-wide settings and configurations
 */
class AppSettingsController extends AppAdminController
{
    /**
     * Index method - Display and edit app settings
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Application Settings');
        
        // Define config file path
        $configFile = CONFIG . 'app_settings.php';
        
        // Load current settings or defaults
        if (file_exists($configFile)) {
            $settings = include $configFile;
        } else {
            $settings = $this->getDefaultSettings();
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Sanitize and validate input
            $settings = [
                'app_name' => $data['app_name'] ?? 'Treasury Invoicing System',
                'company_name' => $data['company_name'] ?? '',
                'company_address' => $data['company_address'] ?? '',
                'company_phone' => $data['company_phone'] ?? '',
                'company_email' => $data['company_email'] ?? '',
                'company_website' => $data['company_website'] ?? '',
                'tax_id' => $data['tax_id'] ?? '',
                'currency' => $data['currency'] ?? 'USD',
                'currency_symbol' => $data['currency_symbol'] ?? '$',
                'date_format' => $data['date_format'] ?? 'Y-m-d',
                'timezone' => $data['timezone'] ?? 'UTC',
                'items_per_page' => (int)($data['items_per_page'] ?? 25),
                'enable_email_notifications' => isset($data['enable_email_notifications']),
                'enable_audit_log' => isset($data['enable_audit_log']),
                'session_timeout' => (int)($data['session_timeout'] ?? 30),
                // New: Teams
                'teams_enabled' => isset($data['teams_enabled']),
                'teams_team_id' => $data['teams_team_id'] ?? '',
                'teams_channel_id' => $data['teams_channel_id'] ?? '',
                // New: Power Automate
                'power_automate_enabled' => isset($data['power_automate_enabled']),
                'power_automate_flow_url' => $data['power_automate_flow_url'] ?? '',
                'power_automate_secret' => $data['power_automate_secret'] ?? '',
                // New: PDF logo explicit filename (optional override)
                'pdf_logo' => $data['pdf_logo'] ?? '',
            ];
            
            // Save settings to config file
            $content = "<?php\nreturn " . var_export($settings, true) . ";\n";
            
            if (file_put_contents($configFile, $content)) {
                // Log the settings change
                $authUser = $this->request->getAttribute('authUser') ?? $this->request->getSession()->read('Auth.User');
                $userId = null;
                if ($authUser && isset($authUser['id'])) {
                    $userId = $authUser['id'];
                }
                
                if ($userId) {
                    $this->loadModel('AuditLogs');
                    $this->AuditLogs->save($this->AuditLogs->newEntity([
                        'user_id' => $userId,
                        'action' => 'update',
                        'model' => 'AppSettings',
                        'record_id' => 0,
                        'old_values' => null,
                        'new_values' => json_encode($settings),
                        'ip_address' => $this->request->clientIp(),
                        'user_agent' => $this->request->getEnv('HTTP_USER_AGENT'),
                    ]));
                }
                
                $this->Flash->success(__('Application settings have been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to save settings. Please check file permissions.'));
            }
        }
        
        $this->set(compact('settings'));
        $this->set('timezones', timezone_identifiers_list());
    }
    
    /**
     * Get default settings
     *
     * @return array
     */
    protected function getDefaultSettings(): array
    {
        return [
            'app_name' => 'Treasury Invoicing System',
            'company_name' => 'Your Company Name',
            'company_address' => '',
            'company_phone' => '',
            'company_email' => '',
            'company_website' => '',
            'tax_id' => '',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'date_format' => 'Y-m-d',
            'timezone' => 'UTC',
            'items_per_page' => 25,
            'enable_email_notifications' => true,
            'enable_audit_log' => true,
            'session_timeout' => 30,
            // Teams defaults
            'teams_enabled' => false,
            'teams_team_id' => '',
            'teams_channel_id' => '',
            // Power Automate defaults
            'power_automate_enabled' => false,
            'power_automate_flow_url' => '',
            'power_automate_secret' => '',
            // PDF logo override
            'pdf_logo' => '',
        ];
    }
}
