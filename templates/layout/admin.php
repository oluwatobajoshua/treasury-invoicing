<?php
/** @var \App\View\AppView $this */
$this->assign('title', $this->fetch('title') ?: 'Admin');
$session = $this->request->getSession();
$auth = (array)($session->read('Auth.User') ?? []);
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Travel Request System: <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>

    <?= $this->Html->css(['sunbeth-theme', 'admin']) ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
</head>
<body>
    <header>
        <div class="header-inner">
            <div class="brand">
                <div class="brand-icon">✈️</div>
                <div class="brand-text">
                    <h1 class="h1">Travel Request</h1>
                    <span class="brand-subtitle">Admin Panel</span>
                </div>
            </div>
            <?php if ($auth): ?>
            <div class="user">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= substr($auth['name'] ?? 'U', 0, 1) ?>
                    </div>
                    <span class="user-name"><?= h($auth['name'] ?? 'User') ?></span>
                    <a href="#" onclick="confirmLogout(event)" class="btn btn-sm btn-outline" style="margin-left: 1rem;">🚪 Logout</a>
                </div>
            </div>
            <?php else: ?>
            <div class="user">
                <?= $this->Html->link('🔐 Sign In', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'btn btn-sm btn-primary']) ?>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="wrap">
        <div class="container">
            <div class="controls" style="margin-top:16px;">
                <?= $this->Html->link('Dashboard', ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'], ['class' => 'btn ghost btn-sm']) ?>
                <?= $this->Html->link('Users', ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index'], ['class' => 'btn ghost btn-sm']) ?>
                <?= $this->Html->link('Requests', ['prefix' => 'Admin', 'controller' => 'TravelRequests', 'action' => 'index'], ['class' => 'btn ghost btn-sm']) ?>
                <?= $this->Html->link('Job Levels', ['prefix' => 'Admin', 'controller' => 'JobLevels', 'action' => 'index'], ['class' => 'btn ghost btn-sm']) ?>
                <?= $this->Html->link('Allowance Rates', ['prefix' => 'Admin', 'controller' => 'AllowanceRates', 'action' => 'index'], ['class' => 'btn ghost btn-sm']) ?>
                <span class="spacer"></span>
                <?= $this->Html->link('Exit Admin', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 'home'], ['class' => 'btn btn-outline btn-sm']) ?>
            </div>

            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
        <footer>
            <p>&copy; <?= date('Y') ?> Travel Request System. All rights reserved.</p>
        </footer>
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
                confirmButtonText: '🚪 Yes, logout',
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
