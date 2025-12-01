<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\MicrosoftGraphService;
use Cake\Core\Configure;
use Cake\Http\Client;

/**
 * Admin Users Controller
 * 
 * Manages user accounts with role-based access control.
 * Only accessible by admin role users.
 */
class UsersController extends AppAdminController
{
    /**
     * List all users with DataTables AJAX support
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadModel('Users');

        // DataTables AJAX
        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            $draw = (int)$this->request->getQuery('draw', 1);
            $start = (int)$this->request->getQuery('start', 0);
            $length = (int)$this->request->getQuery('length', 10);
            $searchValue = $this->request->getQuery('search')['value'] ?? '';
            $orderColumn = (int)($this->request->getQuery('order')[0]['column'] ?? 0);
            $orderDir = $this->request->getQuery('order')[0]['dir'] ?? 'desc';

            // Map DataTables column index to database fields. Keep indexes aligned with the view columns
            // 0: ID, 1: Email, 2: Name (use first_name), 3: Department, 4: Role, 5: Status (is_active), 6: Last Login, 7: Registered, 8: Actions
            $columns = [
                0 => 'Users.id',
                1 => 'Users.email',
                2 => 'Users.first_name',
                3 => 'Users.department',
                4 => 'Users.role',
                5 => 'Users.is_active',
                6 => 'Users.last_login',
                7 => 'Users.created',
                8 => 'Users.created', // Actions column not orderable; fallback safe field
            ];
            $orderField = $columns[$orderColumn] ?? 'Users.created';

            $query = $this->Users->find();

            if (!empty($searchValue)) {
                $query->where([
                    'OR' => [
                        'Users.email LIKE' => "%$searchValue%",
                        'Users.first_name LIKE' => "%$searchValue%",
                        'Users.last_name LIKE' => "%$searchValue%",
                        'Users.role LIKE' => "%$searchValue%",
                        'Users.department LIKE' => "%$searchValue%",
                    ]
                ]);
            }

            $recordsTotal = $this->Users->find()->count();
            $recordsFiltered = $query->count();

            $rows = $query->order([$orderField => $orderDir])
                ->limit($length)
                ->offset($start)
                ->all();

            $data = [];
            foreach ($rows as $u) {
                $data[] = [
                    'id' => $u->id,
                    'email' => $u->email,
                    'name' => trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')),
                    'role' => $u->role,
                    'department' => $u->department,
                    'is_active' => (bool)$u->is_active,
                    'last_login' => $u->last_login ? $u->last_login->format('Y-m-d H:i:s') : null,
                    'created' => $u->created ? $u->created->format('Y-m-d H:i:s') : null,
                ];
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ];

            return $this->response->withType('application/json')->withStringBody(json_encode($response));
        }

        // Fallback non-AJAX
        $query = $this->Users->find()->order(['Users.created' => 'DESC']);
        $users = $this->paginate($query, ['limit' => 25]);
        
        // Get role counts for summary
        $roleCounts = $this->Users->find()
            ->select(['role', 'count' => $query->func()->count('*')])
            ->group('role')
            ->all()
            ->combine('role', 'count')
            ->toArray();
        
        $activeCount = $this->Users->find()->where(['is_active' => true])->count();
        $inactiveCount = $this->Users->find()->where(['is_active' => false])->count();
        
        $this->set(compact('users', 'roleCounts', 'activeCount', 'inactiveCount'));
    }

    /**
     * View user details
     *
     * @param int $id User ID
     * @return \Cake\Http\Response|null
     */
    public function view($id)
    {
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        
        // Get user's recent activity (if you have audit logs)
        // $recentActivity = $this->AuditLogs->find()
        //     ->where(['user_id' => $id])
        //     ->order(['created' => 'DESC'])
        //     ->limit(10)
        //     ->all();
        
        $this->set(compact('user'));
    }

    /**
     * Add new user
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $this->loadModel('Users');
        $user = $this->Users->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data, [
                'fields' => ['email', 'first_name', 'last_name', 'department', 'phone', 'role', 'is_active']
            ]);
            
            if ($this->Users->save($user)) {
                $this->Flash->success('User has been created successfully.');
                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error('Unable to create user. Please check the form.');
        }
        
        $roles = $this->_getRolesList();
        $this->set(compact('user', 'roles'));
    }

    /**
     * AJAX: Search Microsoft Graph users for the picker on the add/edit form
     * Route: /admin/users/search-graph?q=...
     */
    public function searchGraph()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;

