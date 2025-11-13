<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client;

/**
 * Auth Controller
 * Handles Microsoft Azure AD authentication
 */
class AuthController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // Don't call parent::beforeFilter() to skip authentication check
        // Allow public access to all auth actions
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        
        // Set login layout for login and callback pages
        if (in_array($this->request->getParam('action'), ['login', 'callback', 'test'])) {
            $this->viewBuilder()->setLayout('login');
        }
    }

    /**
     * Test page for troubleshooting Microsoft Graph
     */
    public function test()
    {
        // Just render the test view
    }

    /**
     * Login - Redirect to Microsoft OAuth
     */
    public function login()
    {
        // Check if already logged in
        $session = $this->request->getSession();
        $user = $session->read('Auth.User');
        \Cake\Log\Log::debug('[Auth] Enter login. Auth.User present=' . ($user ? 'yes' : 'no') . ' savedRedirect=' . var_export($session->read('Auth.redirect'), true));
        
        if ($user) {
            // Already logged in, check if there's a redirect URL
            $redirectUrl = (string)$session->read('Auth.redirect');
            // Guard against loops to /login or callback
            if ($redirectUrl && !in_array($redirectUrl, ['/login', '/auth/callback'], true)) {
                \Cake\Log\Log::debug('[Auth] Logged in user hitting /login; redirecting to saved ' . $redirectUrl);
                $session->delete('Auth.redirect');
                return $this->redirect($redirectUrl);
            }
            \Cake\Log\Log::debug('[Auth] Logged in user hitting /login with no valid saved redirect; sending to dashboard');
            // No valid redirect saved
            $session->delete('Auth.redirect');
            // Otherwise redirect to dashboard
            return $this->redirect(['controller' => 'FreshInvoices', 'action' => 'index']);
        }
        
        $config = \Cake\Core\Configure::read('Azure');
        
        if (!$config || !$config['clientId'] || !$config['tenantId']) {
            $this->Flash->error('Azure AD configuration is missing. Please check your .env file.');
            return $this->redirect(['action' => 'test']);
        }
        
        $params = [
            'client_id' => $config['clientId'],
            'response_type' => 'code',
            'redirect_uri' => $config['redirectUri'],
            'response_mode' => 'query',
            'scope' => implode(' ', $config['scopes']),
            'state' => bin2hex(random_bytes(16)),
            // Force a fresh sign-in/account picker to avoid stuck Windows/WAM sessions
            'prompt' => 'select_account',
        ];
        
    // Store state in session for CSRF protection
    $this->request->getSession()->write('oauth_state', $params['state']);
    \Cake\Log\Log::info('[Auth] Starting OAuth login. SessionID=' . session_id() . ' State=' . $params['state']);
        
        $authUrl = "https://login.microsoftonline.com/{$config['tenantId']}/oauth2/v2.0/authorize?" . http_build_query($params);
        
    \Cake\Log\Log::debug('[Auth] Redirecting to Azure authorize URL');
    return $this->redirect($authUrl);
    }

    /**
     * OAuth Callback
     */
    public function callback()
    {
        $config = \Cake\Core\Configure::read('Azure');
        $session = $this->request->getSession();
        
        // Clear any existing flash messages from previous session
        $session->delete('Flash');
        
        // Verify state parameter
        $state = $this->request->getQuery('state');
        $savedState = $session->read('oauth_state');
        
        if (!$savedState || $state !== $savedState) {
            \Cake\Log\Log::warning('[Auth] OAuth state mismatch or missing. SessionID=' . session_id() . ' state=' . var_export($state, true) . ' savedState=' . var_export($savedState, true));
            // Clear the invalid state
            $session->delete('oauth_state');
            $this->Flash->error('Invalid authentication state. Please try again.');
            return $this->redirect(['action' => 'login']);
        }
        
        // Clear the state after successful verification
        $session->delete('oauth_state');
        
        $code = $this->request->getQuery('code');
        if (!$code) {
            \Cake\Log\Log::warning('[Auth] OAuth callback without authorization code. Query=' . json_encode($this->request->getQueryParams()));
            $error = $this->request->getQuery('error_description')
                ?? $this->request->getQuery('error')
                ?? null;
            $message = 'Authentication failed.' . ($error ? ' ' . $error : '');
            $this->Flash->error($message);
            return $this->redirect(['action' => 'login']);
        }
        
        try {
            // Exchange code for token
            $http = new Client(['timeout' => 30]);
            $tokenUrl = "https://login.microsoftonline.com/{$config['tenantId']}/oauth2/v2.0/token";
            
            $response = $http->post($tokenUrl, [
                'client_id' => $config['clientId'],
                'client_secret' => $config['clientSecret'],
                'code' => $code,
                'redirect_uri' => $config['redirectUri'],
                'grant_type' => 'authorization_code',
                'scope' => implode(' ', $config['scopes']),
            ]);
            
            if (!$response->isOk()) {
                $errorData = $response->getJson();
                $errorMsg = $errorData['error_description'] ?? 'Failed to obtain access token';
                \Cake\Log\Log::error('OAuth token error: ' . $response->getStringBody());
                $this->Flash->error('Authentication error: ' . $errorMsg);
                return $this->redirect(['action' => 'login']);
            }
            
            $tokenData = $response->getJson();
            
            if (!isset($tokenData['access_token'])) {
                $this->Flash->error('Invalid token response from Microsoft. Please try again.');
                return $this->redirect(['action' => 'login']);
            }
            
            $accessToken = $tokenData['access_token'];
            
            // Get user profile from Microsoft Graph
            try {
                $profileResponse = $http->get(
                    $config['graphApiEndpoint'] . '/me',
                    [],
                    ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]
                );
                
                if (!$profileResponse->isOk()) {
                    \Cake\Log\Log::error('Graph API profile error: ' . $profileResponse->getStringBody());
                    $this->Flash->error('Unable to retrieve your profile from Microsoft. Please contact support.');
                    return $this->redirect(['action' => 'login']);
                }
                
                $profile = $profileResponse->getJson();
                
                if (!isset($profile['id'])) {
                    $this->Flash->error('Invalid profile data received. Please try again.');
                    return $this->redirect(['action' => 'login']);
                }
                
            } catch (\Cake\Http\Client\Exception\NetworkException $e) {
                \Cake\Log\Log::error('Network error fetching profile: ' . $e->getMessage());
                $this->Flash->error('Network connection issue. Please check your internet and try again.');
                return $this->redirect(['action' => 'login']);
            }
            
            // Find or create user
            $this->loadModel('Users');
            
            try {
                $userEmail = $profile['mail'] ?? $profile['userPrincipalName'] ?? null;
                
                if (!$userEmail) {
                    $this->Flash->error('Unable to retrieve your email address. Please contact support.');
                    return $this->redirect(['action' => 'login']);
                }
                
                $user = $this->Users->find()
                    ->where(['email' => $userEmail])
                    ->first();
                
                if (!$user) {
                    // Determine role based on admin emails config
                    $adminEmails = \Cake\Core\Configure::read('Admin.emails') ?? [];
                    $role = in_array(strtolower($userEmail), array_map('strtolower', $adminEmails), true) ? 'admin' : 'user';

                    // Create new user
                    $user = $this->Users->newEntity([
                        'email' => $userEmail,
                        'first_name' => $profile['givenName'] ?? 'User',
                        'last_name' => $profile['surname'] ?? '',
                        'microsoft_id' => $profile['id'],
                        'role' => $role, // Assign admin if configured, else user
                        'is_active' => true,
                    ]);
                    
                    if (!$this->Users->save($user)) {
                        \Cake\Log\Log::error('Failed to create user: ' . json_encode($user->getErrors()));
                        $this->Flash->error('Unable to create your account. Please contact support.');
                        return $this->redirect(['action' => 'login']);
                    }
                } else {
                    // Update user info
                    $user->microsoft_id = $profile['id'];
                    $user->last_login = new \DateTime();

                    // Optionally promote/demote role based on config
                    $adminEmails = \Cake\Core\Configure::read('Admin.emails') ?? [];
                    $shouldBeAdmin = in_array(strtolower($userEmail), array_map('strtolower', $adminEmails), true);
                    if ($shouldBeAdmin && $user->role !== 'admin') {
                        $user->role = 'admin';
                    }
                    
                    if (!$this->Users->save($user)) {
                        \Cake\Log\Log::warning('Failed to update user login time: ' . json_encode($user->getErrors()));
                        // Don't block login for this
                    }
                }
                
            } catch (\Exception $e) {
                \Cake\Log\Log::error('Database error during user lookup/creation: ' . $e->getMessage());
                $this->Flash->error('A database error occurred. Please try again or contact support.');
                return $this->redirect(['action' => 'login']);
            }
            
            // Store user info and token in session
            try {
                $displayName = trim((string)($user->first_name ?? '') . ' ' . (string)($user->last_name ?? '')) ?: (string)$user->email;
                $session->write('Auth.User', [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $displayName,
                    'role' => $user->role,
                    'microsoft_id' => $user->microsoft_id,
                ]);
                $session->write('Auth.AccessToken', $accessToken);
                
                $this->Flash->success('Welcome back, ' . $displayName . '!');
                
                // Check if there's a saved redirect URL
                $redirectUrl = (string)$session->read('Auth.redirect');
                if ($redirectUrl && !in_array($redirectUrl, ['/login', '/auth/callback'], true)) {
                    \Cake\Log\Log::debug('[Auth] Post-login redirect to ' . $redirectUrl);
                    // Clear the saved redirect
                    $session->delete('Auth.redirect');
                    // Redirect to the intended page
                    return $this->redirect($redirectUrl);
                }
                \Cake\Log\Log::debug('[Auth] Post-login no redirect stored; sending to dashboard');
                
                // Default redirect to dashboard
                return $this->redirect(['controller' => 'FreshInvoices', 'action' => 'index']);
                
            } catch (\Exception $e) {
                \Cake\Log\Log::error('Session error: ' . $e->getMessage());
                $this->Flash->error('Unable to establish your session. Please try again.');
                return $this->redirect(['action' => 'login']);
            }
            
        } catch (\Cake\Http\Client\Exception\NetworkException $e) {
            \Cake\Log\Log::error('Network exception during OAuth: ' . $e->getMessage());
            $this->Flash->error('🌐 Network connection error. Please check your internet connection and try again.');
            return $this->redirect(['action' => 'login']);
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Unexpected error during OAuth callback: ' . $e->getMessage());
            $this->Flash->error('An unexpected error occurred. Please try again or contact support if the problem persists.');
            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $session = $this->request->getSession();
        $user = $session->read('Auth.User');
        $userName = $user['name'] ?? 'User';
        
        // Remove only authentication data, keep CSRF token
        $session->delete('Auth');
        
        // Set the logout message
        $this->Flash->success('Goodbye ' . $userName . '! You have been logged out successfully.');
        
        // Redirect to landing page
        return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
    }

    /**
     * Get users from Microsoft Graph API for dropdown
     */
    public function getGraphUsers()
    {
        $this->autoRender = false;
        $session = $this->request->getSession();
        $accessToken = $session->read('Auth.AccessToken');
        
        if (!$accessToken) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'error' => 'Not authenticated. Please login again.',
                    'redirect' => true
                ]));
        }
        
        $config = \Cake\Core\Configure::read('Azure');
        $http = new Client(['timeout' => 30]);
        
        try {
            // Fetch ALL users using pagination
            $allUsers = [];
            $nextLink = $config['graphApiEndpoint'] . '/users?$select=id,displayName,mail,userPrincipalName,givenName,surname,jobTitle&$top=999';
            
            do {
                $response = $http->get(
                    $nextLink,
                    [],
                    ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]
                );
                
                if (!$response->isOk()) {
                    $errorBody = $response->getStringBody();
                    \Cake\Log\Log::error('Microsoft Graph API Error: ' . $errorBody);
                    
                    // Check if token is expired
                    if ($response->getStatusCode() === 401) {
                        return $this->response
                            ->withType('application/json')
                            ->withStringBody(json_encode([
                                'error' => 'Your session has expired. Please login again.',
                                'redirect' => true
                            ]));
                    }
                    
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'error' => 'Failed to fetch users from Microsoft Graph. Status: ' . $response->getStatusCode()
                        ]));
                }
                
                $data = $response->getJson();
                
                // Validate response structure
                if (!isset($data['value']) || !is_array($data['value'])) {
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'error' => 'Invalid response from Microsoft Graph API'
                        ]));
                }
                
                // Add users from current page
                foreach ($data['value'] as $graphUser) {
                    $allUsers[] = [
                        'id' => $graphUser['id'] ?? '',
                        'name' => $graphUser['displayName'] ?? 'Unknown User',
                        'email' => $graphUser['mail'] ?? $graphUser['userPrincipalName'] ?? '',
                        'jobTitle' => $graphUser['jobTitle'] ?? '',
                    ];
                }
                
                // Get next page link if available
                $nextLink = $data['@odata.nextLink'] ?? null;
                
            } while ($nextLink);
            
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'users' => $allUsers,
                    'total' => count($allUsers)
                ]));
                
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Exception in getGraphUsers: ' . $e->getMessage());
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'error' => 'An error occurred while fetching users: ' . $e->getMessage()
                ]));
        }
    }
}
