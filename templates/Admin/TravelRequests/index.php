<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Travel Requests');
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
/* DataTables Custom Styling (match non-admin) */
.dataTables_wrapper { font-family: var(--font); }

#travel-requests-table_wrapper {
    background: var(--card);
    border-radius: 6px;
    padding: 0.6rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    border: var(--border);
}

#travel-requests-table { width: 100% !important; border-collapse: separate; border-spacing: 0; }

#travel-requests-table thead th {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-500) 100%);
    color: white !important;
}
#travel-requests-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid var(--gray-200); }
#travel-requests-table tbody tr:hover { background: var(--gray-50); transform: scale(1.005); box-shadow: 0 2px 6px rgba(12, 83, 67, 0.06); }
#travel-requests-table tbody td { padding: 0.45rem 0.55rem; vertical-align: middle; border: none; font-size: 0.75rem; }

.dataTables_filter input,
.dataTables_length select { border: 1px solid var(--gray-200); border-radius: 4px; padding: 0.3rem 0.5rem; font-family: var(--font); transition: all 0.3s ease; font-size: 0.75rem; }
.dataTables_filter input:focus,
.dataTables_length select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(12, 83, 67, 0.1); }

.dataTables_info { color: var(--muted); padding-top: 0.5rem; font-size: 0.7rem; }

.dataTables_paginate .paginate_button { padding: 0.3rem 0.5rem; margin: 0 0.1rem; border-radius: 3px; border: 1px solid var(--gray-200); background: white; color: var(--primary) !important; transition: all 0.2s ease; font-size: 0.7rem; }
.dataTables_paginate .paginate_button:hover { background: var(--primary) !important; color: white !important; border-color: var(--primary); }
.dataTables_paginate .paginate_button.current { background: var(--primary) !important; color: white !important; border-color: var(--primary); }
.dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; }

.dt-buttons { margin-bottom: 0.5rem; }
.dt-button { background: var(--primary) !important; color: white !important; border: none !important; padding: 0.3rem 0.6rem !important; border-radius: 4px !important; margin-right: 0.3rem !important; font-weight: 600 !important; transition: all 0.2s ease !important; font-size: 0.7rem !important; }
.dt-button:hover { background: var(--primary-600) !important; transform: translateY(-1px); }

@media (max-width: 968px) {
    #travel-requests-table_wrapper { padding: 0.75rem; }
    #travel-requests-table thead th,
    #travel-requests-table tbody td { padding: 0.5rem; font-size: 0.75rem; }
    .badge-sm { font-size: 0.65rem; padding: 0.2rem 0.4rem; }
}

@media (max-width: 640px) {
    #travel-requests-table_wrapper { padding: 0.5rem; }
    #travel-requests-table { font-size: 0.7rem; }
    #travel-requests-table thead th,
    #travel-requests-table tbody td { padding: 0.4rem; white-space: nowrap; }
    .btn-icon-sm { width: 24px; height: 24px; font-size: 0.7rem; }
    .dataTables_filter input { width: 100%; padding: 0.35rem 0.5rem; }
    .dt-button { padding: 0.35rem 0.5rem !important; font-size: 0.7rem !important; }
}

/* Action buttons column - ensure always visible */
#travel-requests-table thead th:last-child,
#travel-requests-table tbody td:last-child {
    min-width: 180px !important;
    width: 180px !important;
    max-width: 180px !important;
    text-align: left !important;
    white-space: nowrap !important;
}

#travel-requests-table tbody td.action-buttons { visibility: visible !important; display: table-cell !important; opacity: 1 !important; }
.action-buttons a { visibility: visible !important; display: inline-flex !important; opacity: 1 !important; }
</style>

