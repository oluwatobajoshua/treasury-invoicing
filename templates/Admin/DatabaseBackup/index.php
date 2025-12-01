<?php
/**
 * @var \App\View\AppView $this
 * @var array $backups
 */
$this->assign('title', 'Database Backup & Restore');
?>

<style>
:root{--primary:#0c5343;--primary-dark:#083d2f}
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
.data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden;margin-bottom:1rem}
.card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem}
.toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
.card-body-sleek{padding:1.25rem}

.backup-warning {
    background: #fff3cd;
    border-left: 4px solid #ff5722;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    border-radius: 8px;
}

.backup-warning strong {
    display: block;
    color: #856404;
    margin-bottom: 0.5rem;
}

.backup-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.backup-table table {
    width: 100%;
    border-collapse: collapse;
}

.backup-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    border-bottom: 1px solid #e5e7eb;
}

.backup-table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.backup-table tbody tr:hover {
    background: #f9fafb;
}

.backup-empty {
    text-align: center;
    padding: 3rem;
    color: var(--gray-500);
}

.backup-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.btn-group {
    display: flex;
    gap: 0.5rem;
}

.file-icon {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    font-weight: 600;
}

.file-size {
    color: var(--gray-600);
    font-size: 0.8125rem;
}

.backup-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-box {
    background: white;
    padding: 1.25rem;
    border-radius: 10px;
    border-left: 3px solid var(--primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.stat-box h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1.5rem;
    color: var(--primary);
    font-weight: 700;
}

.stat-box p {
    margin: 0;
    color: var(--gray-600);
    font-size: 0.875rem;
}
</style>

<div class="page-header-sleek">
    <div>
        <h1 class="page-title-sleek"><i class="fas fa-database"></i> Database Backup & Restore</h1>
        <p class="page-subtitle-sleek">Create, download, and restore database backups</p>
    </div>
    <div>
        <?= $this->Form->postLink(
            '<i class="fas fa-plus-circle"></i> Create Backup',
            ['action' => 'create'],
            [
                'class' => 'btn btn-primary js-swal-post',
                'escape' => false,
                'data-swal-title' => 'Create Backup?',
                'data-swal-text' => 'Create a new database backup? This may take a few moments.'
            ]
        ) ?>
    </div>
</div>

<div class="backup-warning">
    <strong><i class="fas fa-exclamation-triangle"></i> Important Notes:</strong>
    <ul style="margin: 0.5rem 0 0 1.5rem; line-height: 1.8;">
        <li>Always create a backup before performing major operations or updates</li>
        <li>Store backups in a secure location outside the web server</li>
        <li>Test restore procedures regularly to ensure backup integrity</li>
        <li>Restoring a backup will replace all current data - use with caution!</li>
    </ul>
</div>

<!-- Statistics -->
<div class="backup-stats">
    <div class="stat-box">
        <h3><?= count($backups) ?></h3>
        <p><i class="fas fa-archive"></i> Total Backups</p>
    </div>
    <div class="stat-box">
        <h3><?= empty($backups) ? 'N/A' : $this->Number->toReadableSize($backups[0]['size']) ?></h3>
        <p><i class="fas fa-file"></i> Latest Backup Size</p>
    </div>
    <div class="stat-box">
        <h3><?= empty($backups) ? 'Never' : $backups[0]['modified']->timeAgoInWords() ?></h3>
        <p><i class="fas fa-clock"></i> Last Backup</p>
    </div>
</div>

<!-- Actions -->
<div class="backup-actions">
    <?= $this->Form->postLink(
        '<i class="fas fa-plus-circle"></i> Create New Backup',
        ['action' => 'create'],
        [
            'class' => 'btn btn-primary btn-lg js-swal-post',
            'escape' => false,
            'data-swal-title' => 'Create Backup?',
            'data-swal-text' => 'Create a new database backup? This may take a few moments.'
        ]
    ) ?>
    
    <button onclick="refreshBackups()" class="btn btn-outline btn-lg">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
</div>

<!-- Backups Table -->
<div class="data-card-sleek">
    <div class="card-toolbar-sleek">
        <h3 class="toolbar-title-sleek"><i class="fas fa-list"></i> Backups</h3>
    </div>
    <div class="card-body-sleek backup-table">
        <table id="backupsTable" class="table table-hover" style="width:100%">
        <thead>
            <tr>
                <th><i class="fas fa-file"></i> Backup File</th>
                <th><i class="fas fa-hdd"></i> Size</th>
                <th><i class="fas fa-calendar"></i> Created Date</th>
                <th><i class="fas fa-cog"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($backups)): ?>
            <tr>
                <td colspan="4">
                    <div class="backup-empty">
                        <i class="fas fa-database"></i>
                        <p><strong>No Backups Found</strong></p>
                        <p>Create your first database backup to get started</p>
                    </div>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($backups as $backup): ?>
                <tr>
                    <td>
                        <span class="file-icon">
                            <i class="fas fa-file-archive"></i>
                            <?= h($backup['filename']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="file-size">
                            <?= $this->Number->toReadableSize($backup['size']) ?>
                        </span>
                    </td>
                    <td>
                        <?= $backup['modified']->format('M d, Y - H:i:s') ?><br>
                        <small class="text-muted"><?= $backup['modified']->timeAgoInWords() ?></small>
                    </td>
                    <td>
                        <div class="btn-group">
                            <?= $this->Html->link(
                                '<i class="fas fa-download"></i>',
                                ['action' => 'download', $backup['filename']],
                                [
                                    'class' => 'btn btn-sm btn-info',
                                    'escape' => false,
                                    'title' => 'Download'
                                ]
                            ) ?>
                            
                            <?= $this->Form->postLink(
                                '<i class="fas fa-undo"></i>',
                                ['action' => 'restore', $backup['filename']],
                                [
                                    'class' => 'btn btn-sm btn-warning js-swal-post',
                                    'escape' => false,
                                    'title' => 'Restore',
                                    'data-swal-title' => 'Restore from this backup?',
                                    'data-swal-text' => 'This will replace <strong>ALL current data</strong> with data from <strong>"' . h($backup['filename']) . '"</strong> (' . $backup['modified']->format('M d, Y H:i:s') . ').<br><br>This action <strong>cannot be undone</strong>.'
                                ]
                            ) ?>
                            
                            <?= $this->Form->postLink(
                                '<i class="fas fa-trash"></i>',
                                ['action' => 'delete', $backup['filename']],
                                [
                                    'class' => 'btn btn-sm btn-danger js-swal-post',
                                    'escape' => false,
                                    'title' => 'Delete',
                                    'data-swal-title' => 'Delete backup file?',
                                    'data-swal-text' => 'Delete backup file:<br><strong>"' . h($backup['filename']) . '"</strong><br><br>This cannot be undone.'
                                ]
                            ) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
                </tbody>
        </table>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    <?php if (!empty($backups)): ?>
    $('#backupsTable').DataTable({
        pageLength: 25,
        order: [[2, 'desc']], // Sort by created date
        columnDefs: [
            { orderable: false, targets: 3 } // Disable sorting on actions column
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search backups...",
            lengthMenu: "Show _MENU_ backups",
            info: "Showing _START_ to _END_ of _TOTAL_ backups"
        }
    });
    <?php endif; ?>
});

function refreshBackups() {
    location.reload();
}
</script>
