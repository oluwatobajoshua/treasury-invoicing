<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Bulk Upload Fresh Invoices');
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
</style>

<div style="max-width:100%;padding:1rem">
    <div style="margin-bottom:1rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Fresh Invoices', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    </div>

    <div class="upload-container">
        <div class="upload-header">
            <h2>📤 Bulk Upload Fresh Invoices</h2>
            <p>Import multiple invoices from CSV file at once</p>
        </div>

        <div class="upload-body">
            <div class="instructions">
                <h3>📋 Instructions</h3>
                <ul>
                    <li><strong>Step 1:</strong> Download the sample CSV template below</li>
                    <li><strong>Step 2:</strong> Fill in your invoice data following the exact column headers</li>
                    <li><strong>Step 3:</strong> Save as CSV file and upload below</li>
                    <li><strong>Note:</strong> Client, Product, and Vessel will be auto-created if they don't exist</li>
                    <li><strong>Required columns:</strong> INVOICE NUMBER, Client Name, BL Number, Quantity(MT), Unit Price</li>
                </ul>
            </div>

            <!-- Sample Template Table -->
            <div style="margin-bottom:2rem">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                    <h3 style="margin:0;font-size:1rem;font-weight:700;color:#333">Sample Template Format</h3>
                    <?= $this->Html->link('⬇️ Download CSV Template', ['action' => 'downloadTemplate'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <div style="overflow-x:auto">
                    <table class="sample-table">
                        <thead>
                            <tr>
                                <th>INVOICE NUMBER</th>
                                <th>Client Name</th>
                                <th>Product Name</th>
                                <th>Contract ID</th>
                                <th>Vessel Name</th>
                                <th>BL Number</th>
                                <th>Quantity(MT)</th>
                                <th>Unit Price</th>
                                <th>Payment %</th>
                                <th>SGC Account ID</th>
                                <th>Notes</th>
                                <th>BULK/BAG</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0155</td>
                                <td>SFI/CARGILL</td>
                                <td>Cocoa</td>
                                <td>2025-SI 1B/QP 136484</td>
                                <td>Netherlands</td>
                                <td>LOS37115</td>
                                <td>262.430</td>
                                <td>6292.35</td>
                                <td>98%</td>
                                <td>1100533</td>
                                <td></td>
                                <td>16 BULK</td>
                            </tr>
                            <tr>
                                <td>0156</td>
                                <td>SFI/CARGILL</td>
                                <td>Cocoa</td>
                                <td>2025-SI 1B/QP 136484</td>
                                <td>Netherlands</td>
                                <td>LOS37113</td>
                                <td>260.320</td>
                                <td>6292.35</td>
                                <td>98%</td>
                                <td>1100533</td>
                                <td></td>
                                <td>16 BULK</td>
                            </tr>
                        </tbody>
                    </table>
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
                <?= $this->Form->button('🚀 Upload and Process Invoices', [
                    'class' => 'btn btn-primary',
                    'style' => 'min-width:250px'
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

            <div class="note">
                <strong>💡 Pro Tips:</strong>
                <ul style="margin:.5rem 0 0;padding-left:1.5rem">
                    <li>Ensure all invoice numbers are unique</li>
                    <li>Use the exact SGC Account ID from your system</li>
                    <li>Payment % should be a number (e.g., 98 for 98%)</li>
                    <li>Quantity and Unit Price should be numeric values</li>
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
