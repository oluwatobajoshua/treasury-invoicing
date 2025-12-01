<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bank $bank
 */
$this->assign('title', 'Bank Details');
?>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(12, 83, 67, 0.15);
}
.page-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.content-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1rem;
}
.content-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}
.section-title {
    font-size: .875rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    padding-bottom: .5rem;
    border-bottom: 2px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
.detail-grid.full {
    grid-template-columns: 1fr;
}
.detail-item {
    display: flex;
    flex-direction: column;
}
.detail-label {
    font-size: .7rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .25rem;
}
.detail-value {
    font-size: .875rem;
    color: #1f2937;
    font-weight: 500;
}
.detail-value strong {
    font-size: .9375rem;
    color: var(--primary);
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .25rem .625rem;
    border-radius: 6px;
    font-size: .75rem;
    font-weight: 600;
    width: fit-content;
}
.badge.success {
    background: #d1fae5;
    color: #065f46;
}
.badge.danger {
    background: #fee2e2;
    color: #991b1b;
}
.badge.warning {
    background: #ffe4e1;
    color: #991b1b;
}
.badge.primary {
    background: #dbeafe;
    color: #1e40af;
}
.action-buttons {
    display: flex;
    gap: .75rem;
    justify-content: flex-end;
}
.btn {
    padding: .5rem 1rem;
    border: none;
    border-radius: 8px;
    font-size: .8125rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .375rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(12, 83, 67, 0.4);
    transform: translateY(-2px);
}
.btn-outline {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}
.btn-outline:hover {
    background: #f9fafb;
    border-color: var(--primary);
    color: var(--primary);
}
.btn-danger {
    background: #ef4444;
    color: white;
}
.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
}
.sidebar-card {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
@media (max-width: 1024px) {
    .content-wrapper {
        grid-template-columns: 1fr;
    }
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>
            <i class="fas fa-university"></i>
            <?= h($bank->bank_name) ?>
        </h2>
        <div class="action-buttons">
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Back',
                ['action' => 'index'],
                ['class' => 'btn btn-outline', 'escapeTitle' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Edit',
                ['action' => 'edit', $bank->id],
                ['class' => 'btn btn-primary', 'escapeTitle' => false]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash"></i>',
                ['action' => 'delete', $bank->id],
                [
                    'confirm' => 'Delete ' . $bank->bank_name . '?',
                    'class' => 'btn btn-danger',
                    'escapeTitle' => false,
                    'title' => 'Delete'
                ]
            ) ?>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <!-- Main Content (Left) -->
    <div>
        <!-- Basic Information -->
        <div class="content-card" style="margin-bottom: 1.5rem;">
            <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
            </h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Account Number</div>
                    <div class="detail-value"><strong><?= h($bank->account_number) ?></strong></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Currency</div>
                    <div class="detail-value"><strong><?= h($bank->currency) ?></strong></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">SWIFT Code</div>
                    <div class="detail-value"><?= h($bank->swift_code) ?: '<em style="color: #9ca3af;">Not provided</em>' ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Bank Type</div>
                    <div class="detail-value">
                        <?php if ($bank->bank_type === 'sales'): ?>
                            <span class="badge warning"><i class="fas fa-cash-register"></i> Sales</span>
                        <?php elseif ($bank->bank_type === 'sustainability'): ?>
                            <span class="badge success"><i class="fas fa-leaf"></i> Sustainability</span>
                        <?php elseif ($bank->bank_type === 'shipment'): ?>
                            <span class="badge primary"><i class="fas fa-ship"></i> Shipment</span>
                        <?php else: ?>
                            <span class="badge success"><i class="fas fa-check-double"></i> All Types</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($bank->bank_address): ?>
            <div class="detail-grid full" style="margin-top: 1rem;">
                <div class="detail-item">
                    <div class="detail-label">Bank Address</div>
                    <div class="detail-value"><?= nl2br(h($bank->bank_address)) ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($bank->purpose): ?>
            <div class="detail-grid full" style="margin-top: 1rem;">
                <div class="detail-item">
                    <div class="detail-label">Purpose</div>
                    <div class="detail-value"><?= nl2br(h($bank->purpose)) ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Correspondent Bank (if exists) -->
        <?php if ($bank->correspondent_bank || $bank->correspondent_swift || $bank->aba_routing): ?>
        <div class="content-card" style="margin-bottom: 1.5rem;">
            <h3 class="section-title">
                <i class="fas fa-building"></i>
                Correspondent Bank
            </h3>
            
            <div class="detail-grid">
                <?php if ($bank->correspondent_bank): ?>
                <div class="detail-item">
                    <div class="detail-label">Bank Name</div>
                    <div class="detail-value"><strong><?= h($bank->correspondent_bank) ?></strong></div>
                </div>
                <?php endif; ?>
                
                <?php if ($bank->correspondent_swift): ?>
                <div class="detail-item">
                    <div class="detail-label">SWIFT Code</div>
                    <div class="detail-value"><strong><?= h($bank->correspondent_swift) ?></strong></div>
                </div>
                <?php endif; ?>
                
                <?php if ($bank->aba_routing): ?>
                <div class="detail-item">
                    <div class="detail-label">ABA Routing</div>
                    <div class="detail-value"><strong><?= h($bank->aba_routing) ?></strong></div>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($bank->correspondent_address): ?>
            <div class="detail-grid full" style="margin-top: 1rem;">
                <div class="detail-item">
                    <div class="detail-label">Address</div>
                    <div class="detail-value"><?= nl2br(h($bank->correspondent_address)) ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Beneficiary Bank (if exists) -->
        <?php if ($bank->beneficiary_bank || $bank->beneficiary_account_no || $bank->beneficiary_swift): ?>
        <div class="content-card">
            <h3 class="section-title">
                <i class="fas fa-user-tie"></i>
                Beneficiary Bank
            </h3>
            
            <div class="detail-grid">
                <?php if ($bank->beneficiary_bank): ?>
                <div class="detail-item">
                    <div class="detail-label">Bank Name</div>
                    <div class="detail-value"><strong><?= h($bank->beneficiary_bank) ?></strong></div>
                </div>
                <?php endif; ?>
                
                <?php if ($bank->beneficiary_name): ?>
                <div class="detail-item">
                    <div class="detail-label">Beneficiary Name</div>
                    <div class="detail-value"><strong><?= h($bank->beneficiary_name) ?></strong></div>
                </div>
                <?php endif; ?>
                
                <?php if ($bank->beneficiary_account_no): ?>
                <div class="detail-item">
                    <div class="detail-label">Account Number</div>
                    <div class="detail-value"><strong><?= h($bank->beneficiary_account_no) ?></strong></div>
                </div>
                <?php endif; ?>
                
                <?php if ($bank->beneficiary_acct_no && $bank->beneficiary_acct_no !== $bank->beneficiary_account_no): ?>
                <div class="detail-item">
                    <div class="detail-label">Alt Account Number</div>
                    <div class="detail-value"><strong><?= h($bank->beneficiary_acct_no) ?></strong></div>
                </div>
                <?php endif; ?>
                
                <?php if ($bank->beneficiary_swift): ?>
                <div class="detail-item">
                    <div class="detail-label">SWIFT Code</div>
                    <div class="detail-value"><strong><?= h($bank->beneficiary_swift) ?></strong></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar (Right) -->
    <div class="sidebar-card">
        <!-- Status Card -->
        <div class="content-card">
            <h3 class="section-title">
                <i class="fas fa-toggle-on"></i>
                Status
            </h3>
            <div style="text-align: center; padding: 1rem 0;">
                <?php if ($bank->is_active): ?>
                    <span class="badge success" style="font-size: 1rem; padding: .75rem 1.5rem;">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                <?php else: ?>
                    <span class="badge danger" style="font-size: 1rem; padding: .75rem 1.5rem;">
                        <i class="fas fa-times-circle"></i> Inactive
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Metadata Card -->
        <div class="content-card">
            <h3 class="section-title">
                <i class="fas fa-clock"></i>
                Record Info
            </h3>
            
            <div class="detail-item" style="margin-bottom: .75rem;">
                <div class="detail-label">Created</div>
                <div class="detail-value" style="font-size: .8125rem;">
                    <?= h($bank->created->format('M j, Y')) ?><br>
                    <small style="color: #9ca3af;"><?= h($bank->created->format('g:i A')) ?></small>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Last Modified</div>
                <div class="detail-value" style="font-size: .8125rem;">
                    <?= h($bank->modified->format('M j, Y')) ?><br>
                    <small style="color: #9ca3af;"><?= h($bank->modified->format('g:i A')) ?></small>
                </div>
            </div>
        </div>

        <!-- Notes Card (if exists) -->
        <?php if ($bank->notes): ?>
        <div class="content-card">
            <h3 class="section-title">
                <i class="fas fa-sticky-note"></i>
                Notes
            </h3>
            <div class="detail-value" style="font-size: .8125rem; line-height: 1.5;">
                <?= nl2br(h($bank->notes)) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
