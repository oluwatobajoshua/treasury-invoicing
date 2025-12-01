<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Log\Log;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authorization');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * Before filter callback
     *
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $request = $this->request;
        $session = $request->getSession();
        $user = $session->read('Auth.User');

        // Normalise current target (no query string for loop detection)
        $currentUrl = strtok($request->getRequestTarget(), '?') ?: '/';
        $controller = (string)$request->getParam('controller');
        $action = (string)$request->getParam('action');
        $pass = (array)$request->getParam('pass');

        // Public (unauthenticated) endpoints allowlist
        $isPublic = false;
        if ($controller === 'Auth') {
            // All auth actions are public
            $isPublic = true;
        } elseif ($controller === 'Approvals') {
            // Approval links from email are public (protected by signed token)
            $isPublic = true;
        } elseif ($controller === 'Pages' && $action === 'display' && !empty($pass) && $pass[0] === 'home') {
            // Landing page (when Router populated pass)
            $isPublic = true;
        } elseif ($currentUrl === '/') {
            // Explicitly allow root path, which maps to home in routes
            $isPublic = true;
        }

        // Trace routing & auth state for debugging
        Log::debug(sprintf('[Route] %s::%s %s public=%s auth=%s savedRedirect=%s',
            $controller,
            $action,
            $currentUrl,
            $isPublic ? 'yes' : 'no',
            $user ? 'yes' : 'no',
            var_export($session->read('Auth.redirect'), true)
        ));

        // Allow asset/ajax OPTIONS preflight requests silently
        if (in_array($request->getMethod(), ['OPTIONS'], true)) {
            return;
        }

        // If request is public just expose authUser (may be null) and continue
        if ($isPublic) {
            if ($user) {
                $this->set('authUser', $user);
            }
            return;
        }

        // Unauthenticated access to protected page => store redirect & go to login
        if (!$user) {
            // Avoid storing login/callback pages or duplicate value
            $existing = $session->read('Auth.redirect');
            if (!$existing || $existing === '/login' || $existing === '/auth/callback') {
                $session->delete('Auth.redirect');
            }
            if ($currentUrl !== '/login' && $currentUrl !== '/auth/callback') {
                // Don't save ajax or json endpoints to prevent odd loops after login
                if (!$request->is('ajax')) {
                    $session->write('Auth.redirect', $currentUrl);
                }
            }

            // Simple loop guard: if we somehow are already at login, don't redirect again
            if ($currentUrl === '/login') {
                return;
            }

            Log::debug('[Auth] Redirecting unauthenticated user from ' . $currentUrl . ' to /login');
            $this->Flash->error('Please login with your Microsoft account to access this page.');
            return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
        }

        // Authenticated user - expose user globally
        $this->set('authUser', $user);
        
        // Check for session timeout and token expiration
        $this->checkSessionTimeout($session);
        
        // Prevent post-login infinite redirect chains: if the stored redirect equals current page, clear it
        $savedRedirect = $session->read('Auth.redirect');
        if ($savedRedirect && $savedRedirect === $currentUrl) {
            Log::debug('[Auth] Clearing redundant Auth.redirect value: ' . $savedRedirect);
            $session->delete('Auth.redirect');
        }
    }

    /**
     * Authorization hook - check if authenticated user can access current action.
     * Uses RBAC system to enforce role-based permissions.
     *
     * @param array|null $user User data from session
     * @return bool True if authorized, false otherwise
     */
    public function isAuthorized($user): bool
    {
        // If no user (shouldn't happen as beforeFilter protects), deny
        if (!$user) {
            return false;
        }

        // Get controller and action from request
        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');

        // Auth controller is always allowed (login/logout/callback)
        if ($controller === 'Auth') {
            return true;
        }

        // Pages controller is public
        if ($controller === 'Pages') {
            return true;
        }

        // Check RBAC permissions
        $authorized = \App\Security\Rbac::can($user, $controller, $action);

        // Log authorization check for debugging
        if (!$authorized) {
            Log::warning(sprintf(
                '[RBAC] Access denied for user %s (role: %s) to %s::%s',
                $user['email'] ?? 'unknown',
                $user['role'] ?? 'none',
                $controller,
                $action
            ));
        }

        return $authorized;
    }

    /**
     * Override redirect to log every redirect target for loop diagnosis.
     *
     * @param array|string|null $url Target URL/array
     * @param int $status HTTP status (default 302)
     * @return \Cake\Http\Response|null
     */
    public function redirect($url, int $status = 302): ?\Cake\Http\Response
    {
        $target = is_array($url) ? Router::url($url) : (string)$url;
        Log::debug(sprintf('[Redirect] %s => %s (status=%d)', $this->request->getRequestTarget(), $target, $status));
        return parent::redirect($url, $status);
    }

    /**
     * Check session timeout and token expiration
     * 
     * @param \Cake\Http\Session $session Session instance
     * @return \Cake\Http\Response|null
     */
    protected function checkSessionTimeout($session)
    {
        $lastActivity = $session->read('Auth.LastActivity');
        $accessToken = $session->read('Auth.AccessToken');
        $tokenExpiry = $session->read('Auth.TokenExpiry');
        
        // Session timeout: 2 hours of inactivity
        $sessionTimeout = 7200; // 2 hours in seconds
        
        if ($lastActivity) {
            $inactiveTime = time() - $lastActivity;
            
            if ($inactiveTime > $sessionTimeout) {
                // Session has timed out due to inactivity
                Log::info('Session timeout: User inactive for ' . round($inactiveTime / 60) . ' minutes');
                $session->delete('Auth');
                $this->Flash->warning('Your session has expired due to inactivity. Please login again.');
                return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
            }
        }
        
        // Check if access token has expired (tokens typically last 1 hour)
        if ($tokenExpiry && time() >= $tokenExpiry) {
            Log::info('Access token expired. User needs to re-authenticate.');
            $session->delete('Auth');
            $this->Flash->warning('Your access token has expired. Please login again to continue.');
            return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
        }
        
        // Update last activity timestamp
        $session->write('Auth.LastActivity', time());
        
        return null;
    }
}
