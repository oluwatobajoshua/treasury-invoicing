<?php
// SweetAlert2 warning flash element
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showWarning === 'function') {
            showWarning(<?= json_encode((string)($message ?? 'Warning')) ?>);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Warning', text: <?= json_encode((string)($message ?? 'Warning')) ?> });
        }
    });
</script><?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message warning" onclick="this.classList.add('hidden');"><?= $message ?></div>
