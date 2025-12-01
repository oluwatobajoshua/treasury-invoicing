<?php
/** @var \App\View\AppView $this */
use Cake\Core\Configure;

$this->assign('title', $this->fetch('title') ?: 'Admin');
$session = $this->request->getSession();
$auth = (array)($session->read('Auth.User') ?? []);
$appName = Configure::read('AppSettings.app_name', 'Treasury Invoicing System');
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= h($appName) ?>: <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>

    <?= $this->Html->css(['sunbeth-theme', 'admin']) ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #0c5343;
            --primary-dark: #093d31;
            --primary-light: #0f6d56;
            --accent: #ff5722;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-700: #374151;
            --gray-900: #111827;
        }
        
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .admin-container {
            display: flex;
            flex: 1;
        }
        
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid var(--gray-200);
            padding: 1.5rem 0;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }
        
        .sidebar-brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 1rem;
        }
        
        .sidebar-brand h1 {
            margin: 0 0 0.25rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .sidebar-brand .subtitle {
            font-size: 0.75rem;
            color: var(--gray-700);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .nav-section {
            margin-bottom: 1.5rem;
        }
        
        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-700);
            font-weight: 700;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background: var(--gray-50);
            border-left-color: var(--primary);
            color: var(--primary);
        }
        
        .nav-item.active {
            background: rgba(12, 83, 67, 0.08);
            border-left-color: var(--primary);
            color: var(--primary);
            font-weight: 600;
        }
        
        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }
        
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background: white;
            color: var(--gray-900);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .header-inner {
            width: 90%;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }
        
        .user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-name {
            font-weight: 500;
            color: var(--gray-900);
            font-size: 0.875rem;
        }
        
        .btn-logout {
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-logout:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }
        
        .admin-nav {
            display: none;
        }
        
        .wrap {
            width: 90%;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }
        
        footer {
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 1.5rem 2rem;
            text-align: center;
            color: var(--gray-700);
        }
        
        footer p {
            margin: 0;
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h1><?= h($appName) ?></h1>
                <div class="subtitle">Admin Panel</div>
            </div>
            
            <!-- Dashboard -->
            <?= $this->Html->link(
                '<i class="fas fa-chart-line"></i><span>Dashboard</span>',
                ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                ['class' => 'nav-item', 'escape' => false]
            ) ?>
            
            <!-- Master Data -->
            <div class="nav-section">
                <div class="nav-section-title">Master Data</div>
                <?= $this->Html->link(
                    '<i class="fas fa-building"></i><span>Clients</span>',
                    ['prefix' => 'Admin', 'controller' => 'Clients', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-box"></i><span>Products</span>',
                    ['prefix' => 'Admin', 'controller' => 'Products', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-ship"></i><span>Vessels</span>',
                    ['prefix' => 'Admin', 'controller' => 'Vessels', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-university"></i><span>SGC Accounts</span>',
                    ['prefix' => 'Admin', 'controller' => 'SgcAccounts', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-landmark"></i><span>Banks</span>',
                    ['prefix' => 'Admin', 'controller' => 'Banks', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
            </div>
            
            <!-- System Settings -->
            <div class="nav-section">
                <div class="nav-section-title">System Settings</div>
                <?= $this->Html->link(
                    '<i class="fas fa-users"></i><span>Users</span>',
                    ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-user-check"></i><span>Approvers</span>',
                    ['prefix' => 'Admin', 'controller' => 'ApproverSettings', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-cogs"></i><span>App Settings</span>',
                    ['prefix' => 'Admin', 'controller' => 'AppSettings', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
            </div>
            
            <!-- Database Management -->
            <div class="nav-section">
                <div class="nav-section-title">Database</div>
                <?= $this->Html->link(
                    '<i class="fas fa-database"></i><span>Backup & Restore</span>',
                    ['prefix' => 'Admin', 'controller' => 'DatabaseBackup', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-history"></i><span>Audit Logs</span>',
                    ['prefix' => 'Admin', 'controller' => 'AuditLogs', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-file-export"></i><span>Data Export</span>',
                    ['prefix' => 'Admin', 'controller' => 'DataExport', 'action' => 'index'],
                    ['class' => 'nav-item', 'escape' => false]
                ) ?>
            </div>
            
            <!-- Exit -->
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i><span>Exit Admin</span>',
                ['prefix' => false, 'controller' => 'Dashboard', 'action' => 'index'],
                ['class' => 'nav-item', 'escape' => false, 'style' => 'color:var(--accent);margin-top:1rem;border-top:1px solid var(--gray-200);padding-top:1rem;']
            ) ?>
        </aside>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <header>
                <div class="header-inner">
                    <h2 class="page-title"><?= $this->fetch('title') ?></h2>
                    <?php if ($auth): ?>
                    <div class="user">
                        <div class="user-info">
                            <div class="user-avatar">
                                <?= strtoupper(substr($auth['first_name'] ?? $auth['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="user-name"><?= h(trim(($auth['first_name'] ?? '') . ' ' . ($auth['last_name'] ?? '')) ?: $auth['name'] ?? 'User') ?></span>
                            <a href="#" onclick="confirmLogout(event)" class="btn-logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="wrap">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>

            <footer>
                <p>&copy; <?= date('Y') ?> <?= h($appName) ?>. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
        function showSuccess(message, title = 'Success!') {
            Toast.fire({ icon: 'success', title: title, text: message });
        }
        function showError(message, title = 'Error!') {
            Toast.fire({ icon: 'error', title: title, text: message });
        }
        function showWarning(message, title = 'Warning!') {
            Toast.fire({ icon: 'warning', title: title, text: message });
        }
        function showInfo(message, title = 'Info') {
            Toast.fire({ icon: 'info', title: title, text: message });
        }
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Ready to leave?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0c5343',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Yes, logout',
                cancelButtonText: 'Stay',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Logging out...', allowOutsideClick: false, allowEscapeKey: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    window.location.href = '<?= $this->Url->build(['controller' => 'Auth', 'action' => 'logout']) ?>';
                }
            });
        }
        <?php
        $flashMessages = [
            'success' => $session->read('Flash.success'),
            'error' => $session->read('Flash.error'),
            'warning' => $session->read('Flash.warning'),
            'info' => $session->read('Flash.info')
        ];
        foreach ($flashMessages as $type => $messages) {
            if ($messages) {
                foreach ((array)$messages as $message) {
                    $messageText = is_array($message) && isset($message['message']) ? $message['message'] : $message;
                    echo "show" . ucfirst($type) . "(" . json_encode($messageText) . ");\n";
                }
                $session->delete("Flash.$type");
            }
        }
        ?>
    </script>
    <?= $this->fetch('postLink') ?>
</body>
</html>
