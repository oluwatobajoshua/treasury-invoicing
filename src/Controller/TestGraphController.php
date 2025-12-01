<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Test Controller for Microsoft Graph Integration
 * 
 * Access at: /test-graph
 */
class TestGraphController extends AppController
{
    /**
     * Index - Test Microsoft Graph functionality
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('default');
        
        $session = $this->request->getSession();
        $accessToken = $session->read('Auth.AccessToken');
        $authUser = $session->read('Auth.User');
        
        if (!$accessToken) {
            $this->Flash->error('You must be logged in to test Microsoft Graph');
            return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
        }
        
        $results = [];
        $errors = [];
        
        // Initialize Graph Service
        try {
            $graphService = new \App\Service\MicrosoftGraphService($accessToken);
            
            // Test 1: Get all users
            $results['users'] = $graphService->getAllUsers();
            $results['userCount'] = count($results['users']);
            
            // Test 2: Get users for dropdown
            $results['dropdown'] = $graphService->getUsersForDropdown();
            $results['dropdownCount'] = count($results['dropdown']);
            
            // Test 3: Get my profile
            $results['myProfile'] = $graphService->getMyProfile();
            
            $results['success'] = true;
            
        } catch (\Exception $e) {
            $errors[] = 'Exception: ' . $e->getMessage();
            $results['success'] = false;
        }
        
        $this->set(compact('results', 'errors', 'authUser'));
    }
    
    /**
     * Send test email
     */
    public function sendTestEmail()
    {
        $this->viewBuilder()->setLayout('default');
        
        $session = $this->request->getSession();
        $accessToken = $session->read('Auth.AccessToken');
        $authUser = $session->read('Auth.User');
        
        if (!$accessToken) {
            $this->Flash->error('You must be logged in to send test email');
            return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
        }
        
        $result = null;
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            $graphService = new \App\Service\MicrosoftGraphService($accessToken);
            
            $to = array_filter(array_map('trim', explode(',', $data['to'] ?? '')));
            $cc = !empty($data['cc']) ? array_filter(array_map('trim', explode(',', $data['cc']))) : [];
            
            if (empty($to)) {
                $this->Flash->error('Please enter at least one recipient');
            } else {
                $result = $graphService->sendEmail([
                    'to' => $to,
                    'cc' => $cc,
                    'subject' => $data['subject'] ?? 'Test Email from Treasury Invoice System',
                    'body' => $data['body'] ?? '<p>This is a test email sent via Microsoft Graph API.</p>'
                ]);
                
                if ($result['success']) {
                    $this->Flash->success('Test email sent successfully!');
                } else {
                    $this->Flash->error('Failed to send email: ' . $result['message']);
                }
            }
        }
        
        $this->set(compact('result', 'authUser'));
    }
}
