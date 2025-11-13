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
        
        // Prevent post-login infinite redirect chains: if the stored redirect equals current page, clear it
        $savedRedirect = $session->read('Auth.redirect');
        if ($savedRedirect && $savedRedirect === $currentUrl) {
            Log::debug('[Auth] Clearing redundant Auth.redirect value: ' . $savedRedirect);
            $session->delete('Auth.redirect');
        }
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
}
