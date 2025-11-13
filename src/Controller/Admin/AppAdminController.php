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
}
