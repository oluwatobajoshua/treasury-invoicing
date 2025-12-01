<?php
// SweetAlert2 default/info flash element
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showInfo === 'function') {
            showInfo(<?= json_encode((string)($message ?? 'Notice')) ?>, 'Notice');
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'info', title: 'Notice', text: <?= json_encode((string)($message ?? 'Notice')) ?> });
        }
    });
</script><?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="<?= h($class) ?>" onclick="this.classList.add('hidden');"><?= $message ?></div>
