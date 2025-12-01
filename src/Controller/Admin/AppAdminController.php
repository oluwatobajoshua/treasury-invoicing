<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class AppAdminController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->loadComponent('Flash');
        $this->loadComponent('Paginator');
        $this->viewBuilder()->setLayout('admin');

        $session = $this->request->getSession();
        $authUser = (array)$session->read('Auth.User');
        if (!($authUser['role'] ?? null) || strtolower((string)$authUser['role']) !== 'admin') {
            $this->Flash->error('You are not authorized to access the admin area.');
            return $this->redirect(['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 'home']);
        }
    }

    /**
     * Admin area authorization - only admin role allowed.
     * Overrides parent isAuthorized() with admin-only enforcement.
     *
     * @param array|null $user User data from session
     * @return bool True if user is admin, false otherwise
     */
    public function isAuthorized($user): bool
    {
        // Admin routes require admin role
        if (!$user || !isset($user['role'])) {
            return false;
        }

        return strtolower((string)$user['role']) === 'admin';
    }
}
