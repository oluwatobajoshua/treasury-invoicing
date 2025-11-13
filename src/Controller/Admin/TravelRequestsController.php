<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class TravelRequestsController extends AppAdminController
{
    public function index()
    {
        $this->loadModel('TravelRequests');
        // Basic diagnostics to help trace empty table issues (can be toggled off later)
        $isAjax = $this->request->is('ajax');
        if ($isAjax) {
            $debugInfo = [
                'queryParams' => $this->request->getQueryParams(),
                'sessionUser' => (array)$this->request->getSession()->read('Auth.User'),
            ];
            $this->log('[Admin\TravelRequests] Incoming AJAX index request: ' . json_encode($debugInfo), 'debug');
        }

        // DataTables AJAX response (match non-admin index for UI parity)
        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            // DataTables params
            $draw = (int)$this->request->getQuery('draw', 1);
            $start = (int)$this->request->getQuery('start', 0);
            $length = (int)$this->request->getQuery('length', 10);
            $searchValue = $this->request->getQuery('search')['value'] ?? '';
            $orderColumn = $this->request->getQuery('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getQuery('order')[0]['dir'] ?? 'desc';

            // Column mapping (keep aligned with template columns)
            // Map DataTables column indexes to sortable fields (avoid non-existent Users.name)
            $columns = [
                0 => 'TravelRequests.request_number',
                1 => 'Users.first_name', // order by first_name for traveller
                2 => 'TravelRequests.destination',
                3 => 'TravelRequests.travel_type',
                4 => 'TravelRequests.departure_date',
                5 => 'TravelRequests.return_date',
                6 => 'TravelRequests.duration_days',
                7 => 'TravelRequests.status',
                8 => 'TravelRequests.current_step',
                9 => 'TravelRequests.created', // actions column fallback
            ];
            $orderField = $columns[$orderColumn] ?? 'TravelRequests.created';

            $query = $this->TravelRequests->find()->contain(['Users']);

            // Search filter
            if (!empty($searchValue)) {
                $like = '%' . $searchValue . '%';
                $query->where([
                    'OR' => [
                        'TravelRequests.request_number LIKE' => $like,
                        'TravelRequests.destination LIKE' => $like,
                        'TravelRequests.purpose_of_travel LIKE' => $like,
                        'TravelRequests.status LIKE' => $like,
                        'Users.first_name LIKE' => $like,
                        'Users.last_name LIKE' => $like,
                        'Users.email LIKE' => $like,
                    ]
                ]);
            }

            $recordsTotal = $this->TravelRequests->find()->count();
            $recordsFiltered = $query->count();
            $this->log('[Admin\TravelRequests] counts total=' . $recordsTotal . ' filtered=' . $recordsFiltered, 'debug');

            $requests = $query
                ->order([$orderField => $orderDir])
                ->limit($length)
                ->offset($start)
                ->all();

            $data = [];
            foreach ($requests as $req) {
                $user = $req->user ?? null;
                $userData = [
                    'id' => $user->id ?? null,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->email ?? 'N/A'),
                    'email' => $user->email ?? '',
                ];

                $data[] = [
                    'request_number' => $req->request_number,
                    'user' => $userData,
                    'destination' => $req->destination,
                    'travel_type' => $req->travel_type,
                    'departure_date' => $req->departure_date ? $req->departure_date->format('Y-m-d') : null,
                    'return_date' => $req->return_date ? $req->return_date->format('Y-m-d') : null,
                    'duration_days' => $req->duration_days ?? 0,
                    'status' => $req->status,
                    'accommodation_required' => $req->accommodation_required,
                    'total_allowance' => $req->total_allowance ?? 0.00,
                    'current_step' => $req->current_step ?? 1,
                    'purpose' => substr($req->purpose_of_travel ?? '', 0, 100),
                    'created' => $req->created ? $req->created->format('Y-m-d H:i:s') : null,
                    'id' => $req->id,
                ];
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ];

            $json = json_encode($response);
            $this->log('[Admin\TravelRequests] responding with ' . strlen($json) . ' bytes', 'debug');
            return $this->response
                ->withType('application/json')
                ->withStringBody($json);
        }

        // Non-AJAX fallback (not used by DataTables UI but kept for completeness)
        $query = $this->TravelRequests->find()
            ->contain(['Users'])
            ->order(['TravelRequests.created' => 'DESC']);
        $requests = $this->paginate($query, ['limit' => 20]);
        $this->set(compact('requests'));
    }

    public function view($id)
    {
        $this->loadModel('TravelRequests');
        $this->loadModel('WorkflowHistory');
        $request = $this->TravelRequests->get($id, [
            'contain' => ['Users', 'LineManagers', 'Admins']
        ]);
        $history = $this->WorkflowHistory->find()
            ->where(['travel_request_id' => $id])
            ->contain(['Users'])
            ->order(['WorkflowHistory.created' => 'DESC'])
            ->all();
        $this->set(compact('request', 'history'));
    }

    public function approve($id)
    {
        $this->request->allowMethod(['post']);
        // Business rule: only assigned line manager can approve/reject (done in non-admin controller)
        $this->Flash->error('Only the assigned line manager can approve a request. Use the non-admin approval flow.');
        return $this->redirect(['action' => 'view', $id]);
    }

    public function reject($id)
    {
        $this->request->allowMethod(['post']);
        // Business rule: only assigned line manager can approve/reject (done in non-admin controller)
        $this->Flash->error('Only the assigned line manager can reject a request. Use the non-admin rejection flow.');
        return $this->redirect(['action' => 'view', $id]);
    }
}
