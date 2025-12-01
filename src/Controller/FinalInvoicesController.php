<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;

/**
 * FinalInvoices Controller
 *
 * @property \App\Model\Table\FinalInvoicesTable $FinalInvoices
 * @method \App\Model\Entity\FinalInvoice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FinalInvoicesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Server-side pagination and sorting
        $this->paginate = [
            'limit' => 25,
            'order' => ['FinalInvoices.created' => 'DESC'],
            'contain' => ['FreshInvoices' => ['Vessels']],
            // Guard sortable fields
            'sortableFields' => [
                'invoice_number',
                'original_invoice_number',
                'vessel_name',
                'bl_number',
                'landed_quantity',
                'quantity_variance',
                'total_value',
                'status',
                'created'
            ],
        ];

        $query = $this->FinalInvoices->find('all');
        $finalInvoices = $this->paginate($query);

        // KPI metrics for index header
        $all = $this->FinalInvoices->find();
        $kpis = [
            'total' => (clone $all)->count(),
            'draft' => (clone $all)->where(['status' => 'draft'])->count(),
            'pending' => (clone $all)->where(['status' => 'pending_treasurer_approval'])->count(),
            'approved' => (clone $all)->where(['status' => 'approved'])->count(),
            'rejected' => (clone $all)->where(['status' => 'rejected'])->count(),
            'sent_to_sales' => (clone $all)->where(['status' => 'sent_to_sales'])->count(),
            'variance_sum' => (clone $all)->select(['sum' => $all->func()->sum('quantity_variance')])->first()->sum ?? 0,
            'total_value_sum' => (clone $all)->select(['sum' => $all->func()->sum('total_value')])->first()->sum ?? 0,
        ];

        $this->set(compact('finalInvoices', 'kpis'));
    }

    /**
     * View method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $finalInvoice = $this->FinalInvoices->get($id, [
            'contain' => [
                'FreshInvoices' => ['Clients', 'Products', 'Contracts', 'Vessels'],
                'SgcAccounts'
            ],
        ]);

        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        
        $this->set(compact('finalInvoice', 'settings'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $finalInvoice = $this->FinalInvoices->newEmptyEntity();
        if ($this->request->is('post')) {
            $finalInvoice = $this->FinalInvoices->patchEntity($finalInvoice, $this->request->getData());
            
            // Set default values
            $finalInvoice->status = 'draft';
            $finalInvoice->treasurer_approval_status = 'pending';
            
            if ($this->FinalInvoices->save($finalInvoice)) {
                $this->Flash->success(__('The final invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The final invoice could not be saved. Please, try again.'));
        }
        
        // Get eligible fresh invoices (approved or sent_to_export)
        $freshInvoices = $this->FinalInvoices->FreshInvoices
            ->find('list', [
                'keyField' => 'id',
                'valueField' => function ($entity) {
                    $client = $entity->client->name ?? 'Unknown Client';
                    return sprintf('%s - %s', $entity->invoice_number, $client);
                }
            ])
            ->contain(['Clients'])
            ->where(['FreshInvoices.status IN' => ['approved', 'sent_to_export']])
            ->order(['FreshInvoices.created' => 'DESC'])
            ->all();
        $hasEligibleFreshInvoices = count($freshInvoices) > 0;
        
        $sgcAccounts = $this->FinalInvoices->SgcAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return $entity->account_id . ' - ' . $entity->account_name . ' (' . $entity->currency . ')';
            },
            'limit' => 200
        ])->all();

        // Load company settings for header/logo on form
        $settings = $this->fetchTable('Settings')->find()->first();
        
    $this->set(compact('finalInvoice', 'freshInvoices', 'sgcAccounts', 'settings', 'hasEligibleFreshInvoices'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $finalInvoice = $this->FinalInvoices->get($id, [
            'contain' => [],
        ]);
        
        // Only allow editing if status is draft
        if ($finalInvoice->status !== 'draft') {
            $this->Flash->error(__('Only draft final invoices can be edited.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $finalInvoice = $this->FinalInvoices->patchEntity($finalInvoice, $this->request->getData());
            if ($this->FinalInvoices->save($finalInvoice)) {
                $this->Flash->success(__('The final invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The final invoice could not be saved. Please, try again.'));
        }
        
        // Get eligible fresh invoices (approved or sent_to_export)
        $freshInvoices = $this->FinalInvoices->FreshInvoices
            ->find('list', [
                'keyField' => 'id',
                'valueField' => function ($entity) {
                    $client = $entity->client->name ?? 'Unknown Client';
                    return sprintf('%s - %s', $entity->invoice_number, $client);
                }
            ])
            ->contain(['Clients'])
            ->where(['FreshInvoices.status IN' => ['approved', 'sent_to_export']])
            ->order(['FreshInvoices.created' => 'DESC'])
            ->all();
        
        $sgcAccounts = $this->FinalInvoices->SgcAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return $entity->account_id . ' - ' . $entity->account_name . ' (' . $entity->currency . ')';
            },
            'limit' => 200
        ])->all();
        
        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        
        $this->set(compact('finalInvoice', 'freshInvoices', 'sgcAccounts', 'settings'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $finalInvoice = $this->FinalInvoices->get($id);
        
        // Only allow deletion if status is draft
        if ($finalInvoice->status !== 'draft') {
            $this->Flash->error(__('Only draft final invoices can be deleted.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->FinalInvoices->delete($finalInvoice)) {
            $this->Flash->success(__('The final invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The final invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Submit for approval method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function submitForApproval($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $finalInvoice = $this->FinalInvoices->get($id);
        
        if ($finalInvoice->status !== 'draft') {
            $this->Flash->error(__('Only draft final invoices can be submitted for approval.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $finalInvoice->status = 'pending_treasurer_approval';
        if ($this->FinalInvoices->save($finalInvoice)) {
            // Notify approvers using settings
            $accessToken = $this->request->getSession()->read('Auth.AccessToken');
            $notifier = new \App\Service\ApprovalNotificationService($accessToken);
            $notifier->notifyFinal((int)$finalInvoice->id, 'pending_approval', ['attachPdf' => true]);
            $this->Flash->success(__('The final invoice has been submitted to the Treasurer for approval.'));
        } else {
            $this->Flash->error(__('Could not submit the final invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Treasurer approve method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function treasurerApprove($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $finalInvoice = $this->FinalInvoices->get($id);
        
        $finalInvoice->treasurer_approval_status = 'approved';
        $finalInvoice->treasurer_approval_date = new \DateTime();
        $finalInvoice->status = 'approved';
        
        if ($this->request->getData('treasurer_comments')) {
            $finalInvoice->treasurer_comments = $this->request->getData('treasurer_comments');
        }
        
        if ($this->FinalInvoices->save($finalInvoice)) {
            $accessToken = $this->request->getSession()->read('Auth.AccessToken');
            $notifier = new \App\Service\ApprovalNotificationService($accessToken);
            $notifier->notifyFinal((int)$finalInvoice->id, 'approved', ['attachPdf' => true]);
            $this->Flash->success(__('The final invoice has been approved.'));
        } else {
            $this->Flash->error(__('Could not approve the final invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Treasurer reject method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function treasurerReject($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $finalInvoice = $this->FinalInvoices->get($id);
        
        $finalInvoice->treasurer_approval_status = 'rejected';
        $finalInvoice->treasurer_approval_date = new \DateTime();
        $finalInvoice->status = 'rejected';
        $finalInvoice->treasurer_comments = $this->request->getData('treasurer_comments');
        
        if ($this->FinalInvoices->save($finalInvoice)) {
            $accessToken = $this->request->getSession()->read('Auth.AccessToken');
            $notifier = new \App\Service\ApprovalNotificationService($accessToken);
            $notifier->notifyFinal((int)$finalInvoice->id, 'rejected', ['attachPdf' => true]);
            $this->Flash->success(__('The final invoice has been rejected.'));
        } else {
            $this->Flash->error(__('Could not reject the final invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Send to sales team method
     *
     * @param string|null $id Final Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function sendToSales($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $finalInvoice = $this->FinalInvoices->get($id);
        
        if ($finalInvoice->status !== 'approved') {
            $this->Flash->error(__('Only approved final invoices can be sent to the sales team.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $finalInvoice->status = 'sent_to_sales';
        $finalInvoice->sent_to_sales_date = new \DateTime();
        
        if ($this->FinalInvoices->save($finalInvoice)) {
            $this->Flash->success(__('The final invoice has been sent to the sales team.'));
        } else {
            $this->Flash->error(__('Could not send the final invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Get fresh invoice details by ID (AJAX)
     *
     * @return \Cake\Http\Response|null|void JSON response
     */
    public function getFreshInvoiceDetails()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');
        
        $freshInvoiceId = $this->request->getQuery('fresh_invoice_id');
        if (!$freshInvoiceId) {
            return $this->response->withStringBody(json_encode(['error' => 'Fresh Invoice ID is required']));
        }
        
        $freshInvoice = $this->FinalInvoices->FreshInvoices->find()
            ->contain(['Clients', 'Products', 'SgcAccounts', 'Vessels'])
            ->where(['FreshInvoices.id' => $freshInvoiceId])
            ->first();
        
        if (!$freshInvoice) {
            return $this->response->withStringBody(json_encode(['error' => 'Fresh Invoice not found']));
        }
        
        $vesselName = $freshInvoice->vessel->name ?? $freshInvoice->vessel_name ?? '';
        $payload = [
            'invoice_number' => $freshInvoice->invoice_number,
            'vessel_name' => $vesselName,
            'bl_number' => $freshInvoice->bl_number,
            'unit_price' => $freshInvoice->unit_price,
            'payment_percentage' => $freshInvoice->payment_percentage,
            'sgc_account_id' => $freshInvoice->sgc_account_id,
            'quantity' => $freshInvoice->quantity,
            'bulk_or_bag' => $freshInvoice->bulk_or_bag ?? null,
            'total_value' => $freshInvoice->total_value ?? 0, // Add total_value for amount_paid suggestion
        ];

        return $this->response->withStringBody(json_encode($payload));
    }

    /**
     * Bulk upload method for importing multiple final invoices from CSV
     *
     * @return \Cake\Http\Response|null|void Renders view or redirects on successful upload
     */
    public function bulkUpload()
    {
        if ($this->request->is('post')) {
            $file = $this->request->getData('file');
            
            // Check if this is a confirmation submit (after preview)
            if ($this->request->getData('confirm_import')) {
                $previewData = $this->request->getData('preview_data');
                if (!empty($previewData)) {
                    $data = json_decode($previewData, true);
                    $results = $this->processBulkFinalInvoices($data);
                    
                    $this->Flash->success(__('{0} final invoices created successfully. {1} errors.', 
                        $results['success'], 
                        $results['errors']
                    ));
                    
                    return $this->redirect(['action' => 'index']);
                }
            }
            
            // Initial file upload - parse and show preview
            if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
                $this->Flash->error(__('Please upload a valid CSV file.'));
                return;
            }
            
            $extension = strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
            
            if (!in_array($extension, ['csv'])) {
                $this->Flash->error(__('Please upload a CSV file (.csv)'));
                return;
            }
            
            // Move uploaded file to temp location
            $tmpPath = TMP . 'uploads' . DS . uniqid() . '.' . $extension;
            $file->moveTo($tmpPath);
            
            // Parse the file
            $data = $this->parseUploadedFile($tmpPath);
            unlink($tmpPath);
            
            if (empty($data)) {
                $this->Flash->error(__('No valid data found in the uploaded file.'));
                return;
            }
            
            // Validate Fresh Invoices exist for each row
            $validationResults = $this->validateFreshInvoices($data);
            
            // Show preview page with validation results
            $this->set('previewData', $data);
            $this->set('validationResults', $validationResults);
            $this->set('hasErrors', $validationResults['error_count'] > 0);
            $this->render('bulk_upload_preview');
            return;
        }
        
        // Load pending Fresh Invoices for the upload form
        $pendingInvoices = $this->FinalInvoices->FreshInvoices
            ->find()
            ->select([
                'FreshInvoices.id',
                'FreshInvoices.invoice_number',
                'FreshInvoices.quantity',
                'FreshInvoices.bl_number',
                'Vessels.name',
                'Vessels.flag_country'
            ])
            ->contain(['Vessels'])
            ->where([
                'FreshInvoices.id NOT IN' => $this->FinalInvoices
                    ->find()
                    ->select(['fresh_invoice_id'])
                    ->where(['fresh_invoice_id IS NOT' => null])
            ])
            ->order(['FreshInvoices.created' => 'DESC'])
            ->limit(10) // Show first 10 as preview
            ->all();
        
        $this->set('pendingInvoices', $pendingInvoices);
    }
    
    /**
     * Validate that Fresh Invoices exist for all rows
     *
     * @param array $data Parsed CSV data
     * @return array Validation results with matched invoices and errors
     */
    private function validateFreshInvoices(array $data): array
    {
        $results = [
            'matched' => [],
            'errors' => [],
            'error_count' => 0,
            'success_count' => 0
        ];
        
        foreach ($data as $index => $row) {
            $invoiceNumber = $row['Original Invoice Number'] ?? '';
            
            if (empty($invoiceNumber)) {
                $results['errors'][$index] = 'Missing Original Invoice Number';
                $results['error_count']++;
                continue;
            }
            
            // Try to find the Fresh Invoice
            $freshInvoice = $this->FinalInvoices->FreshInvoices
                ->find()
                ->where(['invoice_number' => $invoiceNumber])
                ->contain(['Clients', 'Products', 'Vessels'])
                ->first();
            
            if ($freshInvoice) {
                $results['matched'][$index] = [
                    'invoice' => $freshInvoice,
                    'client_name' => $freshInvoice->client->name ?? 'N/A',
                    'product_name' => $freshInvoice->product->name ?? 'N/A',
                    'original_quantity' => $freshInvoice->quantity,
                    'landed_quantity' => $row['Landed Quantity'] ?? 0,
                    'variance' => ($row['Landed Quantity'] ?? 0) - $freshInvoice->quantity,
                    'unit_price' => $freshInvoice->unit_price
                ];
                $results['success_count']++;
            } else {
                $results['errors'][$index] = "Fresh Invoice '{$invoiceNumber}' not found in system";
                $results['error_count']++;
            }
        }
        
        return $results;
    }

    /**
     * Parse uploaded CSV file
     *
     * @param string $filePath Path to uploaded file
     * @return array Parsed data rows
     */
    private function parseUploadedFile(string $filePath): array
    {
        $data = [];
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle); // First row as headers
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === count($headers)) {
                    $data[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        }
        
        return $data;
    }

    /**
     * Process bulk final invoices data and create entities
     *
     * @param array $data Parsed invoice data
     * @return array Results with success and error counts
     */
    private function processBulkFinalInvoices(array $data): array
    {
        $success = 0;
        $errors = 0;
        
        foreach ($data as $row) {
            try {
                // Find the Fresh Invoice by invoice number
                $originalInvoiceNumber = trim($row['Original Invoice Number'] ?? '');
                $freshInvoice = $this->FinalInvoices->FreshInvoices
                    ->find()
                    ->where(['invoice_number' => $originalInvoiceNumber])
                    ->first();
                
                if (!$freshInvoice) {
                    $errors++;
                    continue; // Skip if Fresh Invoice not found
                }
                
                // Parse vessel name from the format "Vessel: NAME - CODE"
                $vesselDisplay = trim($row['Vessel Name'] ?? '');
                $vesselName = $vesselDisplay;
                if (strpos($vesselDisplay, 'Vessel:') !== false) {
                    $vesselName = trim(str_replace('Vessel:', '', $vesselDisplay));
                }
                
                $landedQuantity = (float)str_replace([',', ' '], '', $row['Landed Quantity'] ?? 0);
                $originalQuantity = $freshInvoice->quantity;
                $variance = $landedQuantity - $originalQuantity;
                
                $invoiceData = [
                    'fresh_invoice_id' => $freshInvoice->id,
                    'original_invoice_number' => $originalInvoiceNumber,
                    'vessel_name' => $vesselName,
                    'bl_number' => trim($row['BL Number'] ?? $freshInvoice->bl_number),
                    'landed_quantity' => $landedQuantity,
                    'quantity_variance' => $variance,
                    'unit_price' => $freshInvoice->unit_price,
                    'payment_percentage' => $freshInvoice->payment_percentage,
                    'sgc_account_id' => $freshInvoice->sgc_account_id,
                    'notes' => trim($row['Notes'] ?? ''),
                    'status' => 'draft',
                    'invoice_date' => date('Y-m-d')
                ];
                
                $finalInvoice = $this->FinalInvoices->newEntity($invoiceData);
                
                if ($this->FinalInvoices->save($finalInvoice)) {
                    $success++;
                } else {
                    $errors++;
                }
            } catch (\Exception $e) {
                $errors++;
            }
        }
        
        return ['success' => $success, 'errors' => $errors];
    }

    /**
     * Download CSV template for bulk upload
     *
     * @return \Cake\Http\Response
     */
    public function downloadTemplate()
    {
        $this->autoRender = false;
        
        // Find Fresh Invoices that don't have Final Invoices yet
        $freshInvoicesNeedingFinal = $this->FinalInvoices->FreshInvoices
            ->find()
            ->select([
                'FreshInvoices.id',
                'FreshInvoices.invoice_number',
                'FreshInvoices.quantity',
                'FreshInvoices.bl_number',
                'Vessels.name',
                'Vessels.flag_country'
            ])
            ->contain(['Vessels'])
            ->where([
                'FreshInvoices.id NOT IN' => $this->FinalInvoices
                    ->find()
                    ->select(['fresh_invoice_id'])
                    ->where(['fresh_invoice_id IS NOT' => null])
            ])
            ->order(['FreshInvoices.created' => 'DESC'])
            ->limit(50) // Limit to 50 most recent
            ->all();
        
        // CSV headers
        $headers = [
            'Original Invoice Number',
            'Landed Quantity',
            'Vessel Name',
            'BL Number',
            'Notes'
        ];
        
        // Create CSV content
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, $headers);
        
        if ($freshInvoicesNeedingFinal->count() > 0) {
            // Add real Fresh Invoices as template rows
            foreach ($freshInvoicesNeedingFinal as $freshInvoice) {
                $vesselName = 'Vessel: ' . ($freshInvoice->vessel->name ?? 'Unknown');
                
                fputcsv($csv, [
                    $freshInvoice->invoice_number,
                    $freshInvoice->quantity, // Start with original quantity, user will update
                    $vesselName,
                    $freshInvoice->bl_number ?? '',
                    'Update landed quantity and add notes here'
                ]);
            }
        } else {
            // Fallback sample data if no Fresh Invoices need Final Invoices
            $sampleRows = [
                ['0155', '260.748', 'Vessel: GREAT TEMA - GTT0525', 'LOS37115', 'All Fresh Invoices already have Final Invoices'],
                ['0156', '259.408', 'Vessel: GREAT TEMA - GTT0525', 'LOS37113', 'These are sample rows only'],
            ];
            foreach ($sampleRows as $row) {
                fputcsv($csv, $row);
            }
        }
        
        rewind($csv);
        $output = stream_get_contents($csv);
        fclose($csv);
        
        // Set response
        $this->response = $this->response
            ->withType('text/csv')
            ->withDownload('final_invoices_template.csv')
            ->withStringBody($output);
        
        return $this->response;
    }
}
