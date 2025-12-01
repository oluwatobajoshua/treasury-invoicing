<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Welcome - Treasury Invoicing System');
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Treasury Invoicing System</title>
    <style>
    :root {
        --primary: #0c5343;
        --accent: #f64500;
        --text: #1a1a1a;
        --muted: #6c757d;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text);
        overflow-x: hidden;
    }

    /* Hero Section */
    .hero {
        min-height: 60vh;
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 50%, #083d2f 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        animation: gridMove 20s linear infinite;
    }

    @keyframes gridMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(100px, 100px); }
    }

    .hero-content {
        max-width: 1200px;
        text-align: center;
        position: relative;
        z-index: 2;
        animation: fadeInUp 1s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    .hero h1 {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .hero p {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1.25rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: var(--primary);
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .cta-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
        background: var(--gray-50);
    }

    /* Features Section */
    .features {
        padding: 1.5rem 2rem;
        background: white;
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }

    .feature-card {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 2px solid var(--gray-200);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 40px rgba(12, 83, 67, 0.15);
        border-color: var(--primary);
    }

    .feature-icon {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .feature-card h3 {
        font-size: 1rem;
        color: var(--primary);
        margin-bottom: 0.35rem;
        font-weight: 700;
    }

    .feature-card p {
        color: var(--muted);
        line-height: 1.35;
        font-size: 0.85rem;
    }

    /* How It Works Section */
    .how-it-works {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
    }

    .steps {
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .step {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .step:nth-child(5) {
        grid-column: 1 / -1;
        max-width: 600px;
        margin: 0 auto;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .step-content h3 {
        font-size: 1rem;
        color: var(--primary);
        margin-bottom: 0.35rem;
        font-weight: 700;
    }

    .step-content p {
        color: var(--muted);
        line-height: 1.35;
        font-size: 0.85rem;
    }

    /* Stats Section */
    .stats {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
    }

    .stats-grid {
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        background: linear-gradient(to right, white, rgba(255, 255, 255, 0.7));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    /* CTA Section */
    .cta-section {
        padding: 1.5rem 2rem;
        background: white;
        text-align: center;
    }

    .cta-section h2 {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
        font-weight: 800;
    }

    .cta-section p {
        font-size: 1rem;
        color: var(--muted);
        margin-bottom: 1.25rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Footer */
    .footer {
        background: var(--primary);
        color: white;
        padding: 1.25rem 2rem;
        text-align: center;
    }

    .footer p {
        opacity: 0.9;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 0.75rem;
        font-size: 0.85rem;
    }

    .footer-links a {
        color: white;
        text-decoration: none;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .footer-links a:hover {
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            min-height: 70vh;
            padding: 1rem;
        }
        
        .hero h1 {
            font-size: 1.75rem;
        }
        
        .hero p {
            font-size: 0.95rem;
            margin-bottom: 1.25rem;
        }
        
        .hero-icon {
            font-size: 2.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .features {
            padding: 2rem 1.5rem;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .how-it-works {
            padding: 2rem 1.5rem;
        }
        
        .steps {
            grid-template-columns: 1fr;
        }
        
        .step {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .step:nth-child(5) {
            max-width: 100%;
        }
        
        .stats {
            padding: 2rem 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        
        .cta-section {
            padding: 2rem 1.5rem;
        }
        
        .cta-button {
            padding: 0.75rem 1.75rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .hero h1 {
            font-size: 1.5rem;
        }
        
        .hero p {
            font-size: 0.9rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .stat-number {
            font-size: 1.75rem;
        }
    }
    </style>

</head>
<body>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-icon">📊</div>
        <h1>Treasury Invoicing System</h1>
        <p>Streamline your cocoa export invoicing with automated calculations, multi-level approvals, and real-time tracking from fresh to final invoices.</p>
        <?php 
        $session = $this->request->getSession(); 
        $loggedIn = (bool)$session->read('Auth.User');
        $user = $session->read('Auth.User');
        $isAdmin = $loggedIn && isset($user['role']) && $user['role'] === 'admin';
        ?>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?= $loggedIn 
                ? $this->Url->build(['controller' => 'FreshInvoices', 'action' => 'index']) 
                : $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>" class="cta-button">
                <svg width="20" height="20" viewBox="0 0 21 21" fill="currentColor">
                    <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                    <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                    <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                    <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
                </svg>
                Sign in with Microsoft
            </a>
            
            <?php if ($isAdmin): ?>
                <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index', 'prefix' => 'Admin']) ?>" 
                   class="cta-button" 
                   style="background:linear-gradient(135deg,#ff5722 0%,#f4511e 100%);color:white;">
                    <i class="fas fa-cog"></i>
                    Admin Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="features-container">
        <h2 class="section-title">🌟 Powerful Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">🔐</span>
                <h3>Microsoft Integration</h3>
                <p>Secure single sign-on with Microsoft Azure AD. Access your organization's directory and streamline authentication.</p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">💰</span>
                <h3>Automated Calculations</h3>
                <p>Automatic calculation of invoice totals, quantity variance, and payment percentages. No manual calculations needed.</p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">⚡</span>
                <h3>Fast Approvals</h3>
                <p>Multi-level approval workflow with treasurer review. Fresh and final invoices can be approved and sent to departments instantly.</p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">📊</span>
                <h3>Real-time Tracking</h3>
                <p>Monitor your invoices status in real-time. Track from draft to approved, and see when invoices are sent to Export or Sales.</p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <h3>Mobile Responsive</h3>
                <p>Access the system from any device. Create and approve invoices on the go with our mobile-friendly interface.</p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🗂️</span>
                <h3>Contract Management</h3>
                <p>Link invoices to contracts with vessel information. Track quantities, unit prices, and maintain full audit trails.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="features-container">
        <h2 class="section-title">� How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Sign In with Microsoft</h3>
                    <p>Use your organization's Microsoft account to securely access the system. Single sign-on makes it quick and easy.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Create Fresh Invoice</h3>
                    <p>Select contract, vessel, and enter quantity details. The system automatically calculates total value based on payment percentage.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Treasurer Reviews</h3>
                    <p>Treasurer receives notification and can approve or reject the fresh invoice with comments and feedback.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Send to Export Department</h3>
                    <p>After approval, send the fresh invoice to Export department for processing and final preparations.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <h3>Create & Approve Final Invoice</h3>
                    <p>Generate final invoice from fresh invoice with actual quantities. Track variance and send to Sales after treasurer approval!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="stats-grid">
        <div>
            <div class="stat-number">100%</div>
            <div class="stat-label">Automated Calculations</div>
        </div>
        <div>
            <div class="stat-number">3min</div>
            <div class="stat-label">Average Invoice Creation</div>
        </div>
        <div>
            <div class="stat-number">24/7</div>
            <div class="stat-label">System Availability</div>
        </div>
        <div>
            <div class="stat-number">∞</div>
            <div class="stat-label">Export Possibilities</div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2>Ready to Get Started?</h2>
    <p>Sign in with your Microsoft account and create your first invoice in minutes.</p>
    <?php $session = $this->request->getSession(); $loggedIn = (bool)$session->read('Auth.User'); ?>
    <a href="<?= $loggedIn 
        ? $this->Url->build(['controller' => 'FreshInvoices', 'action' => 'index']) 
        : $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>" class="cta-button">
        <svg width="20" height="20" viewBox="0 0 21 21" fill="currentColor">
            <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
            <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
            <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
            <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
        </svg>
        Sign in with Microsoft
    </a>
</section>

<!-- Footer -->
<footer class="footer">
    <p>&copy; <?= date('Y') ?> Treasury Invoicing System. All rights reserved.</p>
    <p>Powered by Microsoft Azure AD & CakePHP<?= $loggedIn ? ' · <a style="color:#fff;text-decoration:underline;" href="' . $this->Url->build(['controller' => 'FreshInvoices', 'action' => 'index']) . '">Go to Dashboard</a>' : '' ?></p>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Support</a>
    </div>
</footer>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert2 Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    <?php
    // Display flash messages
    $session = $this->request->getSession();
    $flashMessages = [
        'success' => $session->read('Flash.success'),
        'error' => $session->read('Flash.error'),
        'warning' => $session->read('Flash.warning'),
        'info' => $session->read('Flash.info')
    ];
    
    foreach ($flashMessages as $type => $messages) {
        if ($messages) {
            foreach ((array)$messages as $message) {
                if (is_array($message) && isset($message['message'])) {
                    $messageText = $message['message'];
                } else {
                    $messageText = $message;
                }
                echo "Toast.fire({ icon: '$type', title: " . json_encode($messageText) . " });\n";
            }
            $session->delete("Flash.$type");
        }
    }
    ?>
</script>

</body>
</html>


