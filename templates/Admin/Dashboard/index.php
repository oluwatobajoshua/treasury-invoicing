<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Admin Dashboard');
?>

<style>
.kpi-grid {display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem;margin-bottom:1rem;}
.kpi-card {background:var(--card);border:var(--border);border-radius:8px;padding:.75rem;display:flex;flex-direction:column;gap:.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);}
.kpi-card h3 {margin:0;font-size:.65rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);font-weight:600;}
.kpi-value {font-size:1.4rem;font-weight:700;color:var(--primary);line-height:1;}
.kpi-sub {font-size:.6rem;color:var(--muted);}
.section-title {font-size:.9rem;margin:.5rem 0 .4rem;font-weight:600;display:flex;align-items:center;gap:.4rem;}
.status-badges {display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.8rem;}
.status-badge {background:var(--gray-100);padding:.4rem .6rem;border-radius:16px;font-size:.6rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;border:1px solid var(--gray-200);} 
.status-badge.approved {background:#e6f9ed;border-color:#c4e8d2;color:#0c5343;}
.status-badge.processing {background:#fff4e8;border-color:#ffe0c2;color:#b45309;}
.status-badge.rejected {background:#fdecea;border-color:#f8c5bf;color:#a61b0f;}
.status-badge.submitted {background:#e8f0fe;border-color:#c3dafd;color:#0c4ca3;}
.status-badge.completed {background:#f1f5ff;border-color:#d2e3ff;color:#0c5343;}
.recent-table {width:100%;border-collapse:separate;border-spacing:0;background:var(--card);border:var(--border);border-radius:6px;}
.recent-table th {background:var(--gray-100);font-size:.6rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600;padding:.45rem .55rem;text-align:left;}
.recent-table td {padding:.45rem .55rem;font-size:.65rem;border-top:1px solid var(--gray-100);} 
.recent-table tbody tr:hover {background:var(--gray-50);} 
@media (max-width:640px){.kpi-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));}}
.length-wrapper-inline .dataTables_length {display:flex;align-items:center;gap:.4rem;}
.length-wrapper-inline .dataTables_length label {display:flex;align-items:center;gap:.4rem;margin:0;font-size:.65rem;font-weight:600;}
</style>

<div class="fade-in">
    <h2 style="margin:0 0 .75rem;font-size:1.05rem;display:flex;align-items:center;gap:.5rem;">🛠️ Admin Dashboard <small class="muted" style="font-size:.65rem;font-weight:500;">System Overview</small></h2>

    <div class="kpi-grid">
        <div class="kpi-card">
            <h3>Users</h3>
            <div class="kpi-value"><?= (int)$usersCount ?></div>
            <div class="kpi-sub">Total registered</div>
        </div>
        <div class="kpi-card">
            <h3>Requests</h3>
            <div class="kpi-value"><?= (int)$requestsCount ?></div>
            <div class="kpi-sub">All travel requests</div>
        </div>
        <div class="kpi-card">
            <h3>Submitted</h3>
            <div class="kpi-value"><?= (int)($statusCounts['submitted'] ?? 0) ?></div>
            <div class="kpi-sub">Awaiting LM</div>
        </div>
        <div class="kpi-card">
            <h3>LM Approved</h3>
            <div class="kpi-value"><?= (int)($statusCounts['lm_approved'] ?? 0) ?></div>
            <div class="kpi-sub">Ready for admin</div>
        </div>
        <div class="kpi-card">
            <h3>Processing</h3>
            <div class="kpi-value"><?= (int)($statusCounts['admin_processing'] ?? 0) ?></div>
            <div class="kpi-sub">Allowance calc</div>
        </div>
        <div class="kpi-card">
            <h3>Completed</h3>
            <div class="kpi-value"><?= (int)($statusCounts['completed'] ?? 0) ?></div>
            <div class="kpi-sub">Finished trips</div>
        </div>
    </div>

    <div class="section-title">📊 Status Breakdown</div>
    <div class="status-badges">
        <span class="status-badge submitted">⏳ Submitted: <?= (int)($statusCounts['submitted'] ?? 0) ?></span>
        <span class="status-badge approved">✅ LM Approved: <?= (int)($statusCounts['lm_approved'] ?? 0) ?></span>
        <span class="status-badge processing">⚙️ Processing: <?= (int)($statusCounts['admin_processing'] ?? 0) ?></span>
        <span class="status-badge completed">✨ Completed: <?= (int)($statusCounts['completed'] ?? 0) ?></span>
        <span class="status-badge rejected">❌ LM Rejected: <?= (int)($statusCounts['lm_rejected'] ?? 0) ?></span>
    </div>

    <div class="section-title">🆕 Recent Requests</div>
    <table class="recent-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Traveler</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($recentRequests as $r): ?>
            <tr>
                <td><code><?= h($r->request_number) ?></code></td>
                <td><?= h(($r->user->first_name ?? '') . ' ' . ($r->user->last_name ?? '')) ?></td>
                <td><?= h($r->destination) ?></td>
                <td><?= h($r->status) ?></td>
                <td><?= $r->created ? $r->created->format('Y-m-d') : '' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top:1rem;font-size:.65rem;color:var(--muted);">Need more analytics? We can add charts (e.g., monthly trends, top destinations) next.</div>
</div>
