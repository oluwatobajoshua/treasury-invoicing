<?php
/**
 * System Health Check & Debugging Page
 * Navigate to: http://localhost:8765/travel-requests/debug-check
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Health Check</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 2rem;
            background: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h1 { color: #0c5343; }
        h2 { color: #0a4636; border-bottom: 2px solid #0c5343; padding-bottom: 0.5rem; }
        .status { padding: 1rem; margin: 1rem 0; border-radius: 6px; }
        .status.good { background: #d4edda; border-left: 4px solid #28a745; }
        .status.warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        .status.error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .check-item { padding: 0.75rem; margin: 0.5rem 0; background: #f8f9fa; border-radius: 4px; }
        .check-item .label { font-weight: 600; color: #6c757d; }
        .check-item .value { color: #212529; }
        .badge { padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600; }
        .badge.success { background: #28a745; color: white; }
        .badge.danger { background: #dc3545; color: white; }
        .badge.info { background: #17a2b8; color: white; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto; }
        .method-list { list-style: none; padding: 0; }
        .method-list li { padding: 0.5rem; margin: 0.25rem 0; background: #e7f3ff; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 System Health Check & Debug</h1>
        <p>System Status: <strong><?= date('F d, Y - H:i:s') ?></strong></p>
        
        <h2>✅ Azure Configuration</h2>
        <?php
        $azureConfig = \Cake\Core\Configure::read('Azure');
        $hasClientId = !empty($azureConfig['clientId']);
        $hasClientSecret = !empty($azureConfig['clientSecret']);
        $hasTenantId = !empty($azureConfig['tenantId']);
        ?>
        <div class="status <?= ($hasClientId && $hasClientSecret && $hasTenantId) ? 'good' : 'error' ?>">
            <div class="check-item">
                <span class="label">Client ID:</span>
                <span class="value"><?= $hasClientId ? '✅ Configured' : '❌ Missing' ?></span>
            </div>
            <div class="check-item">
                <span class="label">Client Secret:</span>
                <span class="value"><?= $hasClientSecret ? '✅ Configured' : '❌ Missing' ?></span>
            </div>
            <div class="check-item">
                <span class="label">Tenant ID:</span>
                <span class="value"><?= $hasTenantId ? '✅ Configured' : '❌ Missing' ?></span>
            </div>
            <div class="check-item">
                <span class="label">Graph API Endpoint:</span>
                <span class="value"><?= h($azureConfig['graphApiEndpoint'] ?? 'Not configured') ?></span>
            </div>
        </div>
        
        <h2>🔐 Session Status</h2>
        <?php
        $session = $this->request->getSession();
        $isAuthenticated = $session->check('Auth.User');
        $hasAccessToken = $session->check('Auth.AccessToken');
        ?>
        <div class="status <?= $isAuthenticated ? 'good' : 'warning' ?>">
            <div class="check-item">
                <span class="label">Authentication:</span>
                <span class="value">
                    <?php if ($isAuthenticated): ?>
                        <span class="badge success">Logged In</span> as <?= h($session->read('Auth.User.name')) ?>
                    <?php else: ?>
                        <span class="badge danger">Not Logged In</span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">Access Token:</span>
                <span class="value">
                    <?= $hasAccessToken ? '<span class="badge success">Present</span>' : '<span class="badge danger">Missing</span>' ?>
                </span>
            </div>
            <?php if ($hasAccessToken): ?>
            <div class="check-item">
                <span class="label">Token Preview:</span>
                <span class="value" style="font-family: monospace; font-size: 0.85rem;">
                    <?= h(substr($session->read('Auth.AccessToken'), 0, 50)) ?>...
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <h2>📊 API Permissions Required</h2>
        <div class="status info">
            <p><strong>Required Microsoft Graph API Permissions:</strong></p>
            <ul class="method-list">
                <li>✅ User.Read - Read user profile</li>
                <li>✅ User.ReadBasic.All - Read all users' basic profiles</li>
                <li>🔔 Chat.Create - Create chats</li>
                <li>🔔 Chat.ReadWrite - Read and write chats</li>
                <li>🔔 ChatMessage.Send - Send chat messages</li>
                <li>📧 Mail.Send - Send emails</li>
            </ul>
            <p><small>🔔 = Required for Teams notifications | 📧 = Required for email fallback</small></p>
        </div>
        
        <h2>🔔 Notification Methods Available</h2>
        <div class="status good">
            <p><strong>TravelRequestsController Notification Methods:</strong></p>
            <ul class="method-list">
                <li>📤 _sendApprovalNotification() - Main orchestrator</li>
                <li>💬 _sendTeamsApprovalCard() - Teams adaptive card</li>
                <li>📧 _sendEmailApprovalNotification() - Email fallback</li>
                <li>✅ _notifyRequesterApproval() - Notify employee of approval</li>
                <li>❌ _notifyRequesterRejection() - Notify employee of rejection</li>
                <li>💬 _sendTeamsRequesterApproval() - Teams card for approval</li>
                <li>💬 _sendTeamsRequesterRejection() - Teams card for rejection</li>
                <li>📧 _sendEmailRequesterApproval() - Email for approval</li>
                <li>📧 _sendEmailRequesterRejection() - Email for rejection</li>
            </ul>
        </div>
        
        <h2>📁 File System</h2>
        <?php
        $uploadDir = WWW_ROOT . 'files' . DS . 'travel_requests';
        $uploadDirExists = is_dir($uploadDir);
        $uploadDirWritable = $uploadDirExists && is_writable($uploadDir);
        ?>
        <div class="status <?= $uploadDirWritable ? 'good' : 'warning' ?>">
            <div class="check-item">
                <span class="label">Upload Directory:</span>
                <span class="value"><?= $uploadDirExists ? '✅ Exists' : '❌ Missing' ?></span>
            </div>
            <div class="check-item">
                <span class="label">Writable:</span>
                <span class="value"><?= $uploadDirWritable ? '✅ Yes' : '❌ No' ?></span>
            </div>
            <div class="check-item">
                <span class="label">Path:</span>
                <span class="value" style="font-family: monospace; font-size: 0.85rem;"><?= h($uploadDir) ?></span>
            </div>
        </div>
        
        <h2>🗄️ Database Connection</h2>
        <?php
        try {
            $connection = \Cake\Datasource\ConnectionManager::get('default');
            $connected = $connection->connect();
            $dbName = $connection->config()['database'];
        } catch (\Exception $e) {
            $connected = false;
            $dbError = $e->getMessage();
        }
        ?>
        <div class="status <?= $connected ? 'good' : 'error' ?>">
            <div class="check-item">
                <span class="label">Connection:</span>
                <span class="value">
                    <?= $connected ? '<span class="badge success">Connected</span>' : '<span class="badge danger">Failed</span>' ?>
                </span>
            </div>
            <?php if ($connected): ?>
            <div class="check-item">
                <span class="label">Database:</span>
                <span class="value"><?= h($dbName) ?></span>
            </div>
            <?php else: ?>
            <div class="check-item">
                <span class="label">Error:</span>
                <span class="value" style="color: #dc3545;"><?= h($dbError) ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <h2>🧪 Test Actions</h2>
        <div style="display: flex; gap: 1rem; margin: 1rem 0;">
            <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn" style="display: inline-block; padding: 0.75rem 1.5rem; background: #0c5343; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                ➕ Create Test Request
            </a>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn" style="display: inline-block; padding: 0.75rem 1.5rem; background: #17a2b8; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                📋 View All Requests
            </a>
            <?php if (!$isAuthenticated): ?>
            <a href="<?= $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>" class="btn" style="display: inline-block; padding: 0.75rem 1.5rem; background: #ffc107; color: #212529; text-decoration: none; border-radius: 6px; font-weight: 600;">
                🔐 Login with Microsoft
            </a>
            <?php endif; ?>
        </div>
        
        <h2>📝 Quick Tips</h2>
        <div class="status warning">
            <ul>
                <li><strong>Before Testing:</strong> Ensure you're logged in with Microsoft account</li>
                <li><strong>Azure Permissions:</strong> Go to Azure Portal and grant admin consent for new permissions</li>
                <li><strong>Teams Testing:</strong> Both sender and receiver need Microsoft Teams access</li>
                <li><strong>Email Fallback:</strong> Configure SMTP in config/app_local.php if Teams fails</li>
                <li><strong>Logs:</strong> Check logs/error.log and logs/debug.log for detailed information</li>
            </ul>
        </div>
        
        <hr style="margin: 2rem 0;">
        <p style="text-align: center; color: #6c757d;">
            <small>System ready for testing! Start by logging in and creating a travel request. ✨</small>
        </p>
    </div>
</body>
</html>