<!-- Page Header -->
<div class="card fade-in" style="margin-bottom: 0.6rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;" class="index-page-header">
        <div>
            <h2 class="title" style="margin-bottom: 0.1rem; font-size: 1.1rem;">✈️ Travel Requests (Admin)</h2>
            <p class="muted" style="margin: 0; font-size: 0.7rem;">Review, track, and manage all travel requests</p>
        </div>
        <div style="display: flex; gap: 0.4rem; align-items: center;" class="index-header-actions">
            <?= $this->Html->link('📊 Admin Dashboard', ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'], ['class' => 'btn btn-outline btn-sm']) ?>
            <?= $this->Html->link('👁️ View App', ['prefix' => false, 'controller' => 'TravelRequests', 'action' => 'index'], ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
    </div>
    <style>
    @media (max-width: 968px) {
        .index-page-header { flex-direction: column; align-items: stretch !important; }
        .index-page-header h2 { font-size: 1.3rem !important; }
        .index-header-actions { width: 100%; }
        .index-header-actions .btn { flex: 1; text-align: center; }
    }
    @media (max-width: 640px) {
        .index-page-header h2 { font-size: 1.2rem !important; }
        .index-header-actions { flex-direction: column; }
        .index-header-actions .btn { width: 100%; }
    }
    </style>
</div>

<!-- DataTable -->
<div class="fade-in">
    <table id="travel-requests-table" class="display responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Request #</th>
                <th>Traveller</th>
                <th>Destination</th>
                <th>Type</th>
                <th>Departure</th>
                <th>Return</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Step</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Mini progress indicator helper (same as non-admin)
    function getMiniProgressIndicator(currentStep) {
        const steps = ['📧', '✈️', '👔', '💰', '✅'];
        let html = '<div style="display: flex; gap: 3px; align-items: center;">';
        for (let i = 0; i < steps.length; i++) {
            const stepNum = i + 1;
            let style = 'width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; ';
            if (stepNum < currentStep) {
                style += 'background: #4CAF50; color: white; border: 2px solid #4CAF50;';
            } else if (stepNum === currentStep) {
                style += 'background: #F64500; color: white; border: 2px solid #F64500; box-shadow: 0 0 8px rgba(246, 69, 0, 0.4);';
            } else {
                style += 'background: #e0e0e0; color: #999; border: 2px solid #ccc;';
            }
            html += '<div style="' + style + '">' + steps[i] + '</div>';
        }
        html += '</div>';
        return html;
    }

    // Status badge helper (same map as non-admin)
    function getStatusBadge(status) {
        const badges = {
            'draft': '<span class="badge" style="background: var(--gray-200); color: var(--gray-700); padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">📝 Draft</span>',
            'submitted': '<span class="badge pending" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">⏳ Submitted</span>',
            'lm_review': '<span class="badge processing" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">👀 Review</span>',
            'lm_approved': '<span class="badge approved" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">✅ Approved</span>',
            'lm_rejected': '<span class="badge rejected" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">❌ Rejected</span>',
            'admin_processing': '<span class="badge processing" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">⚙️ Processing</span>',
            'completed': '<span class="badge approved" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">✨ Completed</span>',
            'cancelled': '<span class="badge rejected" style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">🚫 Cancelled</span>',
        };
        return badges[status] || '<span class="badge">' + status + '</span>';
    }

    try {
    $('#travel-requests-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= $this->Url->build(["action" => "index"]) ?>',
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        },
        columns: [
            { 
                data: 'request_number',
                responsivePriority: 2,
                render: function(data) {
                    return '<span style="font-family: monospace; font-weight: 600; color: var(--primary); background: var(--gray-100); padding: 4px 8px; border-radius: 4px;">' + data + '</span>';
                }
            },
            { 
                data: 'user',
                responsivePriority: 3,
                render: function(data) {
                    return '<span style="font-weight: 600; color: var(--text);">👤 ' + (data.name || data.email || 'N/A') + '</span>';
                }
            },
            { 
                data: 'destination',
                responsivePriority: 4,
                render: function(data, type, row) {
                    const purpose = (row.purpose || '').substring(0, 50);
                    return '<strong style="color: var(--primary);">📍 ' + (data || '') + '</strong><br><small style="color: var(--muted);">' + purpose + (purpose.length === 50 ? '...' : '') + '</small>';
                }
            },
            { 
                data: 'travel_type',
                responsivePriority: 7,
                render: function(data) {
                    if (data === 'local') {
                        return '<span class="travel-type local" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;">🇳🇬 Local</span>';
                    } else {
                        return '<span class="travel-type international" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;">🌍 International</span>';
                    }
                }
            },
            { 
                data: 'departure_date',
                responsivePriority: 8,
                render: function(data) {
                    if (!data) return '';
                    const date = new Date(data);
                    return '<span style="white-space: nowrap;">📅 ' + date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + '</span>';
                }
            },
            { 
                data: 'return_date',
                responsivePriority: 9,
                render: function(data) {
                    if (!data) return '';
                    const date = new Date(data);
                    return '<span style="white-space: nowrap;">📅 ' + date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + '</span>';
                }
            },
            { 
                data: 'duration_days',
                responsivePriority: 10,
                render: function(data) { return '<span style="white-space: nowrap;">⏱️ ' + (data || 0) + ' days</span>'; }
            },
            { 
                data: 'status',
                responsivePriority: 5,
                render: function(data) { return getStatusBadge(data); }
            },
            { 
                data: 'current_step',
                responsivePriority: 6,
                render: function(data) { return getMiniProgressIndicator(parseInt(data || 1)); }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                width: '180px',
                className: 'action-buttons all',
                responsivePriority: 1,
                render: function(data, type, row) {
                    let buttons = '<div style="display: flex; gap: 6px; align-items: center; justify-content: flex-start; flex-wrap: nowrap;">';

                    // View details (admin)
                    buttons += '<a href="<?= $this->Url->build(["action" => "view"]) ?>/' + row.id + '" '
                             + 'class="btn-icon-sm" '
                             + 'style="background: var(--primary); color: white; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-decoration: none; transition: all 0.2s ease; flex-shrink: 0;" '
                             + 'title="View Details" '
                             + 'onmouseover="this.style.transform=\'scale(1.1)\'; this.style.background=\'var(--primary-600)\';" '
                             + 'onmouseout="this.style.transform=\'scale(1)\'; this.style.background=\'var(--primary)\';">👁️</a>';

                    // Quick contact info (no inline string concatenation pitfalls)
                    const email = (row.user && row.user.email) ? String(row.user.email) : 'N/A';
                    const safeEmail = email.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                    buttons += '<a href="javascript:void(0);" '
                             + 'class="btn-icon-sm btn-email" '
                             + 'data-email="' + safeEmail + '" '
                             + 'style="background: #2196F3; color: white; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-decoration: none; transition: all 0.2s ease; flex-shrink: 0;" '
                             + 'title="Contact Info" '
                             + 'onmouseover="this.style.transform=\'scale(1.1)\'; this.style.background=\'#0b7dda\';" '
                             + 'onmouseout="this.style.transform=\'scale(1)\'; this.style.background=\'#2196F3\';">📧</a>';

                    buttons += '</div>';
                    return buttons;
                }
            }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[4, 'desc']],
        responsive: true,
        dom: '<"top"Bfl>rt<"bottom"ip><"clear">',
        // Export buttons with UTF-8 BOM for proper Excel rendering
        buttons: [
            { extend: 'copy', text: '📋 Copy' },
            {
                extend: 'csvHtml5',
                text: '📊 Export CSV',
                bom: true,
                title: 'travel_requests_admin',
                exportOptions: {
                    // Exclude action buttons column
                    columns: ':not(:last-child)',
                    format: {
                        body: function (data, row, column, node) {
                            if (data == null) return '';
                            // Strip HTML and most emoji/symbols that Excel may mangle
                            let text = (typeof data === 'string') ? data : String(data);
                            text = text.replace(/<[^>]*>/g, '');
                            try {
                                text = text.replace(/[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}]/gu, '');
                            } catch (e) { /* older browsers without 'u' flag */ }
                            return text.replace(/\s+/g, ' ').trim();
                        }
                    }
                }
            },
            { extend: 'print', text: '🖨️ Print' }
        ],
        language: {
            search: "🔍 Search:",
            lengthMenu: "Show _MENU_ requests per page",
            info: "Showing _START_ to _END_ of _TOTAL_ requests",
            infoEmpty: "No requests available",
            infoFiltered: "(filtered from _MAX_ total requests)",
            zeroRecords: "No matching requests found",
            emptyTable: "No travel requests available yet",
            loadingRecords: "Loading...",
            processing: '<div style="display: flex; align-items: center; gap: 0.5rem; color: var(--primary);"><div class="spinner"></div> Loading travel requests...</div>'
        }
    });
    } catch (e) {
        console.error('Failed to initialize Travel Requests DataTable:', e);
        const msg = document.createElement('div');
        msg.className = 'alert alert-error';
        msg.style.marginTop = '8px';
        msg.style.padding = '8px 12px';
        msg.style.border = '1px solid var(--danger, #f44336)';
        msg.style.background = 'rgba(244,67,54,0.05)';
        msg.style.borderRadius = '6px';
        msg.style.color = 'var(--danger, #b71c1c)';
        msg.textContent = 'Unable to load table due to a script error. Please refresh. If it continues, check the browser console for details.';
        document.querySelector('#travel-requests-table').parentElement.appendChild(msg);
    }
});
</script>

<script>
// Delegate click for contact-info buttons to avoid inline JS quoting issues
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-email');
    if (!btn) return;
    const email = btn.getAttribute('data-email') || 'N/A';
    alert('Email: ' + email);
});
</script>

<style>
/* Keep DataTables length control on one line */
.dataTables_length label { white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; }
.dataTables_length select { display: inline-block; width: auto; }
</style>
