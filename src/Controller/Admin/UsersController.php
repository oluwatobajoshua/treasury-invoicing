<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class UsersController extends AppAdminController
{
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
            $orderColumn = $this->request->getQuery('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getQuery('order')[0]['dir'] ?? 'desc';

            $columns = [
                0 => 'Users.id',
                1 => 'Users.email',
                2 => 'Users.first_name',
                3 => 'Users.role',
                4 => 'Users.is_active',
                5 => 'Users.last_login',
                6 => 'Users.created',
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
        $users = $this->paginate($query, ['limit' => 20]);
        $this->set(compact('users'));
    }

    public function view($id)
    {
        $this->loadModel('Users');
        $user = $this->Users->get($id, [
            'contain' => ['JobLevels', 'TravelRequests'],
        ]);
        $this->set(compact('user'));
    }

    public function edit($id)
    {
        $this->loadModel('Users');
        $this->loadModel('JobLevels');
        $user = $this->Users->get($id);
        if ($this->request->is(['post','put','patch'])) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data, ['fields' => ['first_name','last_name','department','phone','job_level_id','is_active','role']]);
            if ($this->Users->save($user)) {
                $this->Flash->success('User updated.');
                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error('Unable to update user.');
        }
        $jobLevels = $this->JobLevels->find('list', ['keyField' => 'id', 'valueField' => 'level_name'])->order(['level_code' => 'ASC'])->toArray();
        $roles = ['user' => 'User', 'manager' => 'Manager', 'admin' => 'Admin'];
        $this->set(compact('user','jobLevels','roles'));
    }

    public function deactivate($id)
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        $user->is_active = false;
        if ($this->Users->save($user)) {
            $this->Flash->success('User deactivated.');
        } else {
            $this->Flash->error('Unable to deactivate user.');
        }
        return $this->redirect(['action' => 'view', $id]);
    }

    public function activate($id)
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        $user->is_active = true;
        if ($this->Users->save($user)) {
            $this->Flash->success('User activated.');
        } else {
            $this->Flash->error('Unable to activate user.');
        }
        return $this->redirect(['action' => 'view', $id]);
    }
}
