<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client;

/**
 * TravelRequests Controller
 *
 * @property \App\Model\Table\TravelRequestsTable $TravelRequests
 * @method \App\Model\Entity\TravelRequest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TravelRequestsController extends AppController
{
    /**
     * Debug Check - System health and configuration check
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function debugCheck()
    {
        // This action renders the debug check page
    }
    
    /**
     * Test Notifications - Guide for fixing notification issues
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function testNotifications()
    {
        // This action renders the notification testing guide
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Handle DataTables AJAX request
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            
            // DataTables parameters
            $draw = $this->request->getQuery('draw', 1);
            $start = (int)$this->request->getQuery('start', 0);
            $length = (int)$this->request->getQuery('length', 10);
            $searchValue = $this->request->getQuery('search')['value'] ?? '';
            $orderColumn = $this->request->getQuery('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getQuery('order')[0]['dir'] ?? 'desc';
            
            // Column mapping
            $columns = [
                0 => 'TravelRequests.request_number',
                1 => 'Users.name',
                2 => 'TravelRequests.destination',
                3 => 'TravelRequests.travel_type',
                4 => 'TravelRequests.departure_date',
                5 => 'TravelRequests.return_date',
                6 => 'TravelRequests.status',
                7 => 'TravelRequests.created',
            ];
            
            $orderField = $columns[$orderColumn] ?? 'TravelRequests.created';
            
            // Build query
            $query = $this->TravelRequests->find()
                ->contain(['Users']);
            
            // Search
            if (!empty($searchValue)) {
                $query->where([
                    'OR' => [
                        'TravelRequests.request_number LIKE' => '%' . $searchValue . '%',
                        'TravelRequests.destination LIKE' => '%' . $searchValue . '%',
                        'TravelRequests.purpose_of_travel LIKE' => '%' . $searchValue . '%',
                        'TravelRequests.status LIKE' => '%' . $searchValue . '%',
                        'Users.name LIKE' => '%' . $searchValue . '%',
                    ]
                ]);
            }
            
            // Total records
            $recordsTotal = $this->TravelRequests->find()->count();
            $recordsFiltered = $query->count();
            
            // Get data with pagination and ordering
            $travelRequests = $query
                ->order([$orderField => $orderDir])
                ->limit($length)
                ->offset($start)
                ->all();
            
            // Format data for DataTables
            $data = [];
            foreach ($travelRequests as $request) {
                $userData = [
                    'id' => $request->user->id ?? null,
                    'name' => $request->user->name ?? 'N/A',
                    'email' => $request->user->email ?? '',
                ];
                
                $data[] = [
                    'request_number' => $request->request_number,
                    'user' => $userData,
                    'destination' => $request->destination,
                    'travel_type' => $request->travel_type,
                    'departure_date' => $request->departure_date->format('Y-m-d'),
                    'return_date' => $request->return_date->format('Y-m-d'),
                    'duration_days' => $request->duration_days ?? 0,
                    'status' => $request->status,
                    'accommodation_required' => $request->accommodation_required,
                    'total_allowance' => $request->total_allowance ?? 0.00,
                    'current_step' => $request->current_step ?? 1,
                    'purpose' => substr($request->purpose_of_travel ?? '', 0, 100),
                    'created' => $request->created->format('Y-m-d H:i:s'),
                    'id' => $request->id,
                ];
            }
            
            // Return JSON response
            $response = [
                'draw' => (int)$draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ];
            
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($response));
        }
        
        // Regular page view
        $this->set('travelRequests', []);
    }

    /**
     * View method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['Users' => ['JobLevels'], 'WorkflowHistory'],
        ]);

        // Load allowance rate details if allowances have been calculated
        $allowanceRate = null;
        if ($travelRequest->total_allowance > 0 && $travelRequest->user && $travelRequest->user->job_level_id) {
            $this->loadModel('AllowanceRates');
            $allowanceRate = $this->AllowanceRates->find()
                ->where([
                    'job_level_id' => $travelRequest->user->job_level_id,
                    'travel_type' => $travelRequest->travel_type
                ])
                ->first();
        }

        $this->set(compact('travelRequest', 'allowanceRate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $travelRequest = $this->TravelRequests->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Store line manager information from Microsoft Graph
            if (!empty($data['line_manager_email'])) {
                // Find or create user for line manager
                $lineManager = $this->TravelRequests->Users->find()
                    ->where(['email' => $data['line_manager_email']])
                    ->first();
                
                if ($lineManager) {
                    $data['line_manager_id'] = $lineManager->id;
                } else {
                    // Create a temporary user record for the line manager
                    $lineManager = $this->TravelRequests->Users->newEntity([
                        'email' => $data['line_manager_email'],
                        'first_name' => explode(' ', $data['line_manager_display_name'])[0] ?? '',
                        'last_name' => substr($data['line_manager_display_name'], strpos($data['line_manager_display_name'], ' ') + 1) ?: '',
                        'microsoft_id' => $data['line_manager_graph_id'],
                        'is_active' => true
                    ]);
                    
                    if ($this->TravelRequests->Users->save($lineManager)) {
                        $data['line_manager_id'] = $lineManager->id;
                    }
                }
            }
            
            // Store traveler information from Microsoft Graph
            if (!empty($data['user_email'])) {
                $traveler = $this->TravelRequests->Users->find()
                    ->where(['email' => $data['user_email']])
                    ->first();
                
                if ($traveler) {
                    $data['user_id'] = $traveler->id;
                    
                    // Update job level if provided and user doesn't have one
                    if (!empty($data['job_level_id']) && !$traveler->job_level_id) {
                        $traveler->job_level_id = $data['job_level_id'];
                        $this->TravelRequests->Users->save($traveler);
                    }
                } else {
                    // Create user record for the traveler with job level
                    $traveler = $this->TravelRequests->Users->newEntity([
                        'email' => $data['user_email'],
                        'first_name' => explode(' ', $data['user_display_name'])[0] ?? '',
                        'last_name' => substr($data['user_display_name'], strpos($data['user_display_name'], ' ') + 1) ?: '',
                        'microsoft_id' => $data['user_graph_id'],
                        'job_level_id' => $data['job_level_id'] ?? null,
                        'is_active' => true
                    ]);
                    
                    if ($this->TravelRequests->Users->save($traveler)) {
                        $data['user_id'] = $traveler->id;
                    }
                }
            }
            
            // Handle file upload for email conversation
            $file = $this->request->getData('email_conversation');
            if ($file && $file->getError() === UPLOAD_ERR_OK) {
                $filename = $file->getClientFilename();
                $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
                
                // Generate unique filename
                $newFilename = 'email_conversation_' . uniqid() . '.' . $fileExtension;
                $uploadPath = WWW_ROOT . 'files' . DS . 'travel_requests' . DS;
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move uploaded file
                $destination = $uploadPath . $newFilename;
                $file->moveTo($destination);
                
                // Store relative path in database
                $data['email_conversation_file'] = 'files/travel_requests/' . $newFilename;
                $data['email_conversation_uploaded_at'] = new \DateTime();
            }
            
            // Don't calculate allowances yet - wait for line manager approval
            // Set allowances to 0.00 initially
            $data['accommodation_allowance'] = '0.00';
            $data['feeding_allowance'] = '0.00';
            $data['transport_allowance'] = '0.00';
            $data['incidental_allowance'] = '0.00';
            $data['total_allowance'] = '0.00';
            
            // Set default values
            $data['status'] = 'submitted';
            $data['current_step'] = 2; // Awaiting line manager approval
            
            // Generate request number
            $data['request_number'] = 'TR-' . date('Ymd') . '-' . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $data);
            if ($this->TravelRequests->save($travelRequest)) {
                // Reload travel request with user data for notification
                $travelRequest = $this->TravelRequests->get($travelRequest->id, ['contain' => ['Users']]);
                
                // Send approval notification to line manager via Teams or Email
                $notificationSent = $this->_sendApprovalNotification($travelRequest, $lineManager);
                
                if ($notificationSent) {
                    $this->Flash->success(__('Travel request submitted successfully! Approval notification sent to line manager.'));
                } else {
                    $this->Flash->success(__('Travel request submitted successfully! Please inform your line manager manually.'));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
        }
        
        $users = $this->TravelRequests->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($user) {
                $jobLevel = $user->job_level ? ' (' . $user->job_level->level_name . ')' : '';
                return $user->first_name . ' ' . $user->last_name . $jobLevel;
            }
        ])
        ->contain(['JobLevels'])
        ->where(['Users.is_active' => true])
        ->all();
        
        // Get job levels for dropdown
        $this->loadModel('JobLevels');
        $jobLevels = $this->JobLevels->find('list', [
            'keyField' => 'id',
            'valueField' => function ($jobLevel) {
                return $jobLevel->level_name . ' (' . $jobLevel->level_code . ')';
            }
        ])
        ->order(['id' => 'ASC'])
        ->all();
        
        $this->set(compact('travelRequest', 'users', 'jobLevels'));
    }

    /**
     * Get allowance rates via AJAX
     *
     * @return \Cake\Http\Response JSON response
     */
    public function getAllowanceRates()
    {
        $this->request->allowMethod(['get', 'post']);
        $this->viewBuilder()->setLayout('ajax');
        
        $userId = $this->request->getQuery('user_id');
        $travelType = $this->request->getQuery('travel_type', 'local');
        $durationDays = (int)$this->request->getQuery('duration_days', 1);
        $accommodationRequired = $this->request->getQuery('accommodation_required') === 'true';
        
        if (!$userId) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'User ID is required'
                ]));
        }
        
        $allowances = $this->_calculateAllowances($userId, $travelType, $durationDays, $accommodationRequired);
        
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'data' => $allowances
            ]));
    }

    /**
     * Calculate allowances based on user's job level and travel details
     *
     * @param int $userId User ID
     * @param string $travelType Travel type (local/international)
     * @param int $durationDays Duration in days
     * @param bool $accommodationRequired Whether accommodation is required
     * @return array Calculated allowances
     */
    protected function _calculateAllowances($userId, $travelType, $durationDays, $accommodationRequired)
    {
        $this->loadModel('Users');
        $this->loadModel('AllowanceRates');
        
        // Get user's job level
        $user = $this->Users->get($userId, ['contain' => ['JobLevels']]);
        
        if (!$user->job_level_id) {
            return [
                'accommodation_allowance' => '0.00',
                'transport_allowance' => '0.00',
                'per_diem_allowance' => '0.00',
                'total_allowance' => '0.00',
                'currency' => 'NGN',
            ];
        }
        
        // Get allowance rates for the user's job level and travel type
        $allowanceRate = $this->AllowanceRates->find()
            ->where([
                'job_level_id' => $user->job_level_id,
                'travel_type' => $travelType
            ])
            ->first();
        
        if (!$allowanceRate) {
            return [
                'accommodation_allowance' => '0.00',
                'transport_allowance' => '0.00',
                'per_diem_allowance' => '0.00',
                'total_allowance' => '0.00',
                'currency' => 'NGN',
            ];
        }
        
        // Calculate allowances based on travel type
        if ($travelType === 'local') {
            // LOCAL TRAVEL: Hotel Accommodation (per night) + Per Diem (out-station allowance per day)
            $accommodationAllowance = $accommodationRequired 
                ? (float)$allowanceRate->accommodation_rate * $durationDays 
                : 0;
            $perDiemAllowance = (float)$allowanceRate->per_diem_rate * $durationDays;
            $transportAllowance = 0; // Not applicable for local travel
            
            $totalAllowance = $accommodationAllowance + $perDiemAllowance;
        } else {
            // INTERNATIONAL TRAVEL: Out of Station Allowance (Per Diem) + Transport Per Day
            $accommodationAllowance = 0; // Hotel is separate/booked directly
            $transportAllowance = (float)$allowanceRate->transport_rate * $durationDays;
            $perDiemAllowance = (float)$allowanceRate->per_diem_rate * $durationDays;
            
            $totalAllowance = $perDiemAllowance + $transportAllowance;
        }
        
        return [
            'accommodation_allowance' => number_format($accommodationAllowance, 2, '.', ''),
            'transport_allowance' => number_format($transportAllowance, 2, '.', ''),
            'per_diem_allowance' => number_format($perDiemAllowance, 2, '.', ''),
            'feeding_allowance' => '0.00', // Deprecated - kept for compatibility
            'incidental_allowance' => '0.00', // Deprecated - kept for compatibility
            'total_allowance' => number_format($totalAllowance, 2, '.', ''),
            'currency' => $allowanceRate->currency,
            'flight_class' => $allowanceRate->flight_class,
            'hotel_standard' => $allowanceRate->hotel_standard,
            'room_type' => $allowanceRate->room_type,
            'alternate_accommodation' => $allowanceRate->alternate_accommodation,
        ];
    }

    /**
     * Edit method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->getData());
            if ($this->TravelRequests->save($travelRequest)) {
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
        }
        $users = $this->TravelRequests->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('travelRequest', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $travelRequest = $this->TravelRequests->get($id);
        if ($this->TravelRequests->delete($travelRequest)) {
            $this->Flash->success(__('The travel request has been deleted.'));
        } else {
            $this->Flash->error(__('The travel request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Line Manager Approval
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects after approval.
     */
    public function lineManagerApprove($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $travelRequest = $this->TravelRequests->get($id, ['contain' => ['Users']]);
        
        // Get current logged-in user's ID (line manager)
        $session = $this->request->getSession();
        $currentUserId = $session->read('Auth.User.id');

        // Enforce only assigned line manager can approve
        if (!$currentUserId || $travelRequest->line_manager_id !== (int)$currentUserId) {
            $this->Flash->error(__('Only the assigned line manager can approve this request.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $travelRequest->status = 'lm_approved';
        $travelRequest->current_step = 3;
        $travelRequest->line_manager_approved_at = new \DateTime();
        $travelRequest->line_manager_id = $currentUserId ?? $travelRequest->line_manager_id;
        $travelRequest->line_manager_comments = $this->request->getData('comments');
        
        if ($this->TravelRequests->save($travelRequest)) {
            // Create workflow history entry
            $this->loadModel('WorkflowHistory');
            $workflowHistory = $this->WorkflowHistory->newEntity([
                'travel_request_id' => $travelRequest->id,
                'from_status' => 'submitted',
                'to_status' => 'lm_approved',
                'action_by' => $currentUserId ?? $travelRequest->line_manager_id,
                'comments' => $this->request->getData('comments'),
                'created' => new \DateTime()
            ]);
            $this->WorkflowHistory->save($workflowHistory);
            
            // Notify requester of approval
            $this->_notifyRequesterApproval($travelRequest);
            
            $this->Flash->success(__('Travel request has been approved by Line Manager. Admin can now calculate allowances.'));
        } else {
            $this->Flash->error(__('Unable to approve the request. Please try again.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Line Manager Rejection
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects after rejection.
     */
    public function lineManagerReject($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $travelRequest = $this->TravelRequests->get($id, ['contain' => ['Users']]);
        
        // Get current logged-in user's ID (line manager)
        $session = $this->request->getSession();
        $currentUserId = $session->read('Auth.User.id');

        // Enforce only assigned line manager can reject
        if (!$currentUserId || $travelRequest->line_manager_id !== (int)$currentUserId) {
            $this->Flash->error(__('Only the assigned line manager can reject this request.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $travelRequest->status = 'lm_rejected';
        $travelRequest->rejection_reason = $this->request->getData('reason');
        $travelRequest->rejected_at = new \DateTime();
        $travelRequest->rejected_by = $currentUserId ?? $travelRequest->line_manager_id;
        $travelRequest->current_step = 2; // Keep at line manager step
        
        if ($this->TravelRequests->save($travelRequest)) {
            // Create workflow history entry
            $this->loadModel('WorkflowHistory');
            $workflowHistory = $this->WorkflowHistory->newEntity([
                'travel_request_id' => $travelRequest->id,
                'from_status' => 'submitted',
                'to_status' => 'lm_rejected',
                'action_by' => $currentUserId ?? $travelRequest->line_manager_id,
                'comments' => $this->request->getData('reason'),
                'created' => new \DateTime()
            ]);
            $this->WorkflowHistory->save($workflowHistory);
            
            // Notify requester of rejection
            $this->_notifyRequesterRejection($travelRequest);
            
            $this->Flash->error(__('Travel request has been rejected.'));
        } else {
            $this->Flash->error(__('Unable to reject the request. Please try again.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Calculate Allowances (Admin action after Line Manager approval)
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects after calculation.
     */
    public function calculateAllowances($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $travelRequest = $this->TravelRequests->get($id, ['contain' => ['Users']]);
        
        // Check if line manager has approved
        if ($travelRequest->status !== 'lm_approved') {
            $this->Flash->error(__('Allowances can only be calculated after Line Manager approval.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        // Calculate allowances based on user's job level
        $allowances = $this->_calculateAllowances(
            $travelRequest->user_id,
            $travelRequest->travel_type,
            $travelRequest->duration_days,
            $travelRequest->accommodation_required
        );
        
        // Update travel request with calculated allowances
        $travelRequest->accommodation_allowance = $allowances['accommodation_allowance'];
        $travelRequest->feeding_allowance = $allowances['feeding_allowance'];
        $travelRequest->transport_allowance = $allowances['transport_allowance'];
        $travelRequest->incidental_allowance = $allowances['incidental_allowance'];
        $travelRequest->per_diem_allowance = $allowances['per_diem_allowance'];
        $travelRequest->total_allowance = $allowances['total_allowance'];
        $travelRequest->status = 'admin_processing';
        $travelRequest->current_step = 4;
        
        // Get current admin ID
        $session = $this->request->getSession();
        $currentUserId = $session->read('Auth.User.id');
        $travelRequest->admin_id = $currentUserId ?? 1;
        
        if ($this->TravelRequests->save($travelRequest)) {
            // Create workflow history entry
            $this->loadModel('WorkflowHistory');
            $workflowHistory = $this->WorkflowHistory->newEntity([
                'travel_request_id' => $travelRequest->id,
                'from_status' => 'lm_approved',
                'to_status' => 'admin_processing',
                'action_by' => $currentUserId ?? 1,
                'comments' => 'Allowances calculated: Total ' . ($allowances['currency'] === 'USD' ? '$' : '₦') . number_format((float)$allowances['total_allowance'], 2),
                'created' => new \DateTime()
            ]);
            $this->WorkflowHistory->save($workflowHistory);
            
            $this->Flash->success(__(
                'Allowances calculated successfully! Total: {0} {1}',
                $allowances['currency'] === 'USD' ? '$' : '₦',
                number_format((float)$allowances['total_allowance'], 2)
            ));
        } else {
            $this->Flash->error(__('Unable to calculate allowances. Please try again.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Complete Travel Request
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects after completion.
     */
    public function complete($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $travelRequest = $this->TravelRequests->get($id, ['contain' => ['Users']]);
        
        // Check if allowances have been calculated
        if ($travelRequest->status !== 'admin_processing') {
            $this->Flash->error(__('Request must have calculated allowances before completion.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        // Get current admin ID
        $session = $this->request->getSession();
        $currentUserId = $session->read('Auth.User.id');
        
        $travelRequest->status = 'completed';
        $travelRequest->current_step = 5;
        $travelRequest->completed_at = new \DateTime();
        $travelRequest->completed_by = $currentUserId ?? 1;
        
        if ($this->TravelRequests->save($travelRequest)) {
            // Create workflow history entry
            $this->loadModel('WorkflowHistory');
            $workflowHistory = $this->WorkflowHistory->newEntity([
                'travel_request_id' => $travelRequest->id,
                'from_status' => 'admin_processing',
                'to_status' => 'completed',
                'action_by' => $currentUserId ?? 1,
                'comments' => 'Travel request completed',
                'created' => new \DateTime()
            ]);
            $this->WorkflowHistory->save($workflowHistory);
            
            $this->Flash->success(__('Travel request has been marked as complete!'));
        } else {
            $this->Flash->error(__('Unable to complete the request. Please try again.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }
    
    /**
     * Send Teams Approval Card (Public Action)
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects back to view.
     */
    public function sendTeamsApprovalCard($id = null)
    {
        $this->request->allowMethod(['post']);
        
        try {
            $travelRequest = $this->TravelRequests->get($id, [
                'contain' => ['Users']
            ]);
            
            // Check if request is in appropriate status
            if (!in_array($travelRequest->status, ['submitted', 'lm_approved'])) {
                $this->Flash->error(__('Teams notifications can only be sent for submitted or approved requests.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Get line manager details
            $lineManager = null;
            if ($travelRequest->line_manager_id) {
                $lineManager = $this->TravelRequests->Users->get($travelRequest->line_manager_id);
            }
            
            if (!$lineManager) {
                $this->Flash->error(__('Line manager information not found. Cannot send notification.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            if (!$lineManager->microsoft_id) {
                $this->Flash->error(__('Line manager does not have a Microsoft ID. Please use email notification instead.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Send Teams notification
            $sent = $this->_sendTeamsApprovalCard($travelRequest, $lineManager);
            
            if ($sent) {
                $this->Flash->success(__('💬 Teams approval card sent successfully to ' . $lineManager->first_name . ' ' . $lineManager->last_name));
            } else {
                $this->Flash->warning(__('⚠️ Unable to send Teams notification. This may be due to network issues or missing permissions. Try email notification instead.'));
            }
            
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            \Cake\Log\Log::error('Travel request not found: ' . $e->getMessage());
            $this->Flash->error(__('Travel request not found.'));
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error sending Teams notification: ' . $e->getMessage());
            $this->Flash->error(__('🌐 Network error: Unable to connect to Microsoft Teams. Please check your connection and try again.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }
    
    /**
     * Send Email Approval Notification (Public Action)
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects back to view.
     */
    public function sendEmailApprovalNotification($id = null)
    {
        $this->request->allowMethod(['post']);
        
        try {
            $travelRequest = $this->TravelRequests->get($id, [
                'contain' => ['Users']
            ]);
            
            // Check if request is in appropriate status
            if (!in_array($travelRequest->status, ['submitted', 'lm_approved'])) {
                $this->Flash->error(__('Email notifications can only be sent for submitted or approved requests.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Get line manager details
            $lineManager = null;
            if ($travelRequest->line_manager_id) {
                $lineManager = $this->TravelRequests->Users->get($travelRequest->line_manager_id);
            }
            
            if (!$lineManager) {
                $this->Flash->error(__('Line manager information not found. Cannot send notification.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            if (!$lineManager->email) {
                $this->Flash->error(__('Line manager does not have an email address.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Send email notification
            $sent = $this->_sendEmailApprovalNotification($travelRequest, $lineManager);
            
            if ($sent) {
                $this->Flash->success(__('📧 Email approval notification sent successfully to ' . $lineManager->email));
            } else {
                $this->Flash->warning(__('⚠️ Unable to send email notification. This may be due to network issues or email service problems. Please try again later.'));
            }
            
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            \Cake\Log\Log::error('Travel request not found: ' . $e->getMessage());
            $this->Flash->error(__('Travel request not found.'));
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error sending email notification: ' . $e->getMessage());
            $this->Flash->error(__('🌐 Network error: Unable to connect to email service. Please check your connection and try again.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }
    
    /**
     * Send approval notification to line manager via Microsoft Teams (with email fallback)
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @param \App\Model\Entity\User $lineManager Line manager entity
     * @return bool True if notification sent successfully
     */
    protected function _sendApprovalNotification($travelRequest, $lineManager)
    {
        // Try to send via Microsoft Teams first
        $teamsSent = $this->_sendTeamsApprovalCard($travelRequest, $lineManager);
        
        if ($teamsSent) {
            \Cake\Log\Log::info('Teams approval notification sent successfully for request ' . $travelRequest->request_number);
            return true;
        }
        
        // Fallback to email if Teams fails
        \Cake\Log\Log::warning('Teams notification failed, falling back to email for request ' . $travelRequest->request_number);
        return $this->_sendEmailApprovalNotification($travelRequest, $lineManager);
    }
    
    /**
     * Send Microsoft Teams adaptive card with approve/reject actions
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @param \App\Model\Entity\User $lineManager Line manager entity
     * @return bool True if Teams message sent successfully
     */
    protected function _sendTeamsApprovalCard($travelRequest, $lineManager)
    {
        try {
            $session = $this->request->getSession();
            $accessToken = $session->read('Auth.AccessToken');
            
            if (!$accessToken) {
                \Cake\Log\Log::warning('No access token available for Teams notification');
                return false;
            }
            
            $config = \Cake\Core\Configure::read('Azure');
            $http = new \Cake\Http\Client(['timeout' => 30]);
            
            // Get traveler name from the user association
            $travelerName = isset($travelRequest->user) 
                ? $travelRequest->user->first_name . ' ' . $travelRequest->user->last_name
                : 'Unknown Traveler';
            
            // Build approval URLs
            $approveUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'approve',
                $travelRequest->id
            ], true);
            
            $rejectUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'reject',
                $travelRequest->id
            ], true);
            
            $viewUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'view',
                $travelRequest->id
            ], true);
            
            // Create adaptive card for Teams
            $adaptiveCard = [
                'type' => 'message',
                'attachments' => [
                    [
                        'contentType' => 'application/vnd.microsoft.card.adaptive',
                        'content' => [
                            '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                            'type' => 'AdaptiveCard',
                            'version' => '1.4',
                            'body' => [
                                [
                                    'type' => 'Container',
                                    'style' => 'emphasis',
                                    'items' => [
                                        [
                                            'type' => 'TextBlock',
                                            'text' => '✈️ Travel Request Approval Needed',
                                            'weight' => 'Bolder',
                                            'size' => 'Large',
                                            'color' => 'Accent'
                                        ],
                                        [
                                            'type' => 'TextBlock',
                                            'text' => 'You have a new travel request pending your approval',
                                            'spacing' => 'Small',
                                            'isSubtle' => true,
                                            'wrap' => true
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'FactSet',
                                    'facts' => [
                                        [
                                            'title' => '📋 Request #:',
                                            'value' => $travelRequest->request_number
                                        ],
                                        [
                                            'title' => '👤 Traveler:',
                                            'value' => $travelerName
                                        ],
                                        [
                                            'title' => '📍 Destination:',
                                            'value' => $travelRequest->destination
                                        ],
                                        [
                                            'title' => '✈️ Type:',
                                            'value' => ucfirst($travelRequest->travel_type)
                                        ],
                                        [
                                            'title' => '🛫 Departure:',
                                            'value' => $travelRequest->departure_date->format('M d, Y')
                                        ],
                                        [
                                            'title' => '🛬 Return:',
                                            'value' => $travelRequest->return_date->format('M d, Y')
                                        ],
                                        [
                                            'title' => '📅 Duration:',
                                            'value' => $travelRequest->duration_days . ' days'
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'TextBlock',
                                    'text' => '**Purpose of Travel:**',
                                    'weight' => 'Bolder',
                                    'spacing' => 'Medium'
                                ],
                                [
                                    'type' => 'TextBlock',
                                    'text' => substr($travelRequest->purpose_of_travel, 0, 200) . (strlen($travelRequest->purpose_of_travel) > 200 ? '...' : ''),
                                    'wrap' => true,
                                    'spacing' => 'Small'
                                ]
                            ],
                            'actions' => [
                                [
                                    'type' => 'Action.OpenUrl',
                                    'title' => '✅ Approve Request',
                                    'url' => $approveUrl,
                                    'style' => 'positive'
                                ],
                                [
                                    'type' => 'Action.OpenUrl',
                                    'title' => '❌ Reject Request',
                                    'url' => $rejectUrl,
                                    'style' => 'destructive'
                                ],
                                [
                                    'type' => 'Action.OpenUrl',
                                    'title' => '👁️ View Details',
                                    'url' => $viewUrl
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            
            // Send message via Microsoft Graph API (Teams Chat)
            // Get current user's microsoft_id for creating the chat
            $session = $this->request->getSession();
            $currentUserMicrosoftId = $session->read('Auth.User.microsoft_id');
            $currentUserEmail = $session->read('Auth.User.email');
            
            if (!$currentUserMicrosoftId) {
                \Cake\Log\Log::warning('Current user Microsoft ID not found in session for Teams notification');
                return false;
            }
            
            // Create a 1-on-1 chat between current user and line manager
            $response = $http->post(
                $config['graphApiEndpoint'] . '/chats',
                json_encode([
                    'chatType' => 'oneOnOne',
                    'members' => [
                        [
                            '@odata.type' => '#microsoft.graph.aadUserConversationMember',
                            'roles' => ['owner'],
                            'user@odata.bind' => "https://graph.microsoft.com/v1.0/users('" . $currentUserMicrosoftId . "')"
                        ],
                        [
                            '@odata.type' => '#microsoft.graph.aadUserConversationMember',
                            'roles' => ['owner'],
                            'user@odata.bind' => "https://graph.microsoft.com/v1.0/users('" . $lineManager->microsoft_id . "')"
                        ]
                    ]
                ]),
                ['type' => 'json', 'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]]
            );
            
            if ($response->isOk()) {
                $chatData = $response->getJson();
                $chatId = $chatData['id'] ?? null;
                
                if ($chatId) {
                    // Send message to the chat
                    $messageResponse = $http->post(
                        $config['graphApiEndpoint'] . '/chats/' . $chatId . '/messages',
                        json_encode($adaptiveCard),
                        ['type' => 'json', 'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]]
                    );
                    
                    if ($messageResponse->isOk()) {
                        \Cake\Log\Log::info('Teams approval card sent successfully to ' . $lineManager->email);
                        return true;
                    }
                    
                    \Cake\Log\Log::error('Failed to send message to Teams chat: ' . $messageResponse->getStringBody());
                    return false;
                }
            }
            
            \Cake\Log\Log::error('Failed to create Teams chat: ' . $response->getStringBody());
            return false;
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception sending Teams notification: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email approval notification via Microsoft Graph API (fallback method)
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @param \App\Model\Entity\User $lineManager Line manager entity
     * @return bool True if email sent successfully
     */
    protected function _sendEmailApprovalNotification($travelRequest, $lineManager)
    {
        try {
            $session = $this->request->getSession();
            $accessToken = $session->read('Auth.AccessToken');
            
            if (!$accessToken) {
                \Cake\Log\Log::warning('No access token available for email notification');
                return false;
            }
            
            $config = \Cake\Core\Configure::read('Azure');
            $http = new Client(['timeout' => 30]);
            
            // Get traveler name from the user association
            $travelerName = isset($travelRequest->user) 
                ? $travelRequest->user->first_name . ' ' . $travelRequest->user->last_name
                : 'Unknown Traveler';
            
            // Build approval URLs
            $approveUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'approve',
                $travelRequest->id
            ], true);
            
            $rejectUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'reject',
                $travelRequest->id
            ], true);
            
            $viewUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'view',
                $travelRequest->id
            ], true);
            
            // Build HTML email body
            $emailBody = $this->_buildApprovalEmailHtml($travelRequest, $lineManager, $travelerName, $approveUrl, $rejectUrl, $viewUrl);
            
            // Create email message using Microsoft Graph API
            $emailData = [
                'message' => [
                    'subject' => '✈️ Travel Request Approval Needed - ' . $travelRequest->request_number,
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $emailBody
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $lineManager->email,
                                'name' => $lineManager->first_name . ' ' . $lineManager->last_name
                            ]
                        ]
                    ],
                    'importance' => 'high'
                ],
                'saveToSentItems' => 'true'
            ];
            
            // Send email via Microsoft Graph API
            $response = $http->post(
                $config['graphApiEndpoint'] . '/me/sendMail',
                json_encode($emailData),
                ['type' => 'json', 'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]]
            );
            
            if ($response->isOk()) {
                \Cake\Log\Log::info('Email approval notification sent via Graph API for request ' . $travelRequest->request_number);
                return true;
            }
            
            $errorBody = $response->getStringBody();
            \Cake\Log\Log::error('Failed to send email via Graph API: ' . $errorBody);
            return false;
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception sending email via Graph API: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Approve travel request (Line Manager action)
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects after approval.
     */
    public function approve($id = null)
    {
        $travelRequest = $this->TravelRequests->get($id, ['contain' => ['Users']]);
        
        $this->set(compact('travelRequest'));
        
        if ($this->request->is(['post', 'put'])) {
            // Get current logged-in user's ID (line manager)
            $session = $this->request->getSession();
            $currentUserId = $session->read('Auth.User.id');
            $currentUserEmail = $session->read('Auth.User.email');

            // Enforce only assigned line manager can approve
            if (!$currentUserId || $travelRequest->line_manager_id !== (int)$currentUserId) {
                $this->Flash->error(__('Only the assigned line manager can approve this request.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Update travel request with approval details
            $travelRequest->status = 'lm_approved';
            $travelRequest->current_step = 3;
            $travelRequest->line_manager_approved_at = new \DateTime();
            $travelRequest->line_manager_comments = $this->request->getData('comments');
            
            // Set line_manager_id if we have current user
            if ($currentUserId) {
                $travelRequest->line_manager_id = $currentUserId;
            } elseif ($currentUserEmail && $travelRequest->line_manager_id) {
                // Already has line manager id from submission
                // Keep existing value
            }
            
            if ($this->TravelRequests->save($travelRequest)) {
                // Create workflow history entry
                $this->loadModel('WorkflowHistory');
                $workflowHistory = $this->WorkflowHistory->newEntity([
                    'travel_request_id' => $travelRequest->id,
                    'from_status' => 'submitted',
                    'to_status' => 'lm_approved',
                    'action_by' => $currentUserId ?? $travelRequest->line_manager_id,
                    'comments' => $this->request->getData('comments'),
                    'created' => new \DateTime()
                ]);
                $this->WorkflowHistory->save($workflowHistory);
                
                // Notify requester of approval
                $this->_notifyRequesterApproval($travelRequest);
                
                $this->Flash->success(__('✅ Travel request approved successfully! Admin will now calculate allowances.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            $this->Flash->error(__('Unable to approve the request. Please try again.'));
        }
    }
    
    /**
     * Reject travel request (Line Manager action)
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects after rejection.
     */
    public function reject($id = null)
    {
        $travelRequest = $this->TravelRequests->get($id, ['contain' => ['Users']]);
        
        $this->set(compact('travelRequest'));
        
        if ($this->request->is(['post', 'put'])) {
            // Get current logged-in user's ID (line manager)
            $session = $this->request->getSession();
            $currentUserId = $session->read('Auth.User.id');
            $currentUserEmail = $session->read('Auth.User.email');

            // Enforce only assigned line manager can reject
            if (!$currentUserId || $travelRequest->line_manager_id !== (int)$currentUserId) {
                $this->Flash->error(__('Only the assigned line manager can reject this request.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Update travel request with rejection details
            $travelRequest->status = 'lm_rejected';
            $travelRequest->rejection_reason = $this->request->getData('reason');
            $travelRequest->rejected_at = new \DateTime();
            $travelRequest->current_step = 2; // Keep at line manager step
            
            // Set rejected_by if we have current user
            if ($currentUserId) {
                $travelRequest->rejected_by = $currentUserId;
            } elseif ($travelRequest->line_manager_id) {
                $travelRequest->rejected_by = $travelRequest->line_manager_id;
            }
            
            if ($this->TravelRequests->save($travelRequest)) {
                // Create workflow history entry
                $this->loadModel('WorkflowHistory');
                $workflowHistory = $this->WorkflowHistory->newEntity([
                    'travel_request_id' => $travelRequest->id,
                    'from_status' => 'submitted',
                    'to_status' => 'lm_rejected',
                    'action_by' => $currentUserId ?? $travelRequest->line_manager_id,
                    'comments' => $this->request->getData('reason'),
                    'created' => new \DateTime()
                ]);
                $this->WorkflowHistory->save($workflowHistory);
                
                // Notify requester of rejection
                $this->_notifyRequesterRejection($travelRequest);
                
                $this->Flash->error(__('❌ Travel request has been rejected.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            
            $this->Flash->error(__('Unable to reject the request. Please try again.'));
        }
    }
    
    /**
     * Resend approval notification to line manager
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Http\Response|null|void Redirects back to view.
     */
    public function resendApprovalNotification($id = null)
    {
        $this->request->allowMethod(['post']);
        
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['Users']
        ]);
        
        // Check if request is in submitted status
        if ($travelRequest->status !== 'submitted') {
            $this->Flash->error(__('Approval notifications can only be sent for submitted requests.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        // Get line manager details
        $lineManager = null;
        if ($travelRequest->line_manager_id) {
            $lineManager = $this->TravelRequests->Users->get($travelRequest->line_manager_id);
        }
        
        if (!$lineManager) {
            $this->Flash->error(__('Line manager information not found. Cannot send notification.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        // Resend the notification
        $notificationSent = $this->_sendApprovalNotification($travelRequest, $lineManager);
        
        if ($notificationSent) {
            $this->Flash->success(__('📧 Approval notification resent successfully to ' . $lineManager->first_name . ' ' . $lineManager->last_name));
        } else {
            $this->Flash->warning(__('⚠️ Failed to send notification. Please check logs or notify the line manager manually.'));
        }
        
        return $this->redirect(['action' => 'view', $id]);
    }
    
    /**
     * Notify requester when their travel request is approved
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @return bool True if notification sent successfully
     */
    protected function _notifyRequesterApproval($travelRequest)
    {
        try {
            // Try Teams first
            $teamsSent = $this->_sendTeamsRequesterApproval($travelRequest);
            
            if ($teamsSent) {
                \Cake\Log\Log::info('Teams approval notification sent to requester for request ' . $travelRequest->request_number);
                return true;
            }
            
            // Fallback to email
            return $this->_sendEmailRequesterApproval($travelRequest);
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Failed to notify requester of approval: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notify requester when their travel request is rejected
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @return bool True if notification sent successfully
     */
    protected function _notifyRequesterRejection($travelRequest)
    {
        try {
            // Try Teams first
            $teamsSent = $this->_sendTeamsRequesterRejection($travelRequest);
            
            if ($teamsSent) {
                \Cake\Log\Log::info('Teams rejection notification sent to requester for request ' . $travelRequest->request_number);
                return true;
            }
            
            // Fallback to email
            return $this->_sendEmailRequesterRejection($travelRequest);
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Failed to notify requester of rejection: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send Teams message to requester about approval
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @return bool True if Teams message sent successfully
     */
    protected function _sendTeamsRequesterApproval($travelRequest)
    {
        try {
            $session = $this->request->getSession();
            $accessToken = $session->read('Auth.AccessToken');
            
            if (!$accessToken || !$travelRequest->user_graph_id) {
                return false;
            }
            
            $config = \Cake\Core\Configure::read('Azure');
            $http = new Client();
            
            $viewUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'view',
                $travelRequest->id
            ], true);
            
            // Create adaptive card
            $adaptiveCard = [
                'type' => 'message',
                'attachments' => [
                    [
                        'contentType' => 'application/vnd.microsoft.card.adaptive',
                        'content' => [
                            '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                            'type' => 'AdaptiveCard',
                            'version' => '1.4',
                            'body' => [
                                [
                                    'type' => 'Container',
                                    'style' => 'good',
                                    'items' => [
                                        [
                                            'type' => 'TextBlock',
                                            'text' => '✅ Travel Request Approved!',
                                            'weight' => 'Bolder',
                                            'size' => 'Large',
                                            'color' => 'Good'
                                        ],
                                        [
                                            'type' => 'TextBlock',
                                            'text' => 'Great news! Your travel request has been approved by your line manager.',
                                            'spacing' => 'Small',
                                            'wrap' => true
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'FactSet',
                                    'facts' => [
                                        [
                                            'title' => '📋 Request #:',
                                            'value' => $travelRequest->request_number
                                        ],
                                        [
                                            'title' => '📍 Destination:',
                                            'value' => $travelRequest->destination
                                        ],
                                        [
                                            'title' => '📅 Travel Dates:',
                                            'value' => $travelRequest->departure_date->format('M d') . ' - ' . $travelRequest->return_date->format('M d, Y')
                                        ],
                                        [
                                            'title' => '✅ Status:',
                                            'value' => 'Approved - Awaiting Admin Processing'
                                        ]
                                    ]
                                ]
                            ],
                            'actions' => [
                                [
                                    'type' => 'Action.OpenUrl',
                                    'title' => '👁️ View Request Details',
                                    'url' => $viewUrl
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            
            if (!empty($travelRequest->line_manager_comments)) {
                $adaptiveCard['attachments'][0]['content']['body'][] = [
                    'type' => 'TextBlock',
                    'text' => '**Manager Comments:**',
                    'weight' => 'Bolder',
                    'spacing' => 'Medium'
                ];
                $adaptiveCard['attachments'][0]['content']['body'][] = [
                    'type' => 'TextBlock',
                    'text' => $travelRequest->line_manager_comments,
                    'wrap' => true,
                    'spacing' => 'Small'
                ];
            }
            
            // Send via Teams
            $response = $http->post(
                $config['graphApiEndpoint'] . '/chats',
                json_encode([
                    'chatType' => 'oneOnOne',
                    'members' => [
                        [
                            '@odata.type' => '#microsoft.graph.aadUserConversationMember',
                            'roles' => ['owner'],
                            'user@odata.bind' => "https://graph.microsoft.com/v1.0/users('" . $travelRequest->user_graph_id . "')"
                        ]
                    ]
                ]),
                ['type' => 'json', 'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]]
            );
            
            if ($response->isOk()) {
                $chatData = $response->getJson();
                $chatId = $chatData['id'] ?? null;
                
                if ($chatId) {
                    $messageResponse = $http->post(
                        $config['graphApiEndpoint'] . '/chats/' . $chatId . '/messages',
                        json_encode($adaptiveCard),
                        ['type' => 'json', 'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]]
                    );
                    
                    return $messageResponse->isOk();
                }
            }
            
            return false;
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception sending Teams approval to requester: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send Teams message to requester about rejection
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @return bool True if Teams message sent successfully
     */
    protected function _sendTeamsRequesterRejection($travelRequest)
    {
        try {
            $session = $this->request->getSession();
            $accessToken = $session->read('Auth.AccessToken');
            
            if (!$accessToken || !$travelRequest->user_graph_id) {
                return false;
            }
            
            $config = \Cake\Core\Configure::read('Azure');
            $http = new Client();
            
            $viewUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'view',
                $travelRequest->id
            ], true);
            
            // Create adaptive card
            $adaptiveCard = [
                'type' => 'message',
                'attachments' => [
                    [
                        'contentType' => 'application/vnd.microsoft.card.adaptive',
                        'content' => [
                            '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                            'type' => 'AdaptiveCard',
                            'version' => '1.4',
                            'body' => [
                                [
                                    'type' => 'Container',
                                    'style' => 'attention',
                                    'items' => [
                                        [
                                            'type' => 'TextBlock',
                                            'text' => '❌ Travel Request Not Approved',
                                            'weight' => 'Bolder',
                                            'size' => 'Large',
                                            'color' => 'Attention'
                                        ],
                                        [
                                            'type' => 'TextBlock',
                                            'text' => 'Unfortunately, your travel request has not been approved.',
                                            'spacing' => 'Small',
                                            'wrap' => true
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'FactSet',
                                    'facts' => [
                                        [
                                            'title' => '📋 Request #:',
                                            'value' => $travelRequest->request_number
                                        ],
                                        [
                                            'title' => '📍 Destination:',
                                            'value' => $travelRequest->destination
                                        ],
                                        [
                                            'title' => '📅 Travel Dates:',
                                            'value' => $travelRequest->departure_date->format('M d') . ' - ' . $travelRequest->return_date->format('M d, Y')
                                        ],
                                        [
                                            'title' => '❌ Status:',
                                            'value' => 'Rejected'
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'TextBlock',
                                    'text' => '**Rejection Reason:**',
                                    'weight' => 'Bolder',
                                    'spacing' => 'Medium'
                                ],
                                [
                                    'type' => 'TextBlock',
                                    'text' => $travelRequest->rejection_reason ?? 'No reason provided',
                                    'wrap' => true,
                                    'spacing' => 'Small',
                                    'color' => 'Attention'
                                ]
                            ],
                            'actions' => [
                                [
                                    'type' => 'Action.OpenUrl',
                                    'title' => '👁️ View Request Details',
                                    'url' => $viewUrl
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            
            // Send via Teams
            $response = $http->post(
                $config['graphApiEndpoint'] . '/chats',
                json_encode([
                    'chatType' => 'oneOnOne',
                    'members' => [
                        [
                            '@odata.type' => '#microsoft.graph.aadUserConversationMember',
                            'roles' => ['owner'],
                            'user@odata.bind' => "https://graph.microsoft.com/v1.0/users('" . $travelRequest->user_graph_id . "')"
                        ]
                    ]
                ]),
                ['type' => 'json', 'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]]
            );
            
            if ($response->isOk()) {
                $chatData = $response->getJson();
                $chatId = $chatData['id'] ?? null;
                
                if ($chatId) {
                    $messageResponse = $http->post(
                        $config['graphApiEndpoint'] . '/chats/' . $chatId . '/messages',
                        json_encode($adaptiveCard),
                        ['type' => 'json', 'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]]
                    );
                    
                    return $messageResponse->isOk();
                }
            }
            
            return false;
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception sending Teams rejection to requester: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build HTML email body for approval notification
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @param \App\Model\Entity\User $lineManager Line manager entity
     * @param string $travelerName Traveler's display name
     * @param string $approveUrl Approval URL
     * @param string $rejectUrl Rejection URL
     * @param string $viewUrl View details URL
     * @return string HTML email body
     */
    protected function _buildApprovalEmailHtml($travelRequest, $lineManager, $travelerName, $approveUrl, $rejectUrl, $viewUrl)
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <div style="background-color: #0c5343; color: #ffffff; padding: 30px; text-align: center;">
            <h1 style="margin: 0 0 10px 0; font-size: 24px; color: #ffffff;">✈️ Travel Request Approval Needed</h1>
            <p style="margin: 0; font-size: 16px; color: #ffffff;">You have a new travel request pending your approval</p>
        </div>
        <div style="padding: 30px; background-color: #ffffff;">
            <p style="margin: 0 0 15px 0; color: #333333;">Hello ' . htmlspecialchars($lineManager->first_name ?? 'Manager') . ',</p>
            <p style="margin: 0 0 20px 0; color: #333333;">A new travel request has been submitted and requires your approval.</p>
            
            <div style="background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px;">
                <div style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                    <span style="font-weight: bold; color: #6c757d;">📋 Request Number:</span>
                    <span style="color: #333333;"> ' . htmlspecialchars($travelRequest->request_number) . '</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                    <span style="font-weight: bold; color: #6c757d;">👤 Traveler:</span>
                    <span style="color: #333333;"> ' . htmlspecialchars($travelerName) . '</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                    <span style="font-weight: bold; color: #6c757d;">📍 Destination:</span>
                    <span style="color: #333333;"> ' . htmlspecialchars($travelRequest->destination) . '</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                    <span style="font-weight: bold; color: #6c757d;">✈️ Travel Type:</span>
                    <span style="color: #333333;"> ' . htmlspecialchars(ucfirst($travelRequest->travel_type)) . '</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                    <span style="font-weight: bold; color: #6c757d;">🛫 Departure:</span>
                    <span style="color: #333333;"> ' . $travelRequest->departure_date->format('F d, Y') . '</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                    <span style="font-weight: bold; color: #6c757d;">🛬 Return:</span>
                    <span style="color: #333333;"> ' . $travelRequest->return_date->format('F d, Y') . '</span>
                </div>
                <div style="padding: 10px 0;">
                    <span style="font-weight: bold; color: #6c757d;">📅 Duration:</span>
                    <span style="color: #333333;"> ' . (int)$travelRequest->duration_days . ' days</span>
                </div>
            </div>
            
            <div style="margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #0c5343; border-radius: 4px;">
                <strong style="color: #333333;">📝 Purpose:</strong><br>
                <span style="color: #333333;">' . nl2br(htmlspecialchars($travelRequest->purpose_of_travel)) . '</span>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($approveUrl) . '" style="display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 6px; font-weight: bold; background-color: #28a745; color: #ffffff;">✅ Approve Request</a>
                <a href="' . htmlspecialchars($rejectUrl) . '" style="display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 6px; font-weight: bold; background-color: #dc3545; color: #ffffff;">❌ Reject Request</a>
                <a href="' . htmlspecialchars($viewUrl) . '" style="display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 6px; font-weight: bold; background-color: #17a2b8; color: #ffffff;">👁️ View Details</a>
            </div>
            
            <p style="color: #6c757d; font-size: 14px; margin: 20px 0 0 0;">
                <strong style="color: #6c757d;">Note:</strong> This request is awaiting your approval. Please respond as soon as possible.
            </p>
        </div>
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 13px;">
            <p style="margin: 5px 0;"><strong style="color: #6c757d;">Travel Request Management System</strong></p>
            <p style="margin: 5px 0; color: #6c757d;">This is an automated notification from the system.</p>
            <p style="margin: 5px 0; color: #6c757d;">&copy; ' . date('Y') . ' All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * Send email to requester about approval via Microsoft Graph API
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @return bool True if email sent successfully
     */
    protected function _sendEmailRequesterApproval($travelRequest)
    {
        try {
            $session = $this->request->getSession();
            $accessToken = $session->read('Auth.AccessToken');
            
            if (!$accessToken || !$travelRequest->user_email) {
                return false;
            }
            
            $config = \Cake\Core\Configure::read('Azure');
            $http = new Client();
            
            $viewUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'view',
                $travelRequest->id
            ], true);
            
            // Build HTML email body
            $emailBody = $this->_buildRequesterApprovalEmailHtml($travelRequest, $viewUrl);
            
            // Create email message
            $emailData = [
                'message' => [
                    'subject' => '✅ Your Travel Request Has Been Approved - ' . $travelRequest->request_number,
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $emailBody
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $travelRequest->user_email,
                                'name' => $travelRequest->user_display_name
                            ]
                        ]
                    ],
                    'importance' => 'high'
                ],
                'saveToSentItems' => 'true'
            ];
            
            // Send email via Microsoft Graph API
            $response = $http->post(
                $config['graphApiEndpoint'] . '/me/sendMail',
                json_encode($emailData),
                ['type' => 'json', 'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]]
            );
            
            if ($response->isOk()) {
                \Cake\Log\Log::info('Email approval notification sent to requester via Graph API for request ' . $travelRequest->request_number);
                return true;
            }
            
            \Cake\Log\Log::error('Failed to send approval email to requester via Graph API: ' . $response->getStringBody());
            return false;
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception sending approval email to requester via Graph API: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email to requester about rejection via Microsoft Graph API
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @return bool True if email sent successfully
     */
    protected function _sendEmailRequesterRejection($travelRequest)
    {
        try {
            $session = $this->request->getSession();
            $accessToken = $session->read('Auth.AccessToken');
            
            if (!$accessToken || !$travelRequest->user_email) {
                return false;
            }
            
            $config = \Cake\Core\Configure::read('Azure');
            $http = new Client();
            
            $viewUrl = \Cake\Routing\Router::url([
                'controller' => 'TravelRequests',
                'action' => 'view',
                $travelRequest->id
            ], true);
            
            // Build HTML email body
            $emailBody = $this->_buildRequesterRejectionEmailHtml($travelRequest, $viewUrl);
            
            // Create email message
            $emailData = [
                'message' => [
                    'subject' => '❌ Travel Request Update - ' . $travelRequest->request_number,
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $emailBody
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $travelRequest->user_email,
                                'name' => $travelRequest->user_display_name
                            ]
                        ]
                    ],
                    'importance' => 'normal'
                ],
                'saveToSentItems' => 'true'
            ];
            
            // Send email via Microsoft Graph API
            $response = $http->post(
                $config['graphApiEndpoint'] . '/me/sendMail',
                json_encode($emailData),
                ['type' => 'json', 'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]]
            );
            
            if ($response->isOk()) {
                \Cake\Log\Log::info('Email rejection notification sent to requester via Graph API for request ' . $travelRequest->request_number);
                return true;
            }
            
            \Cake\Log\Log::error('Failed to send rejection email to requester via Graph API: ' . $response->getStringBody());
            return false;
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception sending rejection email to requester via Graph API: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build HTML email body for requester approval notification
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @param string $viewUrl View details URL
     * @return string HTML email body
     */
    protected function _buildRequesterApprovalEmailHtml($travelRequest, $viewUrl)
    {
        $comments = !empty($travelRequest->line_manager_comments) 
            ? '<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <strong style="color: #856404;">💬 Manager\'s Comments:</strong><br>
                <span style="color: #856404;">' . nl2br(htmlspecialchars($travelRequest->line_manager_comments)) . '</span>
            </div>' 
            : '';
        
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <div style="background-color: #28a745; color: #ffffff; padding: 30px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 15px; color: #ffffff;">✅</div>
            <h1 style="margin: 0 0 10px 0; font-size: 24px; color: #ffffff;">Request Approved!</h1>
            <p style="margin: 0; font-size: 16px; color: #ffffff;">Your travel request has been approved</p>
        </div>
        <div style="padding: 30px; background-color: #ffffff;">
            <div style="background-color: #d4edda; border: 2px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 8px; text-align: center;">
                <h2 style="color: #155724; margin: 0 0 10px 0;">🎉 Great News!</h2>
                <p style="color: #155724; margin: 0;">Your line manager has approved your travel request.</p>
            </div>
            
            <div style="background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; color: #333333;">
                <strong style="color: #333333;">📋 Request Number:</strong> ' . htmlspecialchars($travelRequest->request_number) . '<br>
                <strong style="color: #333333;">📍 Destination:</strong> ' . htmlspecialchars($travelRequest->destination) . '<br>
                <strong style="color: #333333;">📅 Travel Dates:</strong> ' . $travelRequest->departure_date->format('M d') . ' - ' . $travelRequest->return_date->format('M d, Y') . ' (' . (int)$travelRequest->duration_days . ' days)<br>
                <strong style="color: #333333;">✅ Status:</strong> <span style="color: #28a745; font-weight: bold;">Approved</span>
            </div>
            
            ' . $comments . '
            
            <div style="background-color: #e7f3ff; border-left: 4px solid #0c5343; padding: 15px; margin: 20px 0; border-radius: 4px; color: #333333;">
                <strong style="color: #333333;">📌 Next Steps:</strong><br>
                <span style="color: #333333;">
                • Admin will calculate your travel allowances<br>
                • Wait for final confirmation before booking<br>
                • Check the system regularly for updates
                </span>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($viewUrl) . '" style="display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 6px; font-weight: bold; background-color: #0c5343; color: #ffffff;">👁️ View Request Details</a>
            </div>
        </div>
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 13px;">
            <p style="margin: 5px 0;"><strong style="color: #6c757d;">Travel Request Management System</strong></p>
            <p style="margin: 5px 0; color: #6c757d;">&copy; ' . date('Y') . ' All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * Build HTML email body for requester rejection notification
     *
     * @param \App\Model\Entity\TravelRequest $travelRequest Travel request entity
     * @param string $viewUrl View details URL
     * @return string HTML email body
     */
    protected function _buildRequesterRejectionEmailHtml($travelRequest, $viewUrl)
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <div style="background-color: #dc3545; color: #ffffff; padding: 30px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 15px; color: #ffffff;">❌</div>
            <h1 style="margin: 0 0 10px 0; font-size: 24px; color: #ffffff;">Request Not Approved</h1>
            <p style="margin: 0; font-size: 16px; color: #ffffff;">Your travel request update</p>
        </div>
        <div style="padding: 30px; background-color: #ffffff;">
            <div style="background-color: #f8d7da; border: 2px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 8px; text-align: center;">
                <h2 style="color: #721c24; margin: 0 0 10px 0;">Travel Request Update</h2>
                <p style="color: #721c24; margin: 0;">Unfortunately, your travel request has not been approved.</p>
            </div>
            
            <div style="background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; color: #333333;">
                <strong style="color: #333333;">📋 Request Number:</strong> ' . htmlspecialchars($travelRequest->request_number) . '<br>
                <strong style="color: #333333;">📍 Destination:</strong> ' . htmlspecialchars($travelRequest->destination) . '<br>
                <strong style="color: #333333;">📅 Travel Dates:</strong> ' . $travelRequest->departure_date->format('M d') . ' - ' . $travelRequest->return_date->format('M d, Y') . '<br>
                <strong style="color: #333333;">❌ Status:</strong> <span style="color: #dc3545; font-weight: bold;">Not Approved</span>
            </div>
            
            <div style="background-color: #fff3cd; border-left: 4px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <strong style="color: #721c24;">📝 Reason for Decision:</strong><br>
                <span style="color: #856404;">' . nl2br(htmlspecialchars($travelRequest->rejection_reason ?? 'No reason provided.')) . '</span>
            </div>
            
            <div style="background-color: #e7f3ff; border-left: 4px solid #0c5343; padding: 15px; margin: 20px 0; border-radius: 4px; color: #333333;">
                <strong style="color: #333333;">📌 What You Can Do:</strong><br>
                <span style="color: #333333;">
                • Review the feedback carefully<br>
                • Discuss with your manager<br>
                • Submit a revised request if needed<br>
                • Do not proceed with travel arrangements
                </span>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($viewUrl) . '" style="display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 6px; font-weight: bold; background-color: #0c5343; color: #ffffff;">👁️ View Request Details</a>
            </div>
        </div>
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 13px;">
            <p style="margin: 5px 0;"><strong style="color: #6c757d;">Travel Request Management System</strong></p>
            <p style="margin: 5px 0; color: #6c757d;">&copy; ' . date('Y') . ' All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }
}
