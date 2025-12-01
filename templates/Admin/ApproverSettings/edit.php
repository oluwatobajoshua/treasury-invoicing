<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\ApproverSetting $approverSetting */
$this->assign('title', 'Edit Notification Rule');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.form-header {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

.form-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.context-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-right: 0.5rem;
}

.context-badge.fresh {
    background: rgba(102, 126, 234, 0.15);
    color: #5a67d8;
}

.context-badge.final {
    background: rgba(245, 87, 108, 0.15);
    color: #f5576c;
}

.context-badge.pending {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
}

.context-badge.approved {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

.context-badge.rejected {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.workflow-preview {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.workflow-preview h4 {
    margin: 0 0 1rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
}

.workflow-path {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.workflow-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: white;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}

.workflow-step.active {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1e40af;
}

.workflow-arrow {
    color: #9ca3af;
    font-size: 1.25rem;
}

.email-hint {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 1rem;
    border-radius: 4px;
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

.email-hint i {
    color: #f59e0b;
}

.danger-zone {
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.danger-zone h4 {
    color: #dc2626;
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.danger-zone p {
    color: #991b1b;
    margin: 0 0 1rem 0;
    font-size: 0.875rem;
}
</style>

<div class="form-header fade-in">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.5rem;"><i class="fas fa-edit"></i> Edit Notification Rule</h2>
            <p class="muted" style="margin:0.5rem 0 0 0;">
                <span class="context-badge <?= h($approverSetting->invoice_type) ?>">
                    <i class="fas fa-file-invoice"></i> <?= h(ucfirst($approverSetting->invoice_type)) ?> Invoice
                </span>
                <span class="context-badge <?= h($approverSetting->stage) ?>">
                    <i class="fas fa-<?= $approverSetting->stage === 'pending_approval' ? 'clock' : ($approverSetting->stage === 'approved' ? 'check-circle' : 'times-circle') ?>"></i> <?= h(ucwords(str_replace('_', ' ', $approverSetting->stage))) ?>
                </span>
            </p>
        </div>
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to List', ['action' => 'index'], [
            'class' => 'btn',
            'style' => 'background:#6b7280;color:white;',
            'escape' => false
        ]) ?>
    </div>
</div>

<div class="form-card fade-in">
    <!-- Current Workflow Context -->
    <div class="workflow-preview">
        <h4><i class="fas fa-route"></i> Current Workflow Context</h4>
        <div class="workflow-path">
            <?php if ($approverSetting->invoice_type === 'fresh'): ?>
                <div class="workflow-step"><i class="fas fa-user"></i> User Creates Invoice</div>
                <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                
                <?php if ($approverSetting->stage === 'pending_approval'): ?>
                    <div class="workflow-step active"><i class="fas fa-paper-plane"></i> Treasurer (Current Rule)</div>
                <?php else: ?>
                    <div class="workflow-step"><i class="fas fa-check"></i> Treasurer Approves</div>
                <?php endif; ?>
                
                <?php if ($approverSetting->stage === 'approved'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-truck"></i> Export Team (Current Rule)</div>
                <?php elseif ($approverSetting->stage === 'rejected'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-times-circle"></i> Rejection Notice (Current Rule)</div>
                <?php endif; ?>
            <?php elseif ($approverSetting->invoice_type === 'final'): ?>
                <div class="workflow-step"><i class="fas fa-user"></i> User Creates Final Invoice</div>
                <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                
                <?php if ($approverSetting->stage === 'pending_approval'): ?>
                    <div class="workflow-step active"><i class="fas fa-paper-plane"></i> Treasurer (Current Rule)</div>
                <?php else: ?>
                    <div class="workflow-step"><i class="fas fa-check"></i> Treasurer Approves</div>
                <?php endif; ?>
                
                <?php if ($approverSetting->stage === 'approved'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-handshake"></i> Sales Team (Current Rule)</div>
                <?php elseif ($approverSetting->stage === 'rejected'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-times-circle"></i> Rejection Notice (Current Rule)</div>
                <?php endif; ?>
            <?php elseif ($approverSetting->invoice_type === 'sales'): ?>
                <div class="workflow-step"><i class="fas fa-user"></i> User Creates Sales Invoice</div>
                <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                <?php if ($approverSetting->stage === 'sent'): ?>
                    <div class="workflow-step active"><i class="fas fa-paper-plane"></i> Sent (Current Rule)</div>
                <?php else: ?>
                    <div class="workflow-step"><i class="fas fa-paper-plane"></i> Sent</div>
                <?php endif; ?>
            <?php elseif ($approverSetting->invoice_type === 'sustainability'): ?>
                <div class="workflow-step"><i class="fas fa-leaf"></i> User Creates Sustainability Invoice</div>
                <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                <?php if ($approverSetting->stage === 'sent'): ?>
                    <div class="workflow-step active"><i class="fas fa-paper-plane"></i> Sent (Current Rule)</div>
                <?php else: ?>
                    <div class="workflow-step"><i class="fas fa-paper-plane"></i> Sent</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?= $this->Form->create($approverSetting) ?>
    
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
        <div>
            <?= $this->Form->control('role', [
                'options' => $roles,
                'label' => '<i class="fas fa-user-tag"></i> Role / Team',
                'escape' => false,
                'class' => 'form-control',
                'disabled' => true,
                'style' => 'padding:0.75rem;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem;background:#f9fafb;'
            ]) ?>
            <small style="color:#6b7280;"><i class="fas fa-lock"></i> Cannot be changed to maintain workflow consistency</small>
        </div>
        
        <div>
            <?= $this->Form->control('invoice_type', [
                'options' => $invoiceTypes,
                'label' => '<i class="fas fa-file-invoice"></i> Invoice Type',
                'escape' => false,
                'class' => 'form-control',
                'disabled' => true,
                'style' => 'padding:0.75rem;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem;background:#f9fafb;'
            ]) ?>
            <small style="color:#6b7280;"><i class="fas fa-lock"></i> Cannot be changed to maintain workflow consistency</small>
        </div>
        
        <div>
            <?= $this->Form->control('stage', [
                'options' => $stages,
                'label' => '<i class="fas fa-stream"></i> Workflow Stage',
                'escape' => false,
                'class' => 'form-control',
                'disabled' => true,
                'style' => 'padding:0.75rem;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem;background:#f9fafb;'
            ]) ?>
            <small style="color:#6b7280;"><i class="fas fa-lock"></i> Cannot be changed to maintain workflow consistency</small>
        </div>
    </div>

    <div style="margin-bottom:1.5rem;">
        <?= $this->Form->control('to_emails', [
            'type' => 'textarea',
            'label' => '<i class="fas fa-envelope"></i> TO Recipients (Primary)',
            'escape' => false,
            'placeholder' => 'email1@example.com, email2@example.com, email3@example.com',
            'rows' => 3,
            'id' => 'to-emails',
            'style' => 'padding:0.75rem;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem;font-family:monospace;'
        ]) ?>
        <div style="margin-top:0.5rem;display:flex;gap:0.5rem;align-items:center;">
            <button type="button" class="btn btn-sm" id="add-to-btn" style="background:#3b82f6;color:white;padding:0.5rem 1rem;font-size:0.875rem;">
                <i class="fas fa-user-plus"></i> Add from Azure AD
            </button>
            <button type="button" class="btn btn-sm" id="clear-to-btn" style="background:#6b7280;color:white;padding:0.5rem 1rem;font-size:0.875rem;">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
        <div class="email-hint">
            <i class="fas fa-lightbulb"></i> <strong>Tip:</strong> Click "Add from Azure AD" to select users, or manually enter email addresses separated by commas.
        </div>
    </div>

    <div style="margin-bottom:1.5rem;">
        <?= $this->Form->control('cc_emails', [
            'type' => 'textarea',
            'label' => '<i class="fas fa-copy"></i> CC Recipients (Copy)',
            'escape' => false,
            'placeholder' => 'cc1@example.com, cc2@example.com',
            'rows' => 2,
            'id' => 'cc-emails',
            'style' => 'padding:0.75rem;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem;font-family:monospace;'
        ]) ?>
        <div style="margin-top:0.5rem;display:flex;gap:0.5rem;align-items:center;">
            <button type="button" class="btn btn-sm" id="add-cc-btn" style="background:#3b82f6;color:white;padding:0.5rem 1rem;font-size:0.875rem;">
                <i class="fas fa-user-plus"></i> Add from Azure AD
            </button>
            <button type="button" class="btn btn-sm" id="clear-cc-btn" style="background:#6b7280;color:white;padding:0.5rem 1rem;font-size:0.875rem;">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
        <div class="email-hint">
            <i class="fas fa-lightbulb"></i> <strong>Tip:</strong> Click "Add from Azure AD" to select users, or manually enter email addresses separated by commas.
        </div>
    </div>

    <div style="margin-bottom:1.5rem;">
        <?= $this->Form->control('is_active', [
            'type' => 'checkbox',
            'label' => ' This notification rule is active',
            'style' => 'width:18px;height:18px;margin-right:0.5rem;'
        ]) ?>
    </div>

    <div style="display:flex;gap:1rem;margin-top:2rem;padding-top:1.5rem;border-top:2px solid #f3f4f6;">
        <button type="submit" class="btn btn-primary" style="padding:0.875rem 1.75rem;font-size:1rem;font-weight:600;">
            <i class="fas fa-save"></i> Save Changes
        </button>
        <?= $this->Html->link('Cancel', ['action' => 'index'], [
            'class' => 'btn',
            'style' => 'background:#6b7280;color:white;padding:0.875rem 1.75rem;'
        ]) ?>
    </div>

    <?= $this->Form->end() ?>

    <!-- Danger Zone -->
    <div class="danger-zone">
        <h4><i class="fas fa-exclamation-triangle"></i> Danger Zone</h4>
        <p>Deleting this notification rule will remove it permanently. The workflow stage will have no email notifications unless another rule is created.</p>
        <?= $this->Form->postLink(
            '<i class="fas fa-trash-alt"></i> Delete This Notification Rule',
            ['action' => 'delete', $approverSetting->id],
            [
                'confirm' => 'Are you sure you want to delete this notification rule? This action cannot be undone.',
                'class' => 'btn',
                'style' => 'background:#dc2626;color:white;padding:0.75rem 1.5rem;',
                'escape' => false
            ]
        ) ?>
    </div>
</div>

<?= $this->element('graph_user_picker') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('add-to-btn').addEventListener('click', function(){
    openGraphUserPicker({
      multiple: true,
      title: 'Select TO Recipients',
      onConfirm: (emails)=>{
        const ta = document.getElementById('to-emails');
        const current = (ta.value||'').trim();
        ta.value = current ? current + ', ' + emails.join(', ') : emails.join(', ');
      }
    });
  });
  document.getElementById('add-cc-btn').addEventListener('click', function(){
    openGraphUserPicker({
      multiple: true,
      title: 'Select CC Recipients',
      onConfirm: (emails)=>{
        const ta = document.getElementById('cc-emails');
        const current = (ta.value||'').trim();
        ta.value = current ? current + ', ' + emails.join(', ') : emails.join(', ');
      }
    });
  });
  document.getElementById('clear-to-btn').addEventListener('click', function(){ document.getElementById('to-emails').value=''; });
  document.getElementById('clear-cc-btn').addEventListener('click', function(){ document.getElementById('cc-emails').value=''; });
});
</script>
