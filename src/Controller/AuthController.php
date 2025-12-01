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
        parent::beforeFilter($event);
        
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
        
        // Check internet connectivity before attempting login
        if (!$this->checkInternetConnectivity()) {
            \Cake\Log\Log::error('[Auth] No internet connectivity detected');
            $this->Flash->error('⚠️ Network Connection Required<br><br>You are not connected to the internet. Please check your network connection and try again.<br><br><small>The Treasury Invoicing System requires an active internet connection to authenticate via Microsoft Azure AD.</small>', ['escape' => false]);
            return null; // Stay on login page
        }
        
        $config = \Cake\Core\Configure::read('Azure');
        
        if (!$config || !$config['clientId'] || !$config['tenantId']) {
            $this->Flash->error('Azure AD configuration is missing. Please check your .env file.');
            return $this->redirect(['action' => 'test']);
        }
        
        // PKCE support (required when Microsoft treats the flow as public/cross-origin)
        // Generate a high-entropy code_verifier and derive the S256 code_challenge.
        $codeVerifier = rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '=');
        $session->write('oauth_pkce_verifier', $codeVerifier);
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        $prompt = \Cake\Core\Configure::read('debug') ? 'consent' : 'select_account';
        $params = [
            'client_id' => $config['clientId'],
            'response_type' => 'code',
            'redirect_uri' => $config['redirectUri'],
            'response_mode' => 'query',
            'scope' => implode(' ', $config['scopes']),
            'state' => bin2hex(random_bytes(16)),
            'prompt' => $prompt,
            // PKCE parameters
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ];
        
        // Store state in session for CSRF protection
        $this->request->getSession()->write('oauth_state', $params['state']);
        \Cake\Log\Log::info('[Auth] Starting OAuth login. SessionID=' . session_id() . ' State=' . $params['state']);
            
            $authUrl = "https://login.microsoftonline.com/{$config['tenantId']}/oauth2/v2.0/authorize?" . http_build_query($params);
            
        // Show a small splash page to allow client-side logging before navigation
        $this->set('authUrl', $authUrl);
        // If a previous error occurred, don't auto-redirect to avoid loops
        $auto = !$session->read('Auth.BlockAutoLogin');
        $this->set('autoRedirect', $auto);
        \Cake\Log\Log::debug('[Auth] Rendering redirect splash to Azure authorize URL; autoRedirect=' . ($auto ? 'yes' : 'no'));
        return $this->render('redirect_to_azure');
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
        
        // Check internet connectivity
        if (!$this->checkInternetConnectivity()) {
            \Cake\Log\Log::error('[Auth] No internet connectivity during callback');
            $this->Flash->error('⚠️ Network Connection Lost<br><br>Your internet connection was lost during authentication. Please check your network and try again.', ['escape' => false]);
            return $this->redirect(['action' => 'login']);
        }
        
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
        
        // Handle error responses from Azure authorize endpoint
        $authorizeError = $this->request->getQuery('error') ?: null;
        if ($authorizeError) {
            $errorDesc = $this->request->getQuery('error_description') ?: '';
            $errorCodes = $this->request->getQuery('error_codes'); // may be array
            $traceId = $this->request->getQuery('trace_id');
            $correlationId = $this->request->getQuery('correlation_id');
            $sanitized = $this->sanitizeAzureError($errorDesc ?: $authorizeError);
            \Cake\Log\Log::warning('[Auth] Azure authorize error: error=' . $authorizeError . ' desc=' . $errorDesc . ' codes=' . var_export($errorCodes, true) . ' trace=' . var_export($traceId, true) . ' correlation=' . var_export($correlationId, true));
            // Build user message safely whether sanitizeAzureError returns string or array
            if (is_array($sanitized)) {
                $friendly = $sanitized['message'];
                $codePart = $sanitized['code'] ? ' (' . $sanitized['code'] . ')' : '';
                $userMsg = 'Sign-in failed' . $codePart . ': ' . $friendly . '<br><small>Please try again. If this persists, contact support' . ($sanitized['code'] ? ' with code ' . $sanitized['code'] : '') . '.</small>';
            } else {
                $userMsg = 'Sign-in failed: ' . $sanitized . '<br><small>Please try again. If this persists, contact support.</small>';
            }
            $this->Flash->error($userMsg, ['escape' => false]);
            // Prevent immediate auto-redirect on /login to avoid loops
            $session->write('Auth.BlockAutoLogin', true);
            return $this->redirect(['action' => 'login']);
        }

        $code = $this->request->getQuery('code');
        if (!$code) {
            \Cake\Log\Log::warning('[Auth] OAuth callback without authorization code and no explicit error. Query=' . json_encode($this->request->getQueryParams()));
            $this->Flash->error('Authentication failed. No authorization code was returned. Please try again.');
            $session->write('Auth.BlockAutoLogin', true);
            return $this->redirect(['action' => 'login']);
        }
        
        try {
            // Exchange code for token
            $http = new Client(['timeout' => 30]);
            $tokenUrl = "https://login.microsoftonline.com/{$config['tenantId']}/oauth2/v2.0/token";
            
            // Include PKCE code_verifier if present
            $tokenParams = [
                'client_id' => $config['clientId'],
                'code' => $code,
                'redirect_uri' => $config['redirectUri'],
                'grant_type' => 'authorization_code',
                'scope' => implode(' ', $config['scopes']),
            ];
            $pkceVerifier = $session->read('oauth_pkce_verifier');
            if ($pkceVerifier) {
                $tokenParams['code_verifier'] = $pkceVerifier;
                // Clear after use
                $session->delete('oauth_pkce_verifier');
            }
            // Do NOT include client_secret when using PKCE for public/native clients.
            // If your Azure app is a confidential web app, set a flag in config to include the secret.
            $isConfidential = \Cake\Core\Configure::read('Azure.isConfidential') === true;
            if ($isConfidential && !empty($config['clientSecret'])) {
                $tokenParams['client_secret'] = $config['clientSecret'];
            }
            $response = $http->post($tokenUrl, $tokenParams);
            
            if (!$response->isOk()) {
                $rawBody = $response->getStringBody();
                $errorData = [];
                try { $errorData = $response->getJson(); } catch (\Exception $e) { /* non-JSON */ }
                $errorMsgRaw = $errorData['error_description'] ?? $errorData['error'] ?? 'Failed to obtain access token';
                $sanitized = $this->sanitizeAzureError($errorMsgRaw);
                \Cake\Log\Log::error('OAuth token error: body=' . $rawBody . ' parsed=' . json_encode($errorData));
                if (is_array($sanitized)) {
                    $display = 'Token exchange failed' . ($sanitized['code'] ? ' (' . $sanitized['code'] . ')' : '') . ': ' . $sanitized['message'];
                } else {
                    $display = 'Token exchange failed: ' . $sanitized;
                }
                $this->Flash->error($display . '<br><small>Please retry sign-in. If this continues, contact support.</small>', ['escape' => false]);
                $session->write('Auth.BlockAutoLogin', true);
                return $this->redirect(['action' => 'login']);
            }
            
            $tokenData = $response->getJson();
            
            if (!isset($tokenData['access_token'])) {
                $this->Flash->error('Invalid token response from Microsoft. Please try again.');
                $session->write('Auth.BlockAutoLogin', true);
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
                    $status = $profileResponse->getStatusCode();
                    $body = $profileResponse->getStringBody();
                    \Cake\Log\Log::error('Graph API /me failed. Status=' . $status . ' Body=' . $body);
                    $errorData = [];
                    try { $errorData = $profileResponse->getJson(); } catch (\Exception $e) { }
                    $apiError = $errorData['error']['message'] ?? 'Unable to retrieve your profile from Microsoft.';
                    $hint = 'This often means the token is missing required Graph scopes (e.g., User.Read).';
                    // Detect common permission errors
                    $permError = false;
                    $combined = strtolower(($apiError ?? '') . ' ' . $body);
                    if (strpos($combined, 'authorization_requestdenied') !== false
                        || strpos($combined, 'insufficient privileges') !== false
                        || strpos($combined, 'permission') !== false) {
                        $permError = true;
                    }
                    $sanitized = $this->sanitizeAzureError($apiError);
                    $friendly = is_array($sanitized) ? $sanitized['message'] : $sanitized;
                    $msg = 'Profile fetch failed (' . $status . '): ' . $friendly;
                    if ($permError) {
                        $msg .= '<br><small>Missing Graph permissions detected (e.g., User.Read). Please accept the consent prompt or have an admin grant consent.</small>';
                    } else {
                        $msg .= '<br><small>' . $hint . ' Please try again and accept the consent prompt.</small>';
                    }
                    $this->Flash->error($msg, ['escape' => false]);
                    $session->write('Auth.BlockAutoLogin', true);
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
                $currentTime = time();
                
                $session->write('Auth.User', [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $displayName,
                    'role' => $user->role,
                    'microsoft_id' => $user->microsoft_id,
                ]);
                $session->write('Auth.AccessToken', $accessToken);
                
                // Store token expiry (Azure AD tokens typically expire in 1 hour)
                // Set expiry to 55 minutes to be safe (3300 seconds)
                $session->write('Auth.TokenExpiry', $currentTime + 3300);
                
                // Store last activity timestamp for session timeout tracking
                $session->write('Auth.LastActivity', $currentTime);
                
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
        
        try {
            // Use Microsoft Graph Service
            $graphService = new \App\Service\MicrosoftGraphService($accessToken);
            $users = $graphService->getAllUsers();
            
            if (empty($users)) {
                // Check if it's a token expiration issue
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'error' => 'Failed to fetch users from Microsoft Graph. Your access token may have expired. Please log out and log back in.'
                    ]));
            }
            
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'users' => $users,
                    'total' => count($users)
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

    /**
     * Check internet connectivity
     * Tests connection to reliable endpoints
     * 
     * @return bool True if connected, false otherwise
     */
    private function checkInternetConnectivity(): bool
    {
        $http = new Client(['timeout' => 5]);
        
        // Try multiple endpoints for reliability
        $endpoints = [
            'https://login.microsoftonline.com',  // Primary: Microsoft login
            'https://www.google.com',              // Fallback: Google
            'https://www.cloudflare.com'           // Fallback: Cloudflare
        ];
        
        foreach ($endpoints as $endpoint) {
            try {
                $response = $http->head($endpoint);
                
                // If we get any response (even error), we have connectivity
                if ($response) {
                    \Cake\Log\Log::debug('[Connectivity] Connection verified via ' . $endpoint);
                    return true;
                }
            } catch (\Cake\Http\Client\Exception\NetworkException $e) {
                // Network error, try next endpoint
                \Cake\Log\Log::debug('[Connectivity] Failed to connect to ' . $endpoint . ': ' . $e->getMessage());
                continue;
            } catch (\Exception $e) {
                // Other errors also indicate no connectivity
                \Cake\Log\Log::debug('[Connectivity] Error connecting to ' . $endpoint . ': ' . $e->getMessage());
                continue;
            }
        }
        
        // All endpoints failed
        \Cake\Log\Log::error('[Connectivity] No internet connection detected. All endpoints failed.');
        return false;
    }

    /**
     * Sanitize Azure AD / Graph error messages.
     * Extracts AADSTS code and returns structured array with code + message.
     * @param string $raw
     * @return array|string
     */
    private function sanitizeAzureError(string $raw)
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return ['code' => null, 'message' => 'Unknown error'];
        }
        // Extract AADSTS error code pattern e.g. AADSTS50011:
        if (preg_match('/(AADSTS\d+)/', $trimmed, $m)) {
            $code = $m[1];
            // Remove verbose diagnostic info after first sentence.
            $parts = preg_split('/[.]/', $trimmed);
            $first = trim($parts[0]);
            // Remove the code itself from message if duplicated.
            $first = preg_replace('/^(AADSTS\d+:?)/', '', $first);
            $first = trim($first);
            return ['code' => $code, 'message' => $first ?: 'Azure authentication error'];
        }
        // Generic fallback - limit length
        if (strlen($trimmed) > 300) {
            $trimmed = substr($trimmed, 0, 300) . '…';
        }
        return ['code' => null, 'message' => $trimmed];
    }
}
