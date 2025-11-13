<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Test Microsoft Graph');
?>
<style>
    .test-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .test-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    .test-section h3 {
        margin-top: 0;
        color: #0c5343;
    }
    .success {
        color: #28a745;
        background: #d4edda;
        padding: 0.75rem;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    .error {
        color: #dc3545;
        background: #f8d7da;
        padding: 0.75rem;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    .info {
        color: #004085;
        background: #cce5ff;
        padding: 0.75rem;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    code {
        background: #f4f4f4;
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-family: monospace;
    }
    .btn-test {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: #0078D4;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        margin: 0.5rem 0;
    }
    .btn-test:hover {
        background: #106EBE;
    }
</style>

<div class="test-container">
    <h1>🔧 Microsoft Graph Connection Test</h1>
    
    <div class="test-section">
        <h3>1. Configuration Check</h3>
        <?php
        $config = \Cake\Core\Configure::read('Azure');
        if ($config):
        ?>
            <div class="success">✅ Azure configuration loaded successfully</div>
            <p><strong>Client ID:</strong> <?= substr($config['clientId'], 0, 8) ?>...</p>
            <p><strong>Tenant ID:</strong> <?= substr($config['tenantId'], 0, 8) ?>...</p>
            <p><strong>Redirect URI:</strong> <code><?= h($config['redirectUri']) ?></code></p>
            <p><strong>Scopes:</strong> <?= implode(', ', $config['scopes']) ?></p>
        <?php else: ?>
            <div class="error">❌ Azure configuration not loaded</div>
            <p>Check if <code>config/azure.php</code> exists and is loaded in <code>config/bootstrap.php</code></p>
        <?php endif; ?>
    </div>
    
    <div class="test-section">
        <h3>2. Environment Variables</h3>
        <?php if (env('AZURE_CLIENT_ID')): ?>
            <div class="success">✅ Environment variables loaded</div>
            <p><strong>AZURE_CLIENT_ID:</strong> <?= substr(env('AZURE_CLIENT_ID'), 0, 8) ?>...</p>
            <p><strong>AZURE_TENANT_ID:</strong> <?= substr(env('AZURE_TENANT_ID'), 0, 8) ?>...</p>
            <p><strong>AZURE_REDIRECT_URI:</strong> <?= h(env('AZURE_REDIRECT_URI')) ?></p>
        <?php else: ?>
            <div class="error">❌ Environment variables not loaded</div>
            <p>Check if <code>config/.env</code> exists and is enabled in <code>config/bootstrap.php</code></p>
        <?php endif; ?>
    </div>
    
    <div class="test-section">
        <h3>3. Session Status</h3>
        <?php 
        $session = $this->request->getSession();
        $user = $session->read('Auth.User');
        $token = $session->read('Auth.AccessToken');
        ?>
        <?php if ($user): ?>
            <div class="success">✅ User is logged in</div>
            <p><strong>Name:</strong> <?= h($user['name'] ?? 'N/A') ?></p>
            <p><strong>Email:</strong> <?= h($user['email'] ?? 'N/A') ?></p>
            <p><strong>Role:</strong> <?= h($user['role'] ?? 'N/A') ?></p>
            <p><strong>Token:</strong> <?= $token ? '✅ Present (' . substr($token, 0, 20) . '...)' : '❌ Missing' ?></p>
        <?php else: ?>
            <div class="info">ℹ️ No active session - not logged in</div>
        <?php endif; ?>
    </div>
    
    <div class="test-section">
        <h3>4. Test Authentication</h3>
        <p>Click the button below to test Microsoft authentication:</p>
        <?= $this->Html->link('🔑 Test Microsoft Login', ['action' => 'login'], ['class' => 'btn-test']) ?>
        
        <?php if ($user): ?>
            <br>
            <?= $this->Html->link('🚪 Logout', ['action' => 'logout'], ['class' => 'btn-test', 'style' => 'background: #dc3545;']) ?>
        <?php endif; ?>
    </div>
    
    <?php if ($token): ?>
    <div class="test-section">
        <h3>5. Test Microsoft Graph API</h3>
        <button onclick="testGraphUsers()" class="btn-test">📋 Fetch Users from Graph</button>
        <div id="graphResult" style="margin-top: 1rem;"></div>
    </div>
    <?php endif; ?>
    
    <div class="test-section">
        <h3>Quick Links</h3>
        <p>
            <?= $this->Html->link('← Back to Home', ['controller' => 'Pages', 'action' => 'display', 'home'], ['class' => 'btn-test', 'style' => 'background: #6c757d;']) ?>
            <?= $this->Html->link('View Setup Guide', ['controller' => 'Auth', 'action' => 'setup'], ['class' => 'btn-test', 'style' => 'background: #17a2b8;']) ?>
        </p>
    </div>
</div>

<?php if ($token): ?>
<script>
function testGraphUsers() {
    const resultDiv = document.getElementById('graphResult');
    resultDiv.innerHTML = '<p>⏳ Loading users from Microsoft Graph...</p>';
    
    fetch('<?= $this->Url->build(['action' => 'getGraphUsers']) ?>')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resultDiv.innerHTML = `<div class="error">❌ Error: ${data.error}</div>`;
                return;
            }
            
            if (data.users && data.users.length > 0) {
                let html = `<div class="success">✅ Successfully fetched ${data.users.length} users</div>`;
                html += '<table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">';
                html += '<tr style="background: #f4f4f4; text-align: left;"><th style="padding: 0.5rem;">Name</th><th style="padding: 0.5rem;">Email</th><th style="padding: 0.5rem;">Job Title</th></tr>';
                
                data.users.slice(0, 10).forEach(user => {
                    html += `<tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 0.5rem;">${user.name}</td>
                        <td style="padding: 0.5rem;">${user.email}</td>
                        <td style="padding: 0.5rem;">${user.jobTitle || 'N/A'}</td>
                    </tr>`;
                });
                
                html += '</table>';
                if (data.users.length > 10) {
                    html += `<p style="margin-top: 0.5rem; color: #666;">Showing first 10 of ${data.users.length} users</p>`;
                }
                
                resultDiv.innerHTML = html;
            } else {
                resultDiv.innerHTML = '<div class="info">ℹ️ No users found</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `<div class="error">❌ Error: ${error.message}</div>`;
        });
}
</script>
<?php endif; ?>
