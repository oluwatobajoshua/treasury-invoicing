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
    <div class="login-logo">✈️</div>
    <h1 class="login-title">Travel Request System</h1>
    <p class="login-subtitle">Sign in with your Microsoft account to continue</p>
    
    <?= $this->Flash->render() ?>
    
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
            'escape' => false
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
