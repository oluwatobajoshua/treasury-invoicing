<?php
// SweetAlert2 success flash element
// Available vars: $message
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showSuccess === 'function') {
            showSuccess(<?= json_encode((string)($message ?? 'Success')) ?>);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'Success', text: <?= json_encode((string)($message ?? 'Success')) ?> });
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
<div class="message success" onclick="this.classList.add('hidden')"><?= $message ?></div>
