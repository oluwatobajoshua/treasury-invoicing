<!-- DataTables CSS - Add to <head> or top of file -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS - Add before closing </body> tag or at bottom -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- DataTables Initialization Script -->
<script>
$(document).ready(function() {
    // Replace '#tableId' with your actual table ID
    $('#tableId').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']], // Adjust column index for sorting
        dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'dt-button',
                exportOptions: {
                    columns: ':visible:not(:last-child)' // Exclude last column (Actions)
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'dt-button',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'dt-button',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns',
                className: 'dt-button'
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records...",
            lengthMenu: "Show _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records available",
            infoFiltered: "(filtered from _MAX_ total records)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});
</script>

<!-- DataTables Custom Styling (Add to <style> section) -->
<style>
/* DataTables Custom Styling */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    padding: .5rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: .875rem;
    transition: all 0.3s ease;
}
.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(12, 83, 67, 0.1);
}
.dataTables_wrapper .dataTables_info {
    padding-top: 1rem;
    color: #6b7280;
    font-size: .875rem;
}
.dataTables_wrapper .dataTables_paginate {
    padding-top: 1rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: .5rem .75rem;
    margin: 0 .25rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    font-size: .875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f9fafb;
    border-color: var(--primary);
    color: var(--primary);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.dt-buttons {
    margin-bottom: 1rem;
    display: flex;
    gap: .5rem;
}
.dt-button {
    padding: .625rem 1.25rem !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    background: white !important;
    color: #374151 !important;
    font-size: .875rem !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
}
.dt-button:hover {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(12, 83, 67, 0.2) !important;
}
</style>
