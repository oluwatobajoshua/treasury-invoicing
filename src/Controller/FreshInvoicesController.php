<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;

/**
 * FreshInvoices Controller
 *
 * @property \App\Model\Table\FreshInvoicesTable $FreshInvoices
 * @method \App\Model\Entity\FreshInvoice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FreshInvoicesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients', 'Products', 'Contracts', 'Vessels', 'SgcAccounts'],
            'order' => ['FreshInvoices.created' => 'DESC']
        ];
        $freshInvoices = $this->paginate($this->FreshInvoices);

        $this->set(compact('freshInvoices'));
    }

    /**
     * View method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $freshInvoice = $this->FreshInvoices->get($id, [
            'contain' => ['Clients', 'Products', 'Contracts', 'Vessels', 'SgcAccounts', 'FinalInvoices'],
        ]);

        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        
        $this->set(compact('freshInvoice', 'settings'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $freshInvoice = $this->FreshInvoices->newEmptyEntity();
        if ($this->request->is('post')) {
            $freshInvoice = $this->FreshInvoices->patchEntity($freshInvoice, $this->request->getData());
            
            // Set default values
            $freshInvoice->status = 'draft';
            $freshInvoice->treasurer_approval_status = 'pending';
            
            if ($this->FreshInvoices->save($freshInvoice)) {
                $this->Flash->success(__('The fresh invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fresh invoice could not be saved. Please, try again.'));
        }
        
        $clients = $this->FreshInvoices->Clients->find('list', ['limit' => 200])->all();
        $products = $this->FreshInvoices->Products->find('list', ['limit' => 200])->all();
        $contracts = $this->FreshInvoices->Contracts->find('list', [
            'keyField' => 'id',
            'valueField' => 'contract_id',
            'limit' => 200
        ])->all();
        $vessels = $this->FreshInvoices->Vessels->find('list', ['limit' => 200])->all();
        $sgcAccounts = $this->FreshInvoices->SgcAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return $entity->account_id . ' - ' . $entity->account_name . ' (' . $entity->currency . ')';
            },
            'limit' => 200
        ])->all();
        
        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        if (!$settings) {
            // Create default settings if none exist
            $settingsTable = $this->fetchTable('Settings');
            $settings = $settingsTable->newEntity([
                'company_name' => 'Sunbeth Global Concepts',
                'company_logo' => 'sunbeth-logo.png',
                'email' => 'info@sunbeth.net',
                'telephone' => '+234(0)805 6666 266',
                'corporate_address' => 'First Floor, Churchgate Towers 2, Victoria Island, Lagos State, Nigeria.'
            ]);
            $settingsTable->save($settings);
        }
        
        $this->set(compact('freshInvoice', 'clients', 'products', 'contracts', 'vessels', 'sgcAccounts', 'settings'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $freshInvoice = $this->FreshInvoices->get($id, [
            'contain' => [],
        ]);
        
        // Only allow editing if status is draft
        if ($freshInvoice->status !== 'draft') {
            $this->Flash->error(__('Only draft invoices can be edited.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $freshInvoice = $this->FreshInvoices->patchEntity($freshInvoice, $this->request->getData());
            if ($this->FreshInvoices->save($freshInvoice)) {
                $this->Flash->success(__('The fresh invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fresh invoice could not be saved. Please, try again.'));
        }
        
        $clients = $this->FreshInvoices->Clients->find('list', ['limit' => 200])->all();
        $products = $this->FreshInvoices->Products->find('list', ['limit' => 200])->all();
        $contracts = $this->FreshInvoices->Contracts->find('list', [
            'keyField' => 'id',
            'valueField' => 'contract_id',
            'limit' => 200
        ])->all();
        $vessels = $this->FreshInvoices->Vessels->find('list', ['limit' => 200])->all();
        $sgcAccounts = $this->FreshInvoices->SgcAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return $entity->account_id . ' - ' . $entity->account_name . ' (' . $entity->currency . ')';
            },
            'limit' => 200
        ])->all();
        
        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        
        $this->set(compact('freshInvoice', 'clients', 'products', 'contracts', 'vessels', 'sgcAccounts', 'settings'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $freshInvoice = $this->FreshInvoices->get($id);
        
        // Only allow deletion if status is draft
        if ($freshInvoice->status !== 'draft') {
            $this->Flash->error(__('Only draft invoices can be deleted.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->FreshInvoices->delete($freshInvoice)) {
            $this->Flash->success(__('The fresh invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The fresh invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Submit for approval method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function submitForApproval($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $freshInvoice = $this->FreshInvoices->get($id);
        
        if ($freshInvoice->status !== 'draft') {
            $this->Flash->error(__('Only draft invoices can be submitted for approval.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $freshInvoice->status = 'pending_treasurer_approval';
        if ($this->FreshInvoices->save($freshInvoice)) {
            $this->Flash->success(__('The invoice has been submitted to the Treasurer for approval.'));
        } else {
            $this->Flash->error(__('Could not submit the invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Treasurer approve method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function treasurerApprove($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $freshInvoice = $this->FreshInvoices->get($id);
        
        $freshInvoice->treasurer_approval_status = 'approved';
        $freshInvoice->treasurer_approval_date = new \DateTime();
        $freshInvoice->status = 'approved';
        
        if ($this->request->getData('treasurer_comments')) {
            $freshInvoice->treasurer_comments = $this->request->getData('treasurer_comments');
        }
        
        if ($this->FreshInvoices->save($freshInvoice)) {
            $this->Flash->success(__('The invoice has been approved.'));
        } else {
            $this->Flash->error(__('Could not approve the invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Treasurer reject method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function treasurerReject($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $freshInvoice = $this->FreshInvoices->get($id);
        
        $freshInvoice->treasurer_approval_status = 'rejected';
        $freshInvoice->treasurer_approval_date = new \DateTime();
        $freshInvoice->status = 'rejected';
        $freshInvoice->treasurer_comments = $this->request->getData('treasurer_comments');
        
        if ($this->FreshInvoices->save($freshInvoice)) {
            $this->Flash->success(__('The invoice has been rejected.'));
        } else {
            $this->Flash->error(__('Could not reject the invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Send to export team method
     *
     * @param string|null $id Fresh Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function sendToExport($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $freshInvoice = $this->FreshInvoices->get($id);
        
        if ($freshInvoice->status !== 'approved') {
            $this->Flash->error(__('Only approved invoices can be sent to the export team.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        $freshInvoice->status = 'sent_to_export';
        $freshInvoice->sent_to_export_date = new \DateTime();
        
        if ($this->FreshInvoices->save($freshInvoice)) {
            $this->Flash->success(__('The invoice has been sent to the export team.'));
        } else {
            $this->Flash->error(__('Could not send the invoice. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Get contract details by contract ID (AJAX)
     *
     * @return \Cake\Http\Response|null|void JSON response
     */
    public function getContractDetails()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');
        
        $contractId = $this->request->getQuery('contract_id');
        if (!$contractId) {
            return $this->response->withStringBody(json_encode(['error' => 'Contract ID is required']));
        }
        
        $contract = $this->FreshInvoices->Contracts->find()
            ->contain(['Clients', 'Products'])
            ->where(['Contracts.id' => $contractId])
            ->first();
        
        if (!$contract) {
            return $this->response->withStringBody(json_encode(['error' => 'Contract not found']));
        }
        
        return $this->response->withStringBody(json_encode([
            'client_id' => $contract->client_id,
            'client_name' => $contract->client->name,
            'product_id' => $contract->product_id,
            'product_name' => $contract->product->name,
            'unit_price' => $contract->unit_price,
            'quantity' => $contract->quantity
        ]));
    }

    /**
     * Bulk upload method for importing multiple fresh invoices from CSV/Excel
     *
     * @return \Cake\Http\Response|null|void Renders view or redirects on successful upload
     */
    public function bulkUpload()
    {
        if ($this->request->is('post')) {
            $file = $this->request->getData('file');
            
            if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
                $this->Flash->error(__('Please upload a valid CSV or Excel file.'));
                return;
            }
            
            $extension = strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
            
            if (!in_array($extension, ['csv', 'xlsx', 'xls'])) {
                $this->Flash->error(__('Please upload a CSV or Excel file (.csv, .xlsx, .xls)'));
                return;
            }
            
            // Move uploaded file to temp location
            $tmpPath = TMP . 'uploads' . DS . uniqid() . '.' . $extension;
            $file->moveTo($tmpPath);
            
            // Parse the file
            $data = $this->parseUploadedFile($tmpPath, $extension);
            unlink($tmpPath); // Clean up
            
            if (empty($data)) {
                $this->Flash->error(__('No valid data found in the uploaded file.'));
                return;
            }
            
            // Process and save invoices
            $results = $this->processBulkInvoices($data);
            
            $this->Flash->success(__('{0} invoices created successfully. {1} errors.', 
                $results['success'], 
                $results['errors']
            ));
            
            return $this->redirect(['action' => 'index']);
        }
        
        // Load reference data for the form
        $clients = $this->FreshInvoices->Clients->find('list', ['limit' => 200])->all();
        $products = $this->FreshInvoices->Products->find('list', ['limit' => 200])->all();
        $vessels = $this->FreshInvoices->Vessels->find('list', ['limit' => 200])->all();
        $contracts = $this->FreshInvoices->Contracts->find('list', ['limit' => 200])->all();
        $sgcAccounts = $this->FreshInvoices->SgcAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return $entity->account_id . ' - ' . $entity->account_name;
            },
            'limit' => 200
        ])->all();
        
        $this->set(compact('clients', 'products', 'vessels', 'contracts', 'sgcAccounts'));
    }

    /**
     * Parse uploaded CSV or Excel file
     *
     * @param string $filePath Path to uploaded file
     * @param string $extension File extension
     * @return array Parsed data rows
     */
    private function parseUploadedFile(string $filePath, string $extension): array
    {
        $data = [];
        
        if ($extension === 'csv') {
            // Parse CSV
            if (($handle = fopen($filePath, 'r')) !== false) {
                $headers = fgetcsv($handle); // First row as headers
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) === count($headers)) {
                        $data[] = array_combine($headers, $row);
                    }
                }
                fclose($handle);
            }
        } else {
            // Parse Excel using PhpSpreadsheet (if installed)
            // For now, we'll prompt user to convert to CSV
            $this->Flash->warning(__('Please convert Excel files to CSV format for now.'));
        }
        
        return $data;
    }

    /**
     * Process bulk invoices data and create entities
     *
     * @param array $data Parsed invoice data
     * @return array Results with success and error counts
     */
    private function processBulkInvoices(array $data): array
    {
        $success = 0;
        $errors = 0;
        
        foreach ($data as $row) {
            try {
                // Find or create related entities by name
                $client = $this->findOrCreateClient($row['Client Name'] ?? '');
                $product = $this->findOrCreateProduct($row['Product Name'] ?? '');
                $vessel = $this->findOrCreateVessel($row['Vessel Name'] ?? '');
                
                // Find SGC Account by account_id
                $sgcAccount = $this->FreshInvoices->SgcAccounts
                    ->find()
                    ->where(['account_id' => trim($row['SGC Account ID'] ?? '')])
                    ->first();
                
                // Invoice number is optional - will be auto-generated if empty
                $invoiceNumber = trim($row['INVOICE NUMBER'] ?? '');
                
                $invoiceData = [
                    'client_id' => $client ? $client->id : null,
                    'product_id' => $product ? $product->id : null,
                    'vessel_id' => $vessel ? $vessel->id : null,
                    'vessel_name' => trim($row['Vessel Name'] ?? ''),
                    'contract_reference' => trim($row['Contract ID'] ?? ''),
                    'bl_number' => trim($row['BL Number'] ?? ''),
                    'quantity' => (float)str_replace([',', ' '], '', $row['Quantity(MT)'] ?? 0),
                    'unit_price' => (float)str_replace([',', ' '], '', $row['Unit Price'] ?? 0),
                    'payment_percentage' => (float)str_replace(['%', ' '], '', $row['Payment %'] ?? 100),
                    'sgc_account_id' => $sgcAccount ? $sgcAccount->id : null,
                    'bulk_or_bag' => trim($row['BULK/BAG'] ?? ''),
                    'notes' => trim($row['Notes'] ?? ''),
                    'status' => 'draft',
                    'invoice_date' => date('Y-m-d')
                ];
                
                // Only set invoice_number if provided, otherwise let Table generate it
                if (!empty($invoiceNumber)) {
                    $invoiceData['invoice_number'] = $invoiceNumber;
                }
                
                $invoice = $this->FreshInvoices->newEntity($invoiceData);
                
                if ($this->FreshInvoices->save($invoice)) {
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
     * Find or create client by name
     */
    private function findOrCreateClient(string $name)
    {
        if (empty($name)) return null;
        
        $client = $this->FreshInvoices->Clients->find()
            ->where(['name' => trim($name)])
            ->first();
        
        if (!$client) {
            $client = $this->FreshInvoices->Clients->newEntity(['name' => trim($name)]);
            $this->FreshInvoices->Clients->save($client);
        }
        
        return $client;
    }

    /**
     * Find or create product by name
     */
    private function findOrCreateProduct(string $name)
    {
        if (empty($name)) return null;
        
        $product = $this->FreshInvoices->Products->find()
            ->where(['name' => trim($name)])
            ->first();
        
        if (!$product) {
            $product = $this->FreshInvoices->Products->newEntity(['name' => trim($name)]);
            $this->FreshInvoices->Products->save($product);
        }
        
        return $product;
    }

    /**
     * Find or create vessel by name
     */
    private function findOrCreateVessel(string $name)
    {
        if (empty($name)) return null;
        
        $vessel = $this->FreshInvoices->Vessels->find()
            ->where(['name' => trim($name)])
            ->first();
        
        if (!$vessel) {
            $vessel = $this->FreshInvoices->Vessels->newEntity(['name' => trim($name)]);
            $this->FreshInvoices->Vessels->save($vessel);
        }
        
        return $vessel;
    }
}
