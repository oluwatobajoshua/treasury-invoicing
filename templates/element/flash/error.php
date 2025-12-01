<?php
// SweetAlert2 error flash element
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showError === 'function') {
            showError(<?= json_encode((string)($message ?? 'An error occurred')) ?>);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Error', text: <?= json_encode((string)($message ?? 'An error occurred')) ?> });
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
<div class="message error" onclick="this.classList.add('hidden');"><?= $message ?></div>
