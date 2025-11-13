<?php
/** @var \App\View\AppView $this */
// Styled Admin View matching the non-admin design
$this->assign('title', 'Travel Request #' . h($request->request_number ?? $request->id));
?>

<style>
    .view-container { max-width: 1400px; margin: 0 auto; }
    .view-header { background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%); color: white; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1rem; box-shadow: 0 2px 12px rgba(12, 83, 67, 0.15); }
    .view-header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; }
    .header-left h1 { font-size: 1.2rem; margin: 0; font-weight: 700; }
    .request-number-badge { font-family: 'Courier New', monospace; font-size: 0.85rem; font-weight: 700; background: rgba(255,255,255,0.25); padding: 0.35rem 0.75rem; border-radius: 6px; display: inline-block; }
    .status-badge-large { padding: 0.5rem 1rem; border-radius: 999px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
    .status-badge-large.pending { color: #FF9800; }
    .status-badge-large.approved { color: #4CAF50; }
    .status-badge-large.rejected { color: #f44336; }
    .status-badge-large.processing { color: #2196F3; }
    .action-bar { display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; padding: 0.75rem; background: var(--gray-50); border-radius: 6px; border: 1px solid var(--gray-200); }
    .section-divider { border: none; height: 1px; background: linear-gradient(90deg, var(--primary), transparent); margin: 1rem 0 0.75rem 0; }
    .section-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding-bottom: 0.4rem; border-bottom: 2px solid var(--gray-200); }
    .section-header h2 { font-size: 1rem; margin: 0; color: var(--primary); font-weight: 700; }
    .section-icon { font-size: 1.25rem; }
    .info-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
    .info-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
    @media (max-width: 1200px) { .info-grid-4 { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 992px) { .info-grid-3, .info-grid-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .info-grid-3, .info-grid-4 { grid-template-columns: 1fr; } }
    .info-card { background: white; border-radius: 8px; padding: 0.85rem; border: 2px solid var(--gray-200); box-shadow: 0 1px 4px rgba(0,0,0,0.04); transition: all 0.3s ease; position: relative; overflow: hidden; }
    .info-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--primary); transform: scaleY(0); transition: transform 0.3s ease; }
    .info-card:hover { box-shadow: 0 8px 24px rgba(12,83,67,0.15); transform: translateY(-4px); border-color: var(--primary); }
    .info-card:hover::before { transform: scaleY(1); }
    .info-card-icon { font-size: 1.5rem; margin-bottom: 0.25rem; display: block; }
    .info-card-title { font-size: 0.65rem; text-transform: uppercase; color: var(--muted); font-weight: 700; letter-spacing: 0.8px; margin-bottom: 0.3rem; }
    .info-card-value { font-size: 0.9rem; font-weight: 700; color: var(--text); line-height: 1.3; }
    .info-card-meta { font-size: 0.75rem; color: var(--muted); margin-top: 0.3rem; }
    .content-box { background: white; border-radius: 8px; padding: 1rem; border: 2px solid var(--gray-200); box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin-bottom: 1rem; }
    .content-box h3 { color: var(--primary); font-size: 0.95rem; margin: 0 0 0.75rem 0; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
    .content-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem; }
    @media (max-width: 768px) { .content-grid-2 { grid-template-columns: 1fr; } }
    .allowance-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .allowance-table thead { background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%); color: white; }
    .allowance-table th { padding: 0.75rem; text-align: left; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .allowance-table th:last-child, .allowance-table td:last-child { text-align: right; }
    .allowance-table tbody tr { border-bottom: 1px solid var(--gray-200); }
    .allowance-table tbody tr:hover { background: var(--gray-50); }
    .allowance-table td { padding: 0.75rem; font-size: 0.9rem; }
    .allowance-table tfoot { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); font-weight: 700; }
    .allowance-table tfoot td { padding: 1rem 0.75rem; font-size: 1rem; border-top: 3px solid var(--primary); }
    .allowance-amount { font-family: 'Courier New', monospace; font-weight: 700; color: var(--primary); }
</style>

<div class="view-container">
    <!-- Header -->
    <div class="view-header">
        <div class="view-header-content">
            <div class="header-left">
                <div class="request-number-badge"><?= h($request->request_number ?? ('ID:' . $request->id)) ?></div>
                <h1><?= h($request->destination ?? 'Travel Request') ?></h1>
            </div>
            <div class="header-right">
                <?php
                    $status = (string)($request->status ?? 'submitted');
                    $statusClass = 'pending';
                    $statusIcon = '⏳';
                    if (in_array($status, ['lm_approved','completed'])) { $statusClass='approved'; $statusIcon='✅'; }
                    elseif ($status === 'lm_rejected' || $status === 'rejected') { $statusClass='rejected'; $statusIcon='❌'; }
                    elseif (in_array($status, ['admin_processing','power_automate'])) { $statusClass='processing'; $statusIcon='⚙️'; }
                ?>
                <div class="status-badge-large <?= $statusClass ?>"><?= $statusIcon ?> <?= ucwords(str_replace('_',' ', h($status))) ?></div>
            </div>
        </div>
    </div>

    <!-- Action Bar (Admin) -->
    <div class="action-bar">
        <?= $this->Html->link('← Back to Admin List', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        <?= $this->Html->link('👁️ View as User', ['prefix' => false, 'controller' => 'TravelRequests', 'action' => 'view', $request->id], ['class' => 'btn btn-outline']) ?>
        <button onclick="window.print()" class="btn btn-outline">🖨️ Print</button>
    </div>

    <!-- Traveler Information -->
    <div class="section-header"><span class="section-icon">👤</span><h2>Traveler Information</h2></div>
    <div class="info-grid-3">
        <div class="info-card">
            <div class="info-card-icon">👤</div>
            <div class="info-card-title">Full Name</div>
            <div class="info-card-value">
                <?php if (!empty($request->user)): ?>
                    <?= h(trim(($request->user->first_name ?? '') . ' ' . ($request->user->last_name ?? '')) ?: ($request->user->email ?? 'N/A')) ?>
                <?php else: ?>N/A<?php endif; ?>
            </div>
            <?php if (!empty($request->user) && !empty($request->user->email)): ?>
                <div class="info-card-meta">📧 <?= h($request->user->email) ?></div>
            <?php endif; ?>
        </div>
        <div class="info-card">
            <div class="info-card-icon">🏢</div>
            <div class="info-card-title">Department</div>
            <div class="info-card-value"><?= !empty($request->user->department) ? h($request->user->department) : 'N/A' ?></div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">🔢</div>
            <div class="info-card-title">Request Number</div>
            <div class="info-card-value"><?= h($request->request_number ?? ('ID:' . $request->id)) ?></div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Travel Details -->
    <div class="section-header"><span class="section-icon">✈️</span><h2>Travel Details</h2></div>
    <div class="info-grid-4">
        <div class="info-card">
            <div class="info-card-icon"><?= ($request->travel_type ?? '') === 'local' ? '🇳🇬' : '🌍' ?></div>
            <div class="info-card-title">Travel Type</div>
            <div class="info-card-value"><?= ($request->travel_type ?? '') === 'local' ? 'Local Travel' : 'International' ?></div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">📍</div>
            <div class="info-card-title">Destination</div>
            <div class="info-card-value"><?= h($request->destination) ?></div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">🛫</div>
            <div class="info-card-title">Departure Date</div>
            <div class="info-card-value">
                <?php if (!empty($request->departure_date) && method_exists($request->departure_date, 'format')): ?>
                    <?= h($request->departure_date->format('M d, Y')) ?>
                <?php else: ?><?= h($request->departure_date) ?><?php endif; ?>
            </div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">🛬</div>
            <div class="info-card-title">Return Date</div>
            <div class="info-card-value">
                <?php if (!empty($request->return_date) && method_exists($request->return_date, 'format')): ?>
                    <?= h($request->return_date->format('M d, Y')) ?>
                <?php else: ?><?= h($request->return_date) ?><?php endif; ?>
            </div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">⏱️</div>
            <div class="info-card-title">Duration</div>
            <div class="info-card-value"><?= h($request->duration_days ?? 0) ?> Days</div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">🛏️</div>
            <div class="info-card-title">Accommodation</div>
            <div class="info-card-value"><?= !empty($request->accommodation_required) ? '✅ Required' : '❌ Not Required' ?></div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">📅</div>
            <div class="info-card-title">Created On</div>
            <div class="info-card-value">
                <?php if (!empty($request->created) && method_exists($request->created, 'format')): ?>
                    <?= h($request->created->format('M d, Y')) ?>
                <?php else: ?><?= h($request->created) ?><?php endif; ?>
            </div>
        </div>
        <?php if (!empty($request->line_manager_approved_at)): ?>
        <div class="info-card">
            <div class="info-card-icon">✅</div>
            <div class="info-card-title">LM Approved</div>
            <div class="info-card-value">
                <?php if (method_exists($request->line_manager_approved_at, 'format')): ?>
                    <?= h($request->line_manager_approved_at->format('M d, Y')) ?>
                <?php else: ?><?= h($request->line_manager_approved_at) ?><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Purpose and Documents -->
    <div class="content-grid-2">
        <div class="content-box">
            <h3>📌 Purpose of Travel</h3>
            <div style="line-height: 1.6; font-size: 0.95rem; color: var(--text);">
                <?= nl2br(h($request->purpose_of_travel ?? '')) ?>
            </div>
        </div>

        <?php if (!empty($request->email_conversation_file)): ?>
        <div class="content-box">
            <h3>📎 Supporting Documents</h3>
            <div style="padding: 1rem; background: var(--gray-50); border-radius: 8px; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📄</div>
                <div style="font-weight: 600; margin-bottom: 0.5rem;">Email Conversation</div>
                <?php if (!empty($request->email_conversation_uploaded_at) && method_exists($request->email_conversation_uploaded_at, 'format')): ?>
                    <div style="color: var(--muted); margin-bottom: 1rem; font-size: 0.85rem;">
                        Uploaded: <?= h($request->email_conversation_uploaded_at->format('M d, Y')) ?>
                    </div>
                <?php endif; ?>
                <?= $this->Html->link('📥 Download', '/' . h($request->email_conversation_file), ['class' => 'btn btn-primary btn-sm', 'target' => '_blank']) ?>
            </div>
        </div>
        <?php elseif (!empty($request->notes)): ?>
        <div class="content-box">
            <h3>📝 Additional Notes</h3>
            <div style="line-height: 1.6; font-size: 0.95rem; color: var(--text);">
                <?= nl2br(h($request->notes)) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Allowances (if present) -->
    <?php if (!empty($request->total_allowance) && $request->total_allowance > 0): ?>
    <hr class="section-divider">
    <div class="section-header"><span class="section-icon">💰</span><h2>Allowances Breakdown</h2></div>
    <div class="content-box">
        <?php if (($request->travel_type ?? '') === 'international'): ?>
            <?php 
                $currency = '$';
                $days = max(1, (int)($request->duration_days ?? 1));
                $perDiemDaily = ($request->per_diem_allowance ?? 0) / $days;
                $transportDaily = ($request->transport_allowance ?? 0) / $days;
                $dailyTotal = $perDiemDaily + $transportDaily;
            ?>
            <table class="allowance-table">
                <thead><tr><th>Day</th><th>Per Diem</th><th>Transport</th><th>Daily Total</th></tr></thead>
                <tbody>
                    <?php for ($day=1; $day <= $days; $day++): ?>
                    <tr>
                        <td><strong>Day <?= $day ?></strong></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($perDiemDaily, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($transportDaily, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($dailyTotal, 2) ?></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>GRAND TOTAL (<?= $days ?> Days)</strong></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($request->per_diem_allowance ?? 0, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($request->transport_allowance ?? 0, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($request->total_allowance ?? 0, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <?php 
                $currency = '₦';
                $days = max(1, (int)($request->duration_days ?? 1));
                $perDiemDaily = ($request->per_diem_allowance ?? 0) / $days;
                $accommodationDaily = (!empty($request->accommodation_required) && !empty($request->accommodation_allowance)) ? (($request->accommodation_allowance ?? 0) / $days) : 0;
                $dailyTotal = $perDiemDaily + $accommodationDaily;
            ?>
            <table class="allowance-table">
                <thead><tr><th>Day/Night</th><?php if (!empty($request->accommodation_required) && !empty($request->accommodation_allowance)): ?><th>Accommodation</th><?php endif; ?><th>Per Diem</th><th>Daily Total</th></tr></thead>
                <tbody>
                    <?php for ($day=1; $day <= $days; $day++): ?>
                    <tr>
                        <td><strong>Day <?= $day ?><?= !empty($request->accommodation_required) && $day < $days ? ' / Night ' . $day : '' ?></strong></td>
                        <?php if (!empty($request->accommodation_required) && !empty($request->accommodation_allowance)): ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($accommodationDaily, 2) ?></td>
                        <?php endif; ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($perDiemDaily, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($dailyTotal, 2) ?></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>GRAND TOTAL (<?= $days ?> Days)</strong></td>
                        <?php if (!empty($request->accommodation_required) && !empty($request->accommodation_allowance)): ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($request->accommodation_allowance ?? 0, 2) ?></td>
                        <?php endif; ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($request->per_diem_allowance ?? 0, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($request->total_allowance ?? 0, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Request Timeline & History -->
    <hr class="section-divider">
    <div class="section-header"><span class="section-icon">🕐</span><h2>Request Timeline & History</h2></div>
    <div class="content-box">
        <?php if (!empty($history)): ?>
            <div class="timeline">
                <?php foreach ($history as $index => $h): ?>
                <div class="timeline-item" style="position: relative; padding-left: 2rem; margin-bottom: 1rem;">
                    <div class="timeline-dot <?= $index < count($history) - 1 ? 'completed' : 'current' ?>" style="position:absolute; left:-1.3rem; top:0.2rem; width:0.7rem; height:0.7rem; border-radius:50%; background:white; border:2px solid var(--primary);"></div>
                    <div class="timeline-content" style="background:white; padding:0.75rem; border-radius:6px; border:2px solid var(--gray-200); box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                        <div class="timeline-title" style="font-weight:700; color:var(--primary); margin-bottom:0.3rem;">
                            <?php
                                $emoji = '🔄';
                                $to = strtolower((string)$h->to_status);
                                if (strpos($to, 'submitted') !== false) $emoji = '📧';
                                elseif (strpos($to, 'approved') !== false) $emoji = '✅';
                                elseif (strpos($to, 'rejected') !== false) $emoji = '❌';
                                elseif (strpos($to, 'processing') !== false) $emoji = '⚙️';
                                elseif (strpos($to, 'completed') !== false) $emoji = '✨';
                            ?>
                            <?= $emoji ?> <?= ucwords(str_replace('_', ' ', h($h->to_status))) ?>
                        </div>
                        <div class="timeline-date" style="font-size:0.75rem; color:var(--muted); margin-bottom:0.4rem;">
                            <?= h(method_exists($h->created, 'format') ? $h->created->format('F d, Y \a\t h:i A') : $h->created) ?>
                            <?php if (!empty($h->user)): ?> · by <?= h($h->user->email ?? ($h->user->first_name ?? '') . ' ' . ($h->user->last_name ?? '')) ?><?php endif; ?>
                        </div>
                        <?php if (!empty($h->comments)): ?>
                        <div class="timeline-comment" style="font-size:0.85rem; color:var(--text); line-height:1.4; padding:0.6rem; background:var(--gray-50); border-radius:4px; border-left:2px solid var(--primary);">
                            💬 <?= h($h->comments) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div> No workflow history available. </div>
        <?php endif; ?>
    </div>

    <div class="action-bar" style="margin-top: 1rem;">
        <?= $this->Html->link('← Back to Admin List', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        <button onclick="window.print()" class="btn btn-outline">🖨️ Print</button>
    </div>
</div>
