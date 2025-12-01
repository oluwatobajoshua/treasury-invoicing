<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Login');
?>
<style>
    body {
        background: linear-gradient(135deg, #0c5343 0%, #0a4636 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }
    .login-container {
        background: white;
        border-radius: 12px;
        padding: 3rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        max-width: 450px;
        width: 90%;
        text-align: center;
    }
    .login-logo {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    .login-title {
        color: var(--primary);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .login-subtitle {
        color: var(--muted);
        margin-bottom: 2rem;
        font-size: 1rem;
    }
    .microsoft-login-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        background: #0078D4;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .microsoft-login-btn:hover {
        background: #106EBE;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 120, 212, 0.4);
    }
    .microsoft-icon {
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .features {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--gray-200);
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        text-align: left;
        color: var(--text);
    }
    .feature-icon {
        font-size: 1.5rem;
    }
</style>

<div class="login-container fade-in">
    <div class="login-logo">💰</div>
    <h1 class="login-title">Treasury Invoicing System</h1>
    <p class="login-subtitle">Sign in with your Microsoft account to continue</p>
    
    <?= $this->Flash->render() ?>
    
    <div id="connectivity-status" style="display:none;margin-bottom:1rem;padding:0.75rem;border-radius:8px;font-size:0.9rem;">
        <i class="fas fa-wifi"></i> <span id="connectivity-message"></span>
    </div>
    
    <?= $this->Html->link(
        '<div class="microsoft-icon">
            <svg width="18" height="18" viewBox="0 0 23 23">
                <rect x="1" y="1" width="10" height="10" fill="#f25022"/>
                <rect x="12" y="1" width="10" height="10" fill="#7fba00"/>
                <rect x="1" y="12" width="10" height="10" fill="#00a4ef"/>
                <rect x="12" y="12" width="10" height="10" fill="#ffb900"/>
            </svg>
        </div>
        <span>Sign in with Microsoft</span>',
        ['action' => 'login'],
        [
            'class' => 'microsoft-login-btn',
            'escape' => false,
            'id' => 'login-btn'
        ]
    ) ?>
    
    <div class="features">
        <div class="feature-item">
            <span class="feature-icon">🔒</span>
            <span>Secure single sign-on</span>
        </div>
        <div class="feature-item">
            <span class="feature-icon">⚡</span>
            <span>Fast and seamless authentication</span>
        </div>
        <div class="feature-item">
            <span class="feature-icon">🎯</span>
            <span>No additional passwords needed</span>
        </div>
    </div>
</div>

<script>
// Check internet connectivity before allowing login
let isOnline = navigator.onLine;

function updateConnectivityStatus(online) {
    const statusDiv = document.getElementById('connectivity-status');
    const messageSpan = document.getElementById('connectivity-message');
    const loginBtn = document.getElementById('login-btn');
    
    if (!online) {
        statusDiv.style.display = 'block';
        statusDiv.style.background = '#FEE2E2';
        statusDiv.style.color = '#991B1B';
        statusDiv.style.border = '2px solid #FCA5A5';
        messageSpan.innerHTML = '<strong>No Internet Connection</strong><br>You are not connected to the internet. Please check your network connection.';
        
        // Disable login button
        loginBtn.style.opacity = '0.5';
        loginBtn.style.cursor = 'not-allowed';
        loginBtn.style.pointerEvents = 'none';
    } else {
        statusDiv.style.display = 'block';
        statusDiv.style.background = '#D1FAE5';
        statusDiv.style.color = '#065F46';
        statusDiv.style.border = '2px solid #6EE7B7';
        messageSpan.innerHTML = '<strong>Connected</strong> - Ready to sign in';
        
        // Enable login button
        loginBtn.style.opacity = '1';
        loginBtn.style.cursor = 'pointer';
        loginBtn.style.pointerEvents = 'auto';
        
        // Hide status after 3 seconds if online
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 3000);
    }
}

// Check connectivity on page load
updateConnectivityStatus(isOnline);

// Listen for connectivity changes
window.addEventListener('online', function() {
    isOnline = true;
    updateConnectivityStatus(true);
    console.log('[Connectivity] Internet connection restored');
});

window.addEventListener('offline', function() {
    isOnline = false;
    updateConnectivityStatus(false);
    console.log('[Connectivity] Internet connection lost');
});

// Additional check before login click
document.getElementById('login-btn').addEventListener('click', function(e) {
    if (!navigator.onLine) {
        e.preventDefault();
        alert('⚠️ No Internet Connection\n\nYou are not connected to the internet.\n\nPlease check your network connection and try again.');
        return false;
    }
});

// Periodic connectivity check (every 5 seconds)
setInterval(function() {
    const currentStatus = navigator.onLine;
    if (currentStatus !== isOnline) {
        isOnline = currentStatus;
        updateConnectivityStatus(isOnline);
    }
}, 5000);
</script>
