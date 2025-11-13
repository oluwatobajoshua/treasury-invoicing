<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\FinalInvoice> $finalInvoices
 * @var array $kpis
 */
$this->assign('title', 'Final Invoices');
?>

<style>
.page-header {
    background: linear-gradient(135deg, #0c5343 0%, #0a4636 100%);
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(12, 83, 67, 0.15);
}
.page-header h2 {
    margin: 0 0 .5rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.page-header p {
    margin: 0;
    font-size: .875rem;
    color: rgba(255, 255, 255, 0.9);
}
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.kpi-card {
    background: white;
    border-radius: 10px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 4px solid;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}
.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    opacity: 0.05;
    border-radius: 50%;
    background: currentColor;
    transform: translate(30%, -30%);
}
.kpi-card.total { border-color: #ff5722; color: #ff5722; }
.kpi-card.draft { border-color: #9e9e9e; color: #9e9e9e; }
.kpi-card.pending { border-color: #fbc02d; color: #fbc02d; }
.kpi-card.approved { border-color: #2e7d32; color: #2e7d32; }
.kpi-card.rejected { border-color: #c62828; color: #c62828; }
.kpi-card.sent { border-color: #0288d1; color: #0288d1; }
.kpi-card.variance { border-color: #6a1b9a; color: #6a1b9a; }
.kpi-card.value { border-color: #455a64; color: #455a64; }
.kpi-label {
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #666;
    margin-bottom: .5rem;
}
.kpi-value {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1;
    position: relative;
    z-index: 1;
}
.kpi-icon {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 1.5rem;
    opacity: 0.2;
}
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}
.search-box {
    max-width: 420px;
    width: 100%;
    position: relative;
}
.search-box input {
    width: 100%;
    padding: .75rem 1rem .75rem 2.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: .9rem;
    transition: all 0.3s ease;
}
.search-box input:focus {
    outline: none;
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
}
.search-box::before {
    content: '🔍';
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.1rem;
}
.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}
.table-responsive {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
thead {
    background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
}
th {
    padding: 1rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #555;
    border-bottom: 2px solid #ddd;
}
td {
    padding: 1rem;
    font-size: .875rem;
    border-bottom: 1px solid #f0f0f0;
}
tbody tr {
    transition: background 0.2s ease;
}
tbody tr:hover {
    background: #fafafa;
}
.empty-state {
    padding: 3rem 2rem;
    text-align: center;
    color: #666;
}
.empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}
.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: .5rem;
}
.empty-state p {
    font-size: .875rem;
    color: #888;
    margin-bottom: 1.5rem;
}
.btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .75rem 1.5rem;
    font-size: .9rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-primary {
    background: linear-gradient(135deg, #ff5722 0%, #f4511e 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 87, 34, 0.4);
}
.paginator {
    margin-top: 1.5rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}
.pagination {
    display: flex;
    justify-content: center;
    gap: .5rem;
    margin-bottom: .75rem;
    list-style: none;
}
.pagination a {
    padding: .5rem .75rem;
    border-radius: 6px;
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
    font-size: .875rem;
    transition: all 0.2s ease;
}
.pagination a:hover {
    background: #ff5722;
    color: white;
}
.paginator p {
    text-align: center;
    font-size: .8rem;
    color: #666;
    margin: 0;
}
@media (max-width: 768px) {
    .kpi-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    }
    .page-header {
        padding: 1rem 1.5rem;
    }
    .toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .search-box {
        max-width: 100%;
    }
}
</style>

<div class="fade-in">
    <div class="page-header">
        <h2>📄 Final Invoices</h2>
        <p>Manage final invoices with landed quantities (FVP prefix) and track variance from fresh invoices</p>
    </div>

    <?php if (isset($kpis)): ?>
    <div class="kpi-grid">
        <div class="kpi-card total">
            <div class="kpi-icon">📊</div>
            <div class="kpi-label">Total Finals</div>
            <div class="kpi-value"><?= (int)($kpis['total'] ?? 0) ?></div>
        </div>
        <div class="kpi-card pending">
            <div class="kpi-icon">⏳</div>
            <div class="kpi-label">Pending</div>
            <div class="kpi-value"><?= (int)($kpis['pending'] ?? 0) ?></div>
        </div>
        <div class="kpi-card approved">
            <div class="kpi-icon">✅</div>
            <div class="kpi-label">Approved</div>
            <div class="kpi-value"><?= (int)($kpis['approved'] ?? 0) ?></div>
        </div>
        <div class="kpi-card rejected">
            <div class="kpi-icon">❌</div>
            <div class="kpi-label">Rejected</div>
            <div class="kpi-value"><?= (int)($kpis['rejected'] ?? 0) ?></div>
        </div>
        <div class="kpi-card sent">
            <div class="kpi-icon">🚀</div>
            <div class="kpi-label">Sent to Sales</div>
            <div class="kpi-value"><?= (int)($kpis['sent_to_sales'] ?? 0) ?></div>
        </div>
        <div class="kpi-card variance">
            <div class="kpi-icon">📈</div>
            <div class="kpi-label">Variance (MT)</div>
            <div class="kpi-value"><?= number_format((float)($kpis['variance_sum'] ?? 0), 2) ?></div>
        </div>
        <div class="kpi-card value">
            <div class="kpi-icon">💰</div>
            <div class="kpi-label">Total Value</div>
            <div class="kpi-value">$<?= number_format((float)($kpis['total_value_sum'] ?? 0), 0) ?></div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="toolbar">
        <div class="search-box">
            <input id="table-filter" type="search" placeholder="Search invoices, vessels, BL numbers...">
        </div>
        <?= $this->Html->link('✨ New Final Invoice', ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="table-card">
        <?php if (!empty($finalInvoices->count())): ?>
        <div class="table-responsive">
            <table id="final-invoices-table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('invoice_number', 'FVP Number') ?></th>
                        <th><?= $this->Paginator->sort('original_invoice_number', 'Original Invoice') ?></th>
                        <th><?= $this->Paginator->sort('vessel_name', 'Vessel') ?></th>
                        <th><?= $this->Paginator->sort('bl_number', 'BL Number') ?></th>
                        <th><?= $this->Paginator->sort('landed_quantity', 'Landed Qty') ?></th>
                        <th><?= $this->Paginator->sort('quantity_variance', 'Variance') ?></th>
                        <th><?= $this->Paginator->sort('total_value', 'Total Value') ?></th>
                        <th><?= $this->Paginator->sort('status') ?></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finalInvoices as $finalInvoice): ?>
                    <tr>
                        <td><strong style="color:#ff5722"><?= h($finalInvoice->invoice_number) ?></strong></td>
                        <td><?= h($finalInvoice->has('fresh_invoice') ? $finalInvoice->fresh_invoice->invoice_number : $finalInvoice->original_invoice_number) ?></td>
                        <td><?= h($finalInvoice->has('fresh_invoice') && $finalInvoice->fresh_invoice->has('vessel') ? $finalInvoice->fresh_invoice->vessel->name : $finalInvoice->vessel_name) ?></td>
                        <td><code style="background:#f5f5f5;padding:.25rem .5rem;border-radius:4px;font-size:.8rem"><?= h($finalInvoice->has('fresh_invoice') ? $finalInvoice->fresh_invoice->bl_number : $finalInvoice->bl_number) ?></code></td>
                        <td><strong><?= $this->Number->format($finalInvoice->landed_quantity, ['places' => 3]) ?></strong> MT</td>
                        <td style="color:<?= $finalInvoice->quantity_variance >= 0 ? '#2e7d32' : '#c62828' ?>;font-weight:700">
                            <?= $finalInvoice->quantity_variance >= 0 ? '+' : '' ?><?= $this->Number->format($finalInvoice->quantity_variance, ['places' => 3]) ?>
                        </td>
                        <td><strong><?= $this->Number->currency($finalInvoice->total_value, 'USD') ?></strong></td>
                        <td>
                            <span class="badge badge-<?= $finalInvoice->status === 'approved' ? 'success' : ($finalInvoice->status === 'rejected' ? 'danger' : ($finalInvoice->status === 'sent_to_sales' ? 'info' : 'warning')) ?>" style="padding:.35rem .75rem;border-radius:12px;font-size:.7rem;font-weight:600;display:inline-block">
                                <?= h(ucfirst(str_replace('_', ' ', $finalInvoice->status))) ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:.35rem">
                                <?= $this->Html->link('View', ['action' => 'view', $finalInvoice->id], ['class' => 'btn-action', 'style' => 'color:#0288d1;font-size:.8rem;font-weight:600;text-decoration:none']) ?>
                                <?php if ($finalInvoice->status === 'draft'): ?>
                                    <?= $this->Html->link('Edit', ['action' => 'edit', $finalInvoice->id], ['class' => 'btn-action', 'style' => 'color:#f57c00;font-size:.8rem;font-weight:600;text-decoration:none']) ?>
                                    <?= $this->Form->postLink('Delete', ['action' => 'delete', $finalInvoice->id], ['confirm' => __('Delete invoice {0}?', $finalInvoice->invoice_number), 'class' => 'btn-action', 'style' => 'color:#c62828;font-size:.8rem;font-weight:600;text-decoration:none']) ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📄</div>
                <h3>No Final Invoices Yet</h3>
                <p>Create your first final invoice from an approved Fresh Invoice with landed quantities.</p>
                <?= $this->Html->link('✨ Create Final Invoice', ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>

<script>
// Simple client-side filter for quick searching
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('table-filter');
    const table = document.getElementById('final-invoices-table');
    if (!input || !table) return;
    input.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(tr => {
            const text = tr.textContent.toLowerCase();
            tr.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
