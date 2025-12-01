<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Bulk Upload Final Invoices');
?>

<style>
.upload-container{max-width:1000px;margin:2rem auto;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.06);overflow:hidden}
.upload-header{background:linear-gradient(135deg,#0c5343 0%,#0a4636 100%);padding:2rem;color:#fff;text-align:center}
.upload-header h2{margin:0 0 .5rem;font-size:1.75rem;font-weight:800}
.upload-header p{margin:0;font-size:.9rem;opacity:.9}
.upload-body{padding:2rem}
.upload-zone{border:3px dashed #ddd;border-radius:12px;padding:3rem 2rem;text-align:center;background:#fafafa;transition:all .3s ease;margin-bottom:2rem}
.upload-zone:hover{border-color:#ff5722;background:#fff7f3}
.upload-zone.dragover{border-color:#ff5722;background:#fff;transform:scale(1.02)}
.upload-icon{font-size:3rem;margin-bottom:1rem;color:#999}
.upload-zone:hover .upload-icon{color:#ff5722}
.file-input-wrapper{position:relative;display:inline-block}
.file-input-wrapper input[type=file]{position:absolute;opacity:0;width:100%;height:100%;cursor:pointer}
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;font-size:.9rem;font-weight:600;text-decoration:none;border-radius:8px;transition:all .3s ease;border:none;cursor:pointer}
.btn-primary{background:linear-gradient(135deg,#ff5722 0%,#f4511e 100%);color:#fff;box-shadow:0 4px 12px rgba(255,87,34,.3)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(255,87,34,.4)}
.btn-outline{background:#fff;color:#0c5343;border:2px solid #0c5343}
.btn-outline:hover{background:#0c5343;color:#fff}
.btn-secondary{background:#f5f5f5;color:#666;border:2px solid #ddd}
.btn-secondary:hover{background:#e0e0e0}
.instructions{background:#e3f2fd;border-left:4px solid#2196f3;padding:1.5rem;border-radius:8px;margin-bottom:2rem}
.instructions h3{margin:0 0 1rem;color:#1976d2;font-size:1.1rem;font-weight:700}
.instructions ul{margin:0;padding-left:1.5rem}
.instructions li{margin-bottom:.5rem;color:#333;line-height:1.6}
.sample-table{width:100%;border-collapse:collapse;margin-top:1rem;font-size:.85rem;border:2px solid #0c5343}
.sample-table th,.sample-table td{border:1px solid #0c5343;padding:.5rem;text-align:left}
.sample-table th{background:linear-gradient(135deg,#0c5343 0%,#0a4636 100%);color:#fff;font-weight:700;font-size:.75rem}
.sample-table td{background:#fff}
.note{background:#fff3cd;border-left:4px solid #ffc107;padding:1rem;border-radius:8px;margin-top:1.5rem}
.note strong{color:#856404}
.warning{background:#ffebee;border-left:4px solid #f44336;padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.warning strong{color:#c62828}
</style>

<div style="max-width:100%;padding:1rem">
    <div style="margin-bottom:1rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Final Invoices', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    </div>

    <div class="upload-container">
        <div class="upload-header">
            <h2>📤 Bulk Upload Final Invoices (FVP)</h2>
            <p>Import multiple final invoices with landed quantities from CSV file</p>
        </div>

        <div class="upload-body">
            <div class="instructions" style="background:#d4edda;border-left-color:#28a745">
                <h3 style="color:#155724">✨ Ready-to-Use Template with Real Data</h3>
                <p style="margin:0 0 1rem;color:#155724;line-height:1.6">
                    Click "Download Full Template" to get a CSV file pre-filled with <strong>all Fresh Invoices awaiting Final Invoices</strong>. 
                    The table below shows the first 10 invoices as a preview of what you'll get in the template.
                </p>
                <div style="background:#fff;padding:1rem;border-radius:6px;border:1px solid #c3e6cb">
                    <strong style="color:#155724">Quick Steps:</strong>
                    <ol style="margin:.5rem 0 0 0;padding-left:1.5rem;color:#155724">
                        <li>Download the template (contains real invoices from your system)</li>
                        <li>Update "Landed Quantity" column with actual landed amounts</li>
                        <li>Add notes if needed (variance calculations will be automatic)</li>
                        <li>Upload the CSV - you'll see a validation preview before import</li>
                        <li>Confirm import after reviewing the preview</li>
                    </ol>
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Validation:</strong> All Original Invoice Numbers will be validated before import. You'll see a preview screen showing matched Fresh Invoices and calculated variances before final confirmation.
            </div>

            <!-- Pending Fresh Invoices Table -->
            <div style="margin-bottom:2rem">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                    <div>
                        <h3 style="margin:0;font-size:1rem;font-weight:700;color:#333">
                            📋 Fresh Invoices Awaiting Final Invoices
                            <?php if (isset($pendingInvoices) && $pendingInvoices->count() > 0): ?>
                                <span style="background:#28a745;color:#fff;padding:.25rem .75rem;border-radius:20px;font-size:.85rem;margin-left:.5rem">
                                    <?= $pendingInvoices->count() ?> Pending
                                </span>
                            <?php endif; ?>
                        </h3>
                        <p style="margin:.25rem 0 0 0;color:#666;font-size:.85rem">
                            These are actual Fresh Invoices from your system that need Final Invoices. Download the template to get the complete list.
                        </p>
                    </div>
                    <?= $this->Html->link('⬇️ Download Full Template', ['action' => 'downloadTemplate'], ['class' => 'btn btn-secondary', 'style' => 'background:#28a745;color:#fff;border-color:#28a745']) ?>
                </div>
                <div style="overflow-x:auto">
                    <?php if (isset($pendingInvoices) && $pendingInvoices->count() > 0): ?>
                        <table class="sample-table">
                            <thead>
                                <tr>
                                    <th>Original Invoice Number</th>
                                    <th>Current Quantity (MT)</th>
                                    <th>Vessel Name</th>
                                    <th>BL Number</th>
                                    <th>Action Needed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingInvoices as $invoice): ?>
                                    <tr>
                                        <td><strong><?= h($invoice->invoice_number) ?></strong></td>
                                        <td><?= h(number_format($invoice->quantity, 3)) ?> MT</td>
                                        <td>
                                            <?php 
                                                $vesselName = 'Vessel: ' . ($invoice->vessel->name ?? 'Unknown');
                                                echo h($vesselName);
                                            ?>
                                        </td>
                                        <td><?= h($invoice->bl_number ?? 'N/A') ?></td>
                                        <td style="color:#ff5722;font-weight:600">
                                            <i class="fas fa-clock"></i> Update landed quantity
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p style="text-align:center;margin-top:1rem;color:#666;font-size:.85rem">
                            <i class="fas fa-info-circle"></i> Showing first 10 invoices. Download template for complete list (up to 50 most recent).
                        </p>
                    <?php else: ?>
                        <div style="background:#d4edda;border:2px solid #28a745;border-radius:8px;padding:2rem;text-align:center">
                            <div style="font-size:3rem;margin-bottom:1rem">✅</div>
                            <h3 style="color:#155724;margin:0 0 .5rem">All Caught Up!</h3>
                            <p style="color:#155724;margin:0">
                                All Fresh Invoices already have Final Invoices. Great work!
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upload Form -->
            <?= $this->Form->create(null, ['type' => 'file', 'id' => 'uploadForm']) ?>
            
            <div class="upload-zone" id="dropZone">
                <div class="upload-icon">📁</div>
                <h3 style="margin:0 0 .5rem;font-size:1.2rem;font-weight:700;color:#333">Drop CSV file here or click to browse</h3>
                <p style="margin:0 0 1.5rem;color:#666;font-size:.9rem">Supports CSV files up to 10MB</p>
                
                <div class="file-input-wrapper">
                    <?= $this->Form->control('file', [
                        'type' => 'file',
                        'label' => false,
                        'accept' => '.csv',
                        'id' => 'fileInput',
                        'required' => true
                    ]) ?>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                        📎 Select CSV File
                    </button>
                </div>
                
                <div id="fileInfo" style="margin-top:1rem;display:none;font-weight:600;color:#0c5343"></div>
            </div>

            <div style="display:flex;gap:1rem;justify-content:center">
                <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
                <?= $this->Form->button('🚀 Upload and Process Final Invoices', [
                    'class' => 'btn btn-primary',
                    'style' => 'min-width:250px'
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

            <div class="note">
                <strong>💡 Pro Tips:</strong>
                <ul style="margin:.5rem 0 0;padding-left:1.5rem">
                    <li>Original Invoice Number must exactly match an existing Fresh Invoice</li>
                    <li>Landed Quantity should be the actual received quantity</li>
                    <li>System will auto-calculate variance (positive or negative)</li>
                    <li>FVP invoice numbers will be auto-generated</li>
                    <li>Price and payment % will be copied from the Fresh Invoice</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// File input handling
const fileInput = document.getElementById('fileInput');
const fileInfo = document.getElementById('fileInfo');
const dropZone = document.getElementById('dropZone');

if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            fileInfo.textContent = `✓ Selected: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
            fileInfo.style.display = 'block';
        }
    });
}

// Drag and drop
if (dropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
    });

    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }, false);
}
</script>
