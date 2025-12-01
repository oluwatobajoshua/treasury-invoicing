<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\I18n\FrozenTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * DataExport Controller
 *
 * Export data for compliance and reporting purposes
 */
class DataExportController extends AppAdminController
{
    /**
     * Index method - Export options
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Data Export & Compliance');
    }

    /**
     * Export audit logs
     *
     * @return \Cake\Http\Response
     */
    public function auditLogs()
    {
        $this->loadModel('AuditLogs');
        
        $startDate = $this->request->getQuery('start_date');
        $endDate = $this->request->getQuery('end_date');
        
        $query = $this->AuditLogs->find()
            ->contain(['Users'])
            ->order(['AuditLogs.created' => 'DESC']);
        
        if ($startDate) {
            $query->where(['AuditLogs.created >=' => $startDate]);
        }
        if ($endDate) {
            $query->where(['AuditLogs.created <=' => $endDate]);
        }
        
        $auditLogs = $query->all();
        
        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Date/Time');
        $sheet->setCellValue('C1', 'User');
        $sheet->setCellValue('D1', 'Action');
        $sheet->setCellValue('E1', 'Model');
        $sheet->setCellValue('F1', 'Record ID');
        $sheet->setCellValue('G1', 'IP Address');
        $sheet->setCellValue('H1', 'Old Values');
        $sheet->setCellValue('I1', 'New Values');
        
        // Style headers
        $headerStyle = $sheet->getStyle('A1:I1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $headerStyle->getFill()->getStartColor()->setRGB('0c5343');
        $headerStyle->getFont()->getColor()->setRGB('FFFFFF');
        
        // Data
        $row = 2;
        foreach ($auditLogs as $log) {
            $sheet->setCellValue('A' . $row, $log->id);
            $sheet->setCellValue('B' . $row, $log->created->format('Y-m-d H:i:s'));
            $sheet->setCellValue('C' . $row, $log->has('user') ? $log->user->name : 'System');
            $sheet->setCellValue('D' . $row, $log->action);
            $sheet->setCellValue('E' . $row, $log->model);
            $sheet->setCellValue('F' . $row, $log->record_id);
            $sheet->setCellValue('G' . $row, $log->ip_address);
            $sheet->setCellValue('H' . $row, $log->old_values);
            $sheet->setCellValue('I' . $row, $log->new_values);
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generate filename
        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Write to file
        $writer = new Xlsx($spreadsheet);
        $tempFile = TMP . $filename;
        $writer->save($tempFile);
        
        // Send file
        $this->response = $this->response->withFile(
            $tempFile,
            ['download' => true, 'name' => $filename]
        );
        
        return $this->response;
    }

    /**
     * Export all invoices
     *
     * @return \Cake\Http\Response
     */
    public function allInvoices()
    {
        $this->loadModel('FreshInvoices');
        $this->loadModel('FinalInvoices');
        $this->loadModel('SalesInvoices');
        $this->loadModel('SustainabilityInvoices');
        
        $spreadsheet = new Spreadsheet();
        
        // Fresh Invoices Sheet
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Fresh Invoices');
        $this->exportFreshInvoices($sheet1);
        
        // Final Invoices Sheet
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Final Invoices');
        $this->exportFinalInvoices($sheet2);
        
        // Sales Invoices Sheet
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Sales Invoices');
        $this->exportSalesInvoices($sheet3);
        
        // Sustainability Invoices Sheet
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Sustainability Invoices');
        $this->exportSustainabilityInvoices($sheet4);
        
        // Generate filename
        $filename = 'all_invoices_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Write to file
        $writer = new Xlsx($spreadsheet);
        $tempFile = TMP . $filename;
        $writer->save($tempFile);
        
        // Send file
        $this->response = $this->response->withFile(
            $tempFile,
            ['download' => true, 'name' => $filename]
        );
        
        return $this->response;
    }

    /**
     * Export fresh invoices to sheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The sheet
     * @return void
     */
    protected function exportFreshInvoices($sheet)
    {
        $invoices = $this->FreshInvoices->find()
            ->contain(['Clients', 'Products', 'Contracts', 'Vessels'])
            ->order(['FreshInvoices.created' => 'DESC'])
            ->all();
        
        // Headers
        $headers = ['ID', 'Invoice#', 'Client', 'Product', 'Contract', 'Vessel', 'BL Number', 'Quantity', 'Total Value', 'Status', 'Date'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Style headers
        $headerStyle = $sheet->getStyle('A1:K1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $headerStyle->getFill()->getStartColor()->setRGB('0c5343');
        $headerStyle->getFont()->getColor()->setRGB('FFFFFF');
        
        // Data
        $row = 2;
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('A' . $row, $invoice->id);
            $sheet->setCellValue('B' . $row, $invoice->invoice_number);
            $sheet->setCellValue('C' . $row, $invoice->has('client') ? $invoice->client->name : '');
            $sheet->setCellValue('D' . $row, $invoice->has('product') ? $invoice->product->name : '');
            $sheet->setCellValue('E' . $row, $invoice->has('contract') ? $invoice->contract->contract_id : '');
            $sheet->setCellValue('F' . $row, $invoice->has('vessel') ? $invoice->vessel->name : '');
            $sheet->setCellValue('G' . $row, $invoice->bl_number);
            $sheet->setCellValue('H' . $row, $invoice->quantity);
            $sheet->setCellValue('I' . $row, $invoice->total_value);
            $sheet->setCellValue('J' . $row, $invoice->status);
            $sheet->setCellValue('K' . $row, $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '');
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Export final invoices to sheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The sheet
     * @return void
     */
    protected function exportFinalInvoices($sheet)
    {
        $invoices = $this->FinalInvoices->find()
            ->contain(['FreshInvoices' => ['Clients', 'Vessels']])
            ->order(['FinalInvoices.created' => 'DESC'])
            ->all();
        
        // Headers
        $headers = ['ID', 'Invoice#', 'Fresh Invoice', 'Client', 'Vessel', 'BL Number', 'Landed Qty', 'Unit Price', 'Total Value', 'Status', 'Date'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Style headers
        $headerStyle = $sheet->getStyle('A1:K1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $headerStyle->getFill()->getStartColor()->setRGB('0c5343');
        $headerStyle->getFont()->getColor()->setRGB('FFFFFF');
        
        // Data
        $row = 2;
        foreach ($invoices as $invoice) {
            $fresh = $invoice->fresh_invoice;
            $sheet->setCellValue('A' . $row, $invoice->id);
            $sheet->setCellValue('B' . $row, $invoice->invoice_number);
            $sheet->setCellValue('C' . $row, $fresh ? $fresh->invoice_number : '');
            $sheet->setCellValue('D' . $row, $fresh && $fresh->has('client') ? $fresh->client->name : '');
            $sheet->setCellValue('E' . $row, $fresh && $fresh->has('vessel') ? $fresh->vessel->name : '');
            $sheet->setCellValue('F' . $row, $invoice->bl_number);
            $sheet->setCellValue('G' . $row, $invoice->landed_quantity);
            $sheet->setCellValue('H' . $row, $invoice->unit_price);
            $sheet->setCellValue('I' . $row, $invoice->landed_quantity * $invoice->unit_price);
            $sheet->setCellValue('J' . $row, $invoice->status);
            $sheet->setCellValue('K' . $row, $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '');
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Export sales invoices to sheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The sheet
     * @return void
     */
    protected function exportSalesInvoices($sheet)
    {
        $invoices = $this->SalesInvoices->find()
            ->contain(['Clients', 'Banks'])
            ->order(['SalesInvoices.created' => 'DESC'])
            ->all();
        
        // Headers
        $headers = ['ID', 'Invoice#', 'Client', 'Description', 'Quantity', 'Unit Price', 'Total Value', 'Bank', 'Status', 'Date'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Style headers
        $headerStyle = $sheet->getStyle('A1:J1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $headerStyle->getFill()->getStartColor()->setRGB('0c5343');
        $headerStyle->getFont()->getColor()->setRGB('FFFFFF');
        
        // Data
        $row = 2;
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('A' . $row, $invoice->id);
            $sheet->setCellValue('B' . $row, $invoice->invoice_number);
            $sheet->setCellValue('C' . $row, $invoice->has('client') ? $invoice->client->name : '');
            $sheet->setCellValue('D' . $row, $invoice->description);
            $sheet->setCellValue('E' . $row, $invoice->quantity);
            $sheet->setCellValue('F' . $row, $invoice->unit_price);
            $sheet->setCellValue('G' . $row, $invoice->total_value);
            $sheet->setCellValue('H' . $row, $invoice->has('bank') ? $invoice->bank->bank_name : '');
            $sheet->setCellValue('I' . $row, $invoice->status);
            $sheet->setCellValue('J' . $row, $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '');
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Export sustainability invoices to sheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The sheet
     * @return void
     */
    protected function exportSustainabilityInvoices($sheet)
    {
        $invoices = $this->SustainabilityInvoices->find()
            ->contain(['Clients', 'Banks'])
            ->order(['SustainabilityInvoices.created' => 'DESC'])
            ->all();
        
        // Headers
        $headers = ['ID', 'Invoice#', 'Client', 'Description', 'Investment', 'Differential', 'Total Value', 'Bank', 'Status', 'Date'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Style headers
        $headerStyle = $sheet->getStyle('A1:J1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $headerStyle->getFill()->getStartColor()->setRGB('0c5343');
        $headerStyle->getFont()->getColor()->setRGB('FFFFFF');
        
        // Data
        $row = 2;
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('A' . $row, $invoice->id);
            $sheet->setCellValue('B' . $row, $invoice->invoice_number);
            $sheet->setCellValue('C' . $row, $invoice->has('client') ? $invoice->client->name : '');
            $sheet->setCellValue('D' . $row, $invoice->description);
            $sheet->setCellValue('E' . $row, $invoice->investment_amount);
            $sheet->setCellValue('F' . $row, $invoice->differential_amount);
            $sheet->setCellValue('G' . $row, $invoice->total_value);
            $sheet->setCellValue('H' . $row, $invoice->has('bank') ? $invoice->bank->bank_name : '');
            $sheet->setCellValue('I' . $row, $invoice->status);
            $sheet->setCellValue('J' . $row, $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '');
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
