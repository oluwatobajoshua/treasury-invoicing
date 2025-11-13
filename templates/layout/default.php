<?php
/**
 * Treasury Invoicing System - Modern Layout
 * Professional UI/UX with responsive design
 */

$cakeDescription = 'Treasury Invoicing System';
$authUser = $this->request->getAttribute('authUser') ?? $this->request->getSession()->read('Auth.User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= $this->fetch('title') ?> | <?= $cakeDescription ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Sunbeth Brand Styles -->
    <?= $this->Html->css('sunbeth-brand') ?>
    
    <style>
        :root {
            --primary: #0c5343;
            --primary-dark: #083d2f;
            --primary-light: #0f6b54;
            --secondary: #ff5722;
            --sunbeth-orange: #ff5722;
            --success: #10b981;
            --warning: #ff5722;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1e293b;
            --light: #f1f5f9;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            color: var(--gray-900);
            line-height: 1.6;
            font-size: 15px;
            min-height: 100vh;
        }
        
        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(12, 83, 67, 0.25);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            text-decoration: none;
            color: white;
            transition: transform 0.3s ease;
        }
        
        .brand:hover {
            transform: translateY(-2px);
        }
        
        .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .brand:hover .brand-icon {
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(-5deg) scale(1.05);
        }
        
        .brand-text h1 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .brand-subtitle {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 400;
            letter-spacing: 0.5px;
        }
        
        .header-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-links {
            display: flex;
            gap: 0.5rem;
            list-style: none;
        }
        
        .nav-link {
            padding: 0.625rem 1.125rem;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .user-info:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--sunbeth-orange), #ff7043);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            box-shadow: 0 2px 8px rgba(255, 87, 34, 0.3);
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            line-height: 1.2;
        }
        
        .user-role {
            font-size: 0.75rem;
            opacity: 0.85;
            text-transform: capitalize;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
            margin-top: 0.125rem;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2.5rem 2rem;
            min-height: calc(100vh - 144px);
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
            animation: fadeInDown 0.5s ease;
        }
        
        .page-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-subtitle {
            color: var(--gray-600);
            font-size: 1.05rem;
        }
        
        .breadcrumb {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            font-size: 0.9rem;
            color: var(--gray-600);
            margin-top: 0.75rem;
        }
        
        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to right, var(--gray-50), white);
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            padding: 1rem 1.5rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn > * {
            position: relative;
            z-index: 1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(12, 83, 67, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(12, 83, 67, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--sunbeth-orange) 0%, #e64a19 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 87, 34, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--sunbeth-orange) 0%, #e64a19 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid currentColor;
            color: inherit;
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.05rem;
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Footer */
        .footer {
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 2rem 0;
            margin-top: 4rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            text-align: center;
            color: var(--gray-600);
            font-size: 0.9rem;
        }
        
        .footer-links {
            margin-top: 1rem;
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .footer-link {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 968px) {
            .header-container {
                padding: 0 1rem;
            }
            
            .user-details {
                display: none;
            }
            
            .nav-links {
                gap: 0.25rem;
            }
            
            .nav-link {
                padding: 0.5rem 0.875rem;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                height: auto;
                flex-wrap: wrap;
                padding: 1rem;
                gap: 1rem;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .main-content {
                padding: 1.5rem 1rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .card {
                border-radius: 12px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="<?= $authUser ? $this->Url->build(['controller' => 'FreshInvoices', 'action' => 'index']) : $this->Url->build('/') ?>" class="brand">
                <div class="brand-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="brand-text">
                    <h1>Treasury Invoicing</h1>
                    <span class="brand-subtitle">Invoice Management System</span>
                </div>
            </a>
            
            <?php if ($authUser): ?>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="header-nav">
                <ul class="nav-links" id="navLinks">
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'Contracts', 'action' => 'index']) ?>" 
                           class="nav-link <?= ($this->request->getParam('controller') === 'Contracts') ? 'active' : '' ?>">
                            <i class="fas fa-file-contract"></i>
                            <span>Contracts</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'FreshInvoices', 'action' => 'index']) ?>" 
                           class="nav-link <?= ($this->request->getParam('controller') === 'FreshInvoices') ? 'active' : '' ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Fresh Invoices</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'FinalInvoices', 'action' => 'index']) ?>" 
                           class="nav-link <?= ($this->request->getParam('controller') === 'FinalInvoices') ? 'active' : '' ?>">
                            <i class="fas fa-file-invoice"></i>
                            <span>Final Invoices</span>
                        </a>
                    </li>
                </ul>
                
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?= h($authUser['name'] ?? 'User') ?></span>
                            <span class="user-role"><?= h($authUser['role'] ?? 'user') ?></span>
                        </div>
                    </div>
                    <button onclick="confirmLogout(event)" class="btn btn-sm btn-outline" style="color: white; border-color: rgba(255,255,255,0.3);">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="logout-text">Logout</span>
                    </button>
                </div>
            </nav>
            <?php else: ?>
            <div class="user-menu">
                <a href="<?= $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>" class="btn btn-sm btn-outline" style="color: white; border-color: rgba(255,255,255,0.3);">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p><strong>Treasury Invoicing System</strong> &copy; <?= date('Y') ?>. All rights reserved.</p>
            <div class="footer-links">
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Terms of Service</a>
                <a href="#" class="footer-link">Support</a>
            </div>
            <p style="margin-top: 1rem; font-size: 0.85rem; opacity: 0.8;">
                Powered by CakePHP &nbsp;|&nbsp; Built with <i class="fas fa-heart" style="color: #ef4444;"></i> for efficient invoice management
            </p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Global Scripts -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }
        
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
            customClass: {
                popup: 'colored-toast'
            }
        });
        
        // Success notification
        function showSuccess(message, title = 'Success!') {
            Toast.fire({
                icon: 'success',
                title: title,
                text: message
            });
        }
        
        // Error notification
        function showError(message, title = 'Error!') {
            Toast.fire({
                icon: 'error',
                title: title,
                text: message
            });
        }
        
        // Warning notification
        function showWarning(message, title = 'Warning!') {
            Toast.fire({
                icon: 'warning',
                title: title,
                text: message
            });
        }
        
        // Info notification
        function showInfo(message, title = 'Info') {
            Toast.fire({
                icon: 'info',
                title: title,
                text: message
            });
        }
        
        // Confirmation dialog
        function confirmAction(title, text, confirmText = 'Yes, proceed!') {
            return Swal.fire({
                title: title,
                html: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0c5343',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn'
                }
            });
        }
        
        // Delete confirmation
        function confirmDelete(itemName = 'this item') {
            return Swal.fire({
                title: 'Are you sure?',
                html: `You are about to delete <strong>${itemName}</strong>.<br>This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
        }
        
        // Loading dialog
        function showLoading(title = 'Please wait...', text = 'Processing your request') {
            Swal.fire({
                title: title,
                text: text,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        // Close loading
        function hideLoading() {
            Swal.close();
        }
        
        // Logout confirmation
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Ready to leave?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0c5343',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Yes, logout',
                cancelButtonText: 'Stay',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Logging out...', 'Please wait');
                    window.location.href = '<?= $this->Url->build(['controller' => 'Auth', 'action' => 'logout']) ?>';
                }
            });
        }
        
        // Display flash messages from PHP
        <?php
        $session = $this->request->getSession();
        $flashTypes = ['success', 'error', 'warning', 'info'];
        
        foreach ($flashTypes as $type) {
            $messages = $session->read("Flash.$type");
            if ($messages) {
                foreach ((array)$messages as $message) {
                    $messageText = is_array($message) && isset($message['message']) 
                        ? $message['message'] 
                        : $message;
                    
                    $functionName = 'show' . ucfirst($type);
                    echo "$functionName(" . json_encode($messageText) . ");\n";
                }
                $session->delete("Flash.$type");
            }
        }
        ?>
    </script>
    
    <?= $this->fetch('script') ?>
    <?= $this->fetch('postLink') ?>
</body>
</html>
