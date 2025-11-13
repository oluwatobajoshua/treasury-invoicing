<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Test Notifications');
?>

<style>
    .test-container {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .alert { padding: 1rem; margin: 1rem 0; border-radius: 6px; }
    .alert-info { background: #d1ecf1; border-left: 4px solid #0c5ea6; }
    .alert-warning { background: #fff3cd; border-left: 4px solid #ffc107; }
    .alert-danger { background: #f8d7da; border-left: 4px solid #dc3545; }
    .alert-success { background: #d4edda; border-left: 4px solid #28a745; }
    .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-primary { background: #0c5343; color: white; }
    .btn-success { background: #28a745; color: white; }
    .btn-danger { background: #dc3545; color: white; }
    .code-block { background: #f8f9fa; padding: 1rem; border-radius: 4px; font-family: monospace; margin: 1rem 0; }
    .step { background: #f8f9fa; padding: 1rem; margin: 1rem 0; border-radius: 6px; border-left: 4px solid #0c5343; }
    .step h4 { margin: 0 0 0.5rem 0; color: #0c5343; }
</style>

<div class="test-container">
    <h1>🧪 Notification Testing Guide</h1>
    
    <div class="alert alert-info">
        <strong>📋 Current Status:</strong><br>
        Based on the logs, we found two issues preventing notifications:
        <ul>
            <li><strong>Teams:</strong> Missing Chat permissions (requires Azure AD admin consent)</li>
            <li><strong>Email:</strong> SMTP not configured (fallback failed)</li>
        </ul>
    </div>
    
    <h2>🔧 Quick Fixes</h2>
    
    <div class="step">
        <h4>Option 1: Grant Azure AD Permissions (Recommended)</h4>
        <ol>
            <li>Go to <a href="https://portal.azure.com" target="_blank">Azure Portal</a></li>
            <li>Navigate to: <strong>App Registrations → Your App → API Permissions</strong></li>
            <li>Click <strong>"Add a permission"</strong></li>
            <li>Select <strong>"Microsoft Graph"</strong></li>
            <li>Choose <strong>"Delegated permissions"</strong></li>
            <li>Add these permissions:
                <ul>
                    <li>✅ Chat.Create</li>
                    <li>✅ Chat.ReadWrite</li>
                    <li>✅ ChatMessage.Send</li>
                </ul>
            </li>
            <li>Click <strong>"Grant admin consent for [Your Org]"</strong></li>
            <li>Wait 5 minutes for changes to propagate</li>
            <li><strong>IMPORTANT:</strong> Logout and login again to get new token with permissions</li>
        </ol>
    </div>
    
    <div class="step">
        <h4>Option 2: Configure Email SMTP (Fallback)</h4>
        <p>Add these to your <code>.env</code> file:</p>
        <div class="code-block">
# Gmail SMTP (recommended for testing)<br>
export EMAIL_USERNAME="your-email@gmail.com"<br>
export EMAIL_PASSWORD="your-app-password"<br>
<br>
# Note: For Gmail, create an "App Password" at:<br>
# https://myaccount.google.com/apppasswords
        </div>
        <p><small>⚠️ <strong>Important:</strong> Use App Password, not your regular Gmail password!</small></p>
    </div>
    
    <div class="step">
        <h4>Option 3: Test Without Notifications (Development Only)</h4>
        <p>You can temporarily disable notifications to test the core functionality:</p>
        <ol>
            <li>Travel request still saves successfully</li>
            <li>Manager can manually navigate to approval page</li>
            <li>Approval/Rejection flows still work</li>
        </ol>
    </div>
    
    <hr style="margin: 2rem 0;">
    
    <h2>🧪 After Fixing - Test Again</h2>
    
    <div class="alert alert-success">
        <strong>Testing Steps:</strong>
        <ol>
            <li><strong>Logout:</strong> <?= $this->Html->link('Click here to logout', ['controller' => 'Auth', 'action' => 'logout']) ?></li>
            <li><strong>Login:</strong> Login again to get new access token with permissions</li>
            <li><strong>Create Request:</strong> <?= $this->Html->link('Submit a new travel request', ['controller' => 'TravelRequests', 'action' => 'add']) ?></li>
            <li><strong>Check Logs:</strong> View logs/error.log for notification status</li>
            <li><strong>Verify:</strong> Check Teams or Email for notification</li>
        </ol>
    </div>
    
    <h2>📊 Current Configuration</h2>
    
    <?php
    $session = $this->request->getSession();
    $hasToken = $session->check('Auth.AccessToken');
    $config = \Cake\Core\Configure::read('Azure');
    ?>
    
    <div class="alert alert-info">
        <strong>Azure Scopes Requested:</strong>
        <div class="code-block">
            <?php
            if (isset($config['scopes'])) {
                foreach ($config['scopes'] as $scope) {
                    $icon = (in_array($scope, ['Chat.Create', 'Chat.ReadWrite', 'ChatMessage.Send'])) ? '🔔' : '✅';
                    echo $icon . ' ' . h($scope) . '<br>';
                }
            }
            ?>
        </div>
        <small>🔔 = Required for Teams notifications</small>
    </div>
    
    <div class="alert <?= $hasToken ? 'alert-success' : 'alert-warning' ?>">
        <strong>Access Token Status:</strong> <?= $hasToken ? '✅ Present' : '❌ Missing - Please login' ?>
        <?php if ($hasToken): ?>
        <br><small>Token expires and needs refresh. If notifications fail, try logging out and in again.</small>
        <?php endif; ?>
    </div>
    
    <h2>🔍 Debugging Tips</h2>
    
    <div class="alert alert-warning">
        <strong>Check Logs in Real-Time:</strong>
        <div class="code-block">
# Windows PowerShell:<br>
Get-Content logs\error.log -Tail 20 -Wait<br>
<br>
# Look for these messages:<br>
- "Teams approval notification sent successfully"<br>
- "Email approval notification sent successfully"<br>
- "Failed to send Teams notification" (shows reason)<br>
- "Failed to send email notification" (shows reason)
        </div>
    </div>
    
    <h2>🎯 Manual Testing (Without Notifications)</h2>
    
    <p>If you want to test the approval workflow without notifications:</p>
    
    <ol>
        <li>Create a travel request</li>
        <li>Note the request number from success message</li>
        <li>Navigate to: <code>/travel-requests/index</code></li>
        <li>Find your request and click "View"</li>
        <li>Manually go to approval URL: <code>/travel-requests/approve/{id}</code></li>
        <li>Test the approval/rejection flow</li>
    </ol>
    
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e9ecef;">
        <h3>Quick Actions:</h3>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <?= $this->Html->link('🏠 Go to Dashboard', ['controller' => 'TravelRequests', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('➕ Create Travel Request', ['controller' => 'TravelRequests', 'action' => 'add'], ['class' => 'btn btn-success']) ?>
            <?= $this->Html->link('🔧 System Check', ['controller' => 'TravelRequests', 'action' => 'debugCheck'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('🚪 Logout & Re-Login', ['controller' => 'Auth', 'action' => 'logout'], ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
</div>
