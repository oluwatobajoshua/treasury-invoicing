<?php
/**
 * @var \App\View\AppView $this
 * @var array $context { type: 'fresh'|'final', id: int, token: string, invoice: Entity }
 */
$this->assign('title', 'Confirm Rejection');
$ctx = $context ?? [];
$type = $ctx['type'] ?? 'fresh';
$id = (int)($ctx['id'] ?? 0);
$token = (string)($ctx['token'] ?? '');
$invoice = $ctx['invoice'] ?? null;

$backController = $type === 'final' ? 'FinalInvoices' : 'FreshInvoices';
$actionUrl = [
    'controller' => 'Approvals',
    'action' => $type,
    $id,
    'reject',
    '?' => ['t' => $token]
];
?>

<div class="card" style="max-width:700px;margin:1.5rem auto">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-times-circle" style="color:#dc2626"></i> Confirm <?= h(ucfirst($type)) ?> Invoice Rejection</div>
    </div>
    <div class="card-body">
        <p style="margin-bottom:1rem;color:#374151">
            You're about to reject <?= h($type) ?> invoice
            <?php if ($invoice && isset($invoice->invoice_number)): ?>
                <strong>#<?= h($invoice->invoice_number) ?></strong>
            <?php endif; ?>.
            Please provide a brief reason for this decision.
        </p>

        <?= $this->Form->create(null, ['url' => $actionUrl]) ?>
        <div class="mb-3">
            <label for="treasurer_comments" class="form-label" style="font-weight:600">Rejection reason (required)</label>
            <?= $this->Form->textarea('treasurer_comments', [
                'id' => 'treasurer_comments',
                'rows' => 5,
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Enter the reason for rejection...'
            ]) ?>
        </div>
        <div style="display:flex;gap:.5rem">
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Cancel',
                ['controller' => $backController, 'action' => 'view', $id],
                ['class' => 'btn btn-outline', 'escape' => false]
            ) ?>
            <?= $this->Form->button('<i class="fas fa-times"></i> Submit Rejection', [
                'class' => 'btn btn-danger',
                'escapeTitle' => false
            ]) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
