<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Contract> $contracts
 */
$this->assign('title', 'Contract Management');

// Calculate KPI statistics
$totalContracts = 0;
$totalValue = 0;
$activeCount = 0;
$expiringCount = 0;
$totalUtilization = 0;

foreach ($contracts as $contract) {
    $totalContracts++;
    $totalValue += $contract->total_value ?? 0;
    
    if ($contract->quantity > 0) {
        $used = $contract->quantity - ($contract->remaining_quantity ?? $contract->quantity);
        $utilization = ($used / $contract->quantity) * 100;
        $totalUtilization += $utilization;
    }
    
    $endDate = $contract->end_date ? new \DateTime($contract->end_date->format('Y-m-d')) : null;
    $today = new \DateTime();
    $thirtyDaysFromNow = (new \DateTime())->modify('+30 days');
    
    if ($contract->status === 'active' && $endDate && $endDate >= $today) {
        $activeCount++;
        if ($endDate <= $thirtyDaysFromNow) {
            $expiringCount++;
        }
    }
}

$avgUtilization = $totalContracts > 0 ? $totalUtilization / $totalContracts : 0;
?>

<style>
.kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;margin-bottom:2rem;animation:fadeIn .6s}
.kpi-card{background:#fff;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid rgba(0,0,0,.05);transition:all .3s;position:relative;overflow:hidden}
.kpi-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--primary),var(--primary-light))}
.kpi-card.success::before{background:linear-gradient(90deg,var(--success),#34d399)}
.kpi-card.warning::before{background:linear-gradient(90deg,var(--warning),#fbbf24)}
.kpi-card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(0,0,0,.12)}
.kpi-content{display:flex;justify-content:space-between;align-items:flex-start}
.kpi-info h4{font-size:.875rem;color:var(--gray-600);font-weight:500;margin-bottom:.5rem;text-transform:uppercase;letter-spacing:.5px}
.kpi-value{font-size:2rem;font-weight:700;color:var(--gray-900);line-height:1}
.kpi-icon{width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem}
.kpi-card .kpi-icon{background:rgba(12,83,67,.1);color:var(--primary)}
.kpi-card.success .kpi-icon{background:rgba(16,185,129,.1);color:var(--success)}
.kpi-card.warning .kpi-icon{background:rgba(245,158,11,.1);color:var(--warning)}
.filter-section{background:#fff;border-radius:16px;padding:1.5rem;margin-bottom:2rem;box-shadow:0 2px 8px rgba(0,0,0,.08);animation:fadeIn .7s}
.filter-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-top:1rem}
.filter-group{display:flex;flex-direction:column}
.filter-group label{font-size:.875rem;font-weight:600;color:var(--gray-700);margin-bottom:.5rem}
.filter-input{padding:.625rem 1rem;border:2px solid var(--gray-200);border-radius:8px;font-size:.9rem;transition:all .2s}
.filter-input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(12,83,67,.1)}
.table-card{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);animation:fadeIn .8s}
.table-header{padding:1.5rem;border-bottom:1px solid var(--gray-200);display:flex;justify-content:space-between;align-items:center;background:linear-gradient(to right,var(--gray-50),#fff)}
.table-title{font-size:1.125rem;font-weight:600;color:var(--gray-900);display:flex;align-items:center;gap:.5rem}
.modern-table{width:100%;border-collapse:separate;border-spacing:0}
.modern-table thead th{background:var(--gray-50);padding:1rem 1.5rem;text-align:left;font-size:.875rem;font-weight:600;color:var(--gray-700);text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid var(--gray-200)}
.modern-table tbody tr{transition:all .2s;border-bottom:1px solid var(--gray-100)}
.modern-table tbody tr:hover{background:var(--gray-50);transform:scale(1.01);box-shadow:0 2px 8px rgba(0,0,0,.05)}
.modern-table tbody td{padding:1rem 1.5rem;font-size:.9rem;color:var(--gray-800)}
.contract-id{font-weight:600;color:var(--primary);font-family:'Courier New',monospace}
.status-badge{display:inline-flex;align-items:center;gap:.375rem;padding:.375rem .875rem;border-radius:20px;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
.status-badge.active{background:rgba(16,185,129,.15);color:#059669}
.status-badge.expired{background:rgba(239,68,68,.15);color:#dc2626}
.status-badge.expiring{background:rgba(245,158,11,.15);color:#d97706}
.progress-bar{width:100px;height:8px;background:var(--gray-200);border-radius:4px;overflow:hidden}
.progress-fill{height:100%;background:linear-gradient(90deg,var(--success),var(--primary));border-radius:4px;transition:width .3s}
.progress-fill.high{background:linear-gradient(90deg,var(--warning),var(--danger))}
.action-buttons{display:flex;gap:.5rem}
.btn-icon{width:32px;height:32px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:8px}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
</style>

<!-- Dashboard Header -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;animation:fadeInDown .5s">
    <div>
        <h1 class="page-title">Contract Management</h1>
        <p class="page-subtitle">Track and manage all contracts with clients</p>
    </div>
    <div>
        <?= $this->Html->link('<i class="fas fa-file-contract"></i> Create Contract', ['action' => 'add'], ['class' => 'btn btn-primary btn-lg', 'escape' => false]) ?>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card success">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Active Contracts</h4>
                <div class="kpi-value"><?= number_format($activeCount) ?></div>
            </div>
            <div class="kpi-icon"><i class="fas fa-file-signature"></i></div>
        </div>
    </div>
    
    <div class="kpi-card warning">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Expiring Soon</h4>
                <div class="kpi-value"><?= number_format($expiringCount) ?></div>
            </div>
            <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Total Value</h4>
                <div class="kpi-value" style="font-size:1.5rem"><?= $this->Number->currency($totalValue, 'USD') ?></div>
            </div>
            <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Avg. Utilization</h4>
                <div class="kpi-value"><?= number_format($avgUtilization, 1) ?>%</div>
            </div>
            <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="filter-section">
    <h3 style="font-size:1rem;font-weight:600;margin-bottom:.5rem">
        <i class="fas fa-filter"></i> Filters
    </h3>
    <div class="filter-grid">
        <div class="filter-group">
            <label>Search Contract</label>
            <input type="text" id="searchInput" class="filter-input" placeholder="Contract ID, client...">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select id="statusFilter" class="filter-input">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="expiring">Expiring Soon</option>
                <option value="expired">Expired</option>
            </select>
        </div>
    </div>
</div>

<!-- Contracts Table -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-list"></i>
            Contract List
        </h3>
    </div>
    
    <div style="overflow-x:auto">
        <table class="modern-table" id="contractsTable">
            <thead>
                <tr>
                    <th>Contract ID</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Utilization</th>
                    <th>Total Value</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): 
                    // Calculate utilization
                    $used = $contract->quantity - ($contract->remaining_quantity ?? $contract->quantity);
                    $utilizationPercent = $contract->quantity > 0 ? ($used / $contract->quantity) * 100 : 0;
                    
                    // Determine display status
                    $endDate = $contract->end_date ? new \DateTime($contract->end_date->format('Y-m-d')) : null;
                    $today = new \DateTime();
                    $thirtyDaysFromNow = (new \DateTime())->modify('+30 days');
                    
                    $displayStatus = 'active';
                    $statusClass = 'active';
                    if ($endDate && $endDate < $today) {
                        $displayStatus = 'expired';
                        $statusClass = 'expired';
                    } elseif ($endDate && $endDate <= $thirtyDaysFromNow) {
                        $displayStatus = 'expiring';
                        $statusClass = 'expiring';
                    }
                ?>
                <tr>
                    <td>
                        <span class="contract-id"><?= h($contract->contract_id) ?></span>
                    </td>
                    <td>
                        <?= $contract->has('client') ? h($contract->client->name) : '' ?>
                    </td>
                    <td>
                        <?= $contract->has('product') ? h($contract->product->name) : '' ?>
                    </td>
                    <td>
                        <strong><?= $this->Number->format($contract->quantity, ['places' => 3]) ?></strong> MT
                        <div style="font-size:.8rem;color:var(--gray-600);margin-top:.25rem">
                            Remaining: <?= $this->Number->format($contract->remaining_quantity ?? $contract->quantity, ['places' => 3]) ?> MT
                        </div>
                    </td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill <?= $utilizationPercent >= 80 ? 'high' : '' ?>" style="width:<?= $utilizationPercent ?>%"></div>
                        </div>
                        <div style="font-size:.8rem;color:var(--gray-600);margin-top:.25rem">
                            <?= number_format($utilizationPercent, 1) ?>%
                        </div>
                    </td>
                    <td>
                        <strong><?= $this->Number->currency($contract->total_value ?? 0, 'USD') ?></strong>
                        <div style="font-size:.8rem;color:var(--gray-600);margin-top:.25rem">
                            @ <?= $this->Number->currency($contract->unit_price ?? 0, 'USD') ?>/MT
                        </div>
                    </td>
                    <td>
                        <?php if ($contract->start_date && $contract->end_date): ?>
                            <div style="font-size:.85rem">
                                <?= h($contract->start_date->format('M d, Y')) ?>
                            </div>
                            <div style="font-size:.75rem;color:var(--gray-600)">
                                to <?= h($contract->end_date->format('M d, Y')) ?>
                            </div>
                        <?php else: ?>
                            <span style="color:var(--gray-400)">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge <?= $statusClass ?>">
                            <i class="fas fa-circle"></i>
                            <?= h(ucfirst($displayStatus)) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <?= $this->Html->link(
                                '<i class="fas fa-eye"></i>',
                                ['action' => 'view', $contract->id],
                                ['class' => 'btn btn-sm btn-info btn-icon', 'escape' => false, 'title' => 'View']
                            ) ?>
                            
                            <?= $this->Html->link(
                                '<i class="fas fa-edit"></i>',
                                ['action' => 'edit', $contract->id],
                                ['class' => 'btn btn-sm btn-warning btn-icon', 'escape' => false, 'title' => 'Edit']
                            ) ?>
                            
                            <?= $this->Form->postLink(
                                '<i class="fas fa-trash"></i>',
                                ['action' => 'delete', $contract->id],
                                [
                                    'confirm' => 'Are you sure you want to delete contract ' . $contract->contract_id . '?',
                                    'class' => 'btn btn-sm btn-danger btn-icon',
                                    'escape' => false,
                                    'title' => 'Delete'
                                ]
                            ) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Simple footer -->
    <div style="padding:1.5rem;border-top:1px solid var(--gray-200);display:flex;justify-content:flex-end;align-items:center;background:var(--gray-50)">
        <p style="margin:0;color:var(--gray-600);font-size:.9rem">
            Showing <?= count($contracts) ?> contract(s)
        </p>
    </div>
</div>

<script>
// Client-side filtering
document.getElementById('searchInput')?.addEventListener('input', filterTable);
document.getElementById('statusFilter')?.addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    
    const table = document.getElementById('contractsTable');
    const rows = table?.getElementsByTagName('tbody')[0]?.getElementsByTagName('tr');
    
    if (!rows) return;
    
    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        const matchSearch = text.includes(searchTerm);
        const matchStatus = !statusFilter || text.includes(statusFilter);
        
        row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    }
}
</script>
