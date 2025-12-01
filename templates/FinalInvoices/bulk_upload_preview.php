<?php
/**
 * @var \App\View\AppView $this
 * @var array $previewData
 * @var array $validationResults
 * @var bool $hasErrors
 */
$this->assign('title', __('Preview Final Invoices Import'));
?>
<div class="finalInvoices form content">
    <style>
        .preview-header {
            background: linear-gradient(135deg, #004085 0%, #007bff 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .preview-header.has-errors {
            background: linear-gradient(135deg, #721c24 0%, #dc3545 100%);
        }
        .preview-container {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .preview-actions {
            padding: 2rem;
            background: #f8f9fa;
            border-top: 3px solid #007bff;
        }
        .preview-actions.has-errors {
            border-top: 3px solid #dc3545;
        }
        .preview-table-wrapper {
            overflow-x: auto;
            max-height: 600px;
            overflow-y: auto;
        }
        .preview-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .preview-table thead {
            position: sticky;
            top: 0;
            background: #495057;
            color: white;
            z-index: 10;
        }
        .preview-table th {
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #007bff;
            white-space: nowrap;
        }
        .preview-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }
        .preview-table tbody tr:hover {
            background: #f8f9fa;
        }
        .preview-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .preview-table tbody tr:nth-child(even):hover {
            background: #e9ecef;
        }
        .preview-table tbody tr.error-row {
            background: #f8d7da !important;
            border-left: 4px solid #dc3545;
        }
        .preview-table tbody tr.error-row:hover {
            background: #f5c6cb !important;
        }
        .preview-table tbody tr.success-row {
            background: #d4edda !important;
            border-left: 4px solid #28a745;
        }
        .preview-table tbody tr.success-row:hover {
            background: #c3e6cb !important;
        }
        .row-number {
            font-weight: bold;
            color: #6c757d;
            background: #e9ecef !important;
            text-align: center;
        }
        .empty-cell {
            color: #6c757d;
            font-style: italic;
        }
        .error-badge {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .success-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .stat-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1rem 0;
        }
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin: 1rem 0;
        }
        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 1rem;
            margin: 1rem 0;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 1rem;
            margin: 1rem 0;
        }
        .btn-confirm {
            background: linear-gradient(135deg, #004085 0%, #007bff 100%);
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .btn-confirm:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            margin-left: 1rem;
        }
        .btn-cancel:hover {
            background: #5a6268;
        }
        .variance-positive {
            color: #28a745;
            font-weight: 600;
        }
        .variance-negative {
            color: #dc3545;
            font-weight: 600;
        }
        .matched-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
    </style>

    <div class="preview-header <?= $hasErrors ? 'has-errors' : '' ?>">
        <h2><i class="fas fa-eye"></i> Preview Final Invoices Import</h2>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">
            <?php if ($hasErrors): ?>
                ⚠️ Validation errors found! Fix issues before importing.
            <?php else: ?>
                ✓ All Fresh Invoices validated successfully!
            <?php endif; ?>
        </p>
        <div style="margin-top: 1rem;">
            <span class="stat-badge">
                <i class="fas fa-check-circle"></i> <strong><?= $validationResults['success_count'] ?></strong> valid
            </span>
            <?php if ($validationResults['error_count'] > 0): ?>
                <span class="stat-badge" style="background: rgba(220, 53, 69, 0.3);">
                    <i class="fas fa-times-circle"></i> <strong><?= $validationResults['error_count'] ?></strong> errors
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="preview-container">
        <?php if ($hasErrors): ?>
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i> <strong>Cannot Import:</strong>
                The following rows have errors. Fresh Invoice numbers must exist in the system before creating Final Invoices.
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                    <?php foreach ($validationResults['errors'] as $index => $error): ?>
                        <li><strong>Row <?= $index + 1 ?>:</strong> <?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="success-box">
                <i class="fas fa-check-circle"></i> <strong>Ready to Import:</strong>
                All Fresh Invoices found! Review the variance calculations below.
            </div>
        <?php endif; ?>

        <div class="preview-table-wrapper">
            <table class="preview-table">
                <thead>
                    <tr>
                        <th class="row-number">#</th>
                        <th>Status</th>
                        <th>Original Invoice Number</th>
                        <th>Client</th>
                        <th>Product</th>
                        <th>Fresh Qty</th>
                        <th>Landed Qty</th>
                        <th>Variance</th>
                        <th>Unit Price</th>
                        <th>Vessel Name</th>
                        <th>BL Number</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($previewData as $index => $row): ?>
                        <?php 
                            $hasError = isset($validationResults['errors'][$index]);
                            $matched = $validationResults['matched'][$index] ?? null;
                            $rowClass = $hasError ? 'error-row' : 'success-row';
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td class="row-number"><?= $index + 1 ?></td>
                            <td>
                                <?php if ($hasError): ?>
                                    <span class="error-badge"><i class="fas fa-times"></i> ERROR</span>
                                <?php else: ?>
                                    <span class="success-badge"><i class="fas fa-check"></i> VALID</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= h($row['Original Invoice Number']) ?></strong>
                                <?php if ($hasError): ?>
                                    <div class="matched-info" style="color: #dc3545;">
                                        <i class="fas fa-exclamation-circle"></i> <?= h($validationResults['errors'][$index]) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($matched): ?>
                                    <?= h($matched['client_name']) ?>
                                <?php else: ?>
                                    <span class="empty-cell">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($matched): ?>
                                    <?= h($matched['product_name']) ?>
                                <?php else: ?>
                                    <span class="empty-cell">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?php if ($matched): ?>
                                    <?= h(number_format($matched['original_quantity'], 2)) ?> MT
                                <?php else: ?>
                                    <span class="empty-cell">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?= h(number_format($row['Landed Quantity'], 2)) ?> MT
                            </td>
                            <td style="text-align: right;">
                                <?php if ($matched): ?>
                                    <?php 
                                        $variance = $matched['variance'];
                                        $varianceClass = $variance >= 0 ? 'variance-positive' : 'variance-negative';
                                    ?>
                                    <span class="<?= $varianceClass ?>">
                                        <?= $variance >= 0 ? '+' : '' ?><?= h(number_format($variance, 2)) ?> MT
                                    </span>
                                <?php else: ?>
                                    <span class="empty-cell">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?php if ($matched): ?>
                                    $<?= h(number_format($matched['unit_price'], 2)) ?>
                                <?php else: ?>
                                    <span class="empty-cell">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= h($row['Vessel Name']) ?></td>
                            <td><?= h($row['BL Number']) ?></td>
                            <td><?= h($row['Notes'] ?? '') ?: '<span class="empty-cell">No notes</span>' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="preview-actions <?= $hasErrors ? 'has-errors' : '' ?>">
            <?= $this->Form->create(null, ['url' => ['action' => 'bulkUpload']]) ?>
            <?= $this->Form->hidden('confirm_import', ['value' => '1']) ?>
            <?= $this->Form->hidden('preview_data', ['value' => json_encode($previewData)]) ?>
            
            <div style="text-align: center;">
                <?php if ($hasErrors): ?>
                    <button type="submit" class="btn-confirm" disabled>
                        <i class="fas fa-ban"></i> Cannot Import - Fix Errors First
                    </button>
                    
                    <p style="margin-top: 1rem; color: #dc3545; font-weight: 600;">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Please correct the errors above. Make sure all Original Invoice Numbers exist as Fresh Invoices.
                    </p>
                <?php else: ?>
                    <button type="submit" class="btn-confirm">
                        <i class="fas fa-check-circle"></i> Confirm & Import <?= $validationResults['success_count'] ?> Final Invoices
                    </button>
                    
                    <p style="margin-top: 1rem; color: #28a745; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> All validations passed! Ready to import.
                    </p>
                <?php endif; ?>
                
                <?= $this->Html->link(
                    '<i class="fas fa-arrow-left"></i> Go Back & Upload Different File',
                    ['action' => 'bulkUpload'],
                    ['class' => 'btn-cancel', 'escape' => false]
                ) ?>
            </div>
            
            <?= $this->Form->end() ?>
            
            <?php if (!$hasErrors): ?>
                <p style="text-align: center; margin-top: 1rem; color: #6c757d; font-size: 0.9rem;">
                    <i class="fas fa-magic"></i> FVP numbers will be auto-generated for each final invoice
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