        $q = trim((string)$this->request->getQuery('q', ''));
        if (strlen($q) < 2) {
            return $this->response->withType('application/json')->withStringBody(json_encode(['results' => []]));
        }

        // Prefer user delegated token if present; fall back to app-only
        $accessToken = (string)($this->request->getSession()->read('Auth.AccessToken') ?? '');
        if (!$accessToken) {
            $azure = (array)Configure::read('Azure');
            $tenant = (string)($azure['tenantId'] ?? '');
            $clientId = (string)($azure['clientId'] ?? '');
            $clientSecret = (string)($azure['clientSecret'] ?? '');
            if ($tenant && $clientId && $clientSecret) {
                try {
                    $http = new Client(['timeout' => 20]);
                    $resp = $http->post('https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/token', [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type' => 'client_credentials',
                        'scope' => 'https://graph.microsoft.com/.default',
                    ]);
                    if ($resp->isOk()) {
                        $data = $resp->getJson();
                        $accessToken = (string)($data['access_token'] ?? '');
                    }
                } catch (\Throwable $e) {
                    // ignore; will return empty results below
                }
            }
        }

        if (!$accessToken) {
            return $this->response->withType('application/json')->withStringBody(json_encode(['results' => []]));
        }

        $graph = new MicrosoftGraphService($accessToken);
        $users = $graph->searchUsers($q, 10);

        return $this->response->withType('application/json')->withStringBody(json_encode([
            'results' => $users,
        ]));
    }

    /**
     * Edit user details
     *
     * @param int $id User ID
     * @return \Cake\Http\Response|null
     */
    public function edit($id)
    {
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        
        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data, [
                'fields' => ['first_name', 'last_name', 'department', 'phone', 'role', 'is_active']
            ]);
            
            if ($this->Users->save($user)) {
                $this->Flash->success('User has been updated successfully.');
                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error('Unable to update user. Please check the form.');
        }
        
        $roles = $this->_getRolesList();
        $this->set(compact('user', 'roles'));
    }

    /**
     * Delete user
     *
     * @param int $id User ID
     * @return \Cake\Http\Response|null
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        
        // Prevent deleting yourself
        $currentUser = $this->getRequest()->getAttribute('identity');
        if ($currentUser && $currentUser['id'] == $id) {
            $this->Flash->error('You cannot delete your own account.');
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->Users->delete($user)) {
            $this->Flash->success('User has been deleted.');
        } else {
            $this->Flash->error('Unable to delete user.');
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Deactivate user account
     *
     * @param int $id User ID
     * @return \Cake\Http\Response|null
     */
    public function deactivate($id)
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        
        // Prevent deactivating yourself
        $currentUser = $this->getRequest()->getAttribute('identity');
        if ($currentUser && $currentUser['id'] == $id) {
            $this->Flash->error('You cannot deactivate your own account.');
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $user->is_active = false;
        if ($this->Users->save($user)) {
            $this->Flash->success('User has been deactivated.');
        } else {
            $this->Flash->error('Unable to deactivate user.');
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Activate user account
     *
     * @param int $id User ID
     * @return \Cake\Http\Response|null
     */
    public function activate($id)
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        
        $user->is_active = true;
        if ($this->Users->save($user)) {
            $this->Flash->success('User has been activated.');
        } else {
            $this->Flash->error('Unable to activate user.');
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Get list of available roles with descriptions
     *
     * @return array
     */
    protected function _getRolesList(): array
    {
        return [
            'admin' => 'Admin - Full system access',
            'user' => 'User - Can manage invoices',
            'auditor' => 'Auditor - Read-only + audit logs',
            'risk_assessment' => 'Risk Assessment - Read-only + limited audit access',
            'treasurer' => 'Treasurer - Can approve/reject invoices',
            'export' => 'Export - Can view fresh invoices sent to Export',
            'sales' => 'Sales - Can view final invoices sent to Sales',
        ];
    }
}
