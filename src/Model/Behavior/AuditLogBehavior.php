<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\Http\ServerRequest;
use Cake\Core\Configure;
use ArrayObject;

/**
 * AuditLog behavior
 * 
 * Automatically logs all Create, Update, and Delete operations
 * with user context, IP address, and data changes
 */
class AuditLogBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => true,
        'fields' => [], // Specific fields to track, empty means all
        'ignore' => ['modified', 'updated'], // Fields to ignore
    ];

    /**
     * After save callback
     *
     * @param \Cake\Event\EventInterface $event The event
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$this->getConfig('enabled')) {
            return;
        }

        $isNew = $entity->isNew();
        $action = $isNew ? 'create' : 'update';
        
        $this->logAction($action, $entity);
    }

    /**
     * After delete callback
     *
     * @param \Cake\Event\EventInterface $event The event
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$this->getConfig('enabled')) {
            return;
        }

        $this->logAction('delete', $entity);
    }

    /**
     * Log an action to the audit_logs table
     *
     * @param string $action The action performed (create, update, delete)
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @return void
     */
    protected function logAction(string $action, EntityInterface $entity)
    {
        try {
            $table = $this->table();
            $auditLogsTable = $table->getTableLocator()->get('AuditLogs');
            
            // Get user context
            $userId = $this->getCurrentUserId();
            $request = $this->getCurrentRequest();
            
            // Get changed data
            $oldValues = null;
            $newValues = null;
            
            if ($action === 'update') {
                $dirty = $entity->extractOriginalChanged($entity->getDirty());
                if (!empty($dirty)) {
                    $oldValues = json_encode($this->filterFields($dirty));
                    $newValues = json_encode($this->filterFields($entity->extract($entity->getDirty())));
                }
            } elseif ($action === 'create') {
                $newValues = json_encode($this->filterFields($entity->toArray()));
            } elseif ($action === 'delete') {
                $oldValues = json_encode($this->filterFields($entity->toArray()));
            }
            
            // Create audit log entry
            $auditLog = $auditLogsTable->newEntity([
                'user_id' => $userId,
                'action' => $action,
                'model' => $table->getRegistryAlias(),
                'record_id' => $entity->get($table->getPrimaryKey()),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $request ? $request->clientIp() : null,
                'user_agent' => $request ? $request->getHeaderLine('User-Agent') : null,
            ]);
            
            $auditLogsTable->save($auditLog);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Cake\Log\Log::error('AuditLog Behavior Error: ' . $e->getMessage());
        }
    }

    /**
     * Filter fields based on configuration
     *
     * @param array $data The data to filter
     * @return array Filtered data
     */
    protected function filterFields(array $data): array
    {
        $ignore = $this->getConfig('ignore');
        $fields = $this->getConfig('fields');
        
        // Remove ignored fields
        foreach ($ignore as $field) {
            unset($data[$field]);
        }
        
        // If specific fields are configured, only include those
        if (!empty($fields)) {
            $data = array_intersect_key($data, array_flip($fields));
        }
        
        // Remove sensitive data
        $sensitive = ['password', 'token', 'secret', 'api_key'];
        foreach ($sensitive as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***REDACTED***';
            }
        }
        
        return $data;
    }

    /**
     * Get current user ID from session
     *
     * @return int|null
     */
    protected function getCurrentUserId(): ?int
    {
        try {
            $request = $this->getCurrentRequest();
            if ($request) {
                $session = $request->getSession();
                $user = $session->read('Auth.User');
                return $user['id'] ?? null;
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }

    /**
     * Get current request
     *
     * @return \Cake\Http\ServerRequest|null
     */
    protected function getCurrentRequest(): ?ServerRequest
    {
        try {
            if (PHP_SAPI === 'cli') {
                return null;
            }
            
            $request = \Cake\Routing\Router::getRequest();
            return $request;
        } catch (\Exception $e) {
            return null;
        }
    }
}
