<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;

/**
 * SalesInvoices Controller
 *
 * @property \App\Model\Table\SalesInvoicesTable $SalesInvoices
 * @method \App\Model\Entity\SalesInvoice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SalesInvoicesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Get all invoices without pagination (DataTables handles pagination client-side)
        $salesInvoices = $this->SalesInvoices->find('all', [
            'contain' => ['Clients', 'Banks'],
            'order' => ['SalesInvoices.created' => 'DESC']
        ])->toArray();

        // KPI metrics for index header
        $all = $this->SalesInvoices->find();
        $kpis = [
            'total' => (clone $all)->count(),
            'draft' => (clone $all)->where(['status' => 'draft'])->count(),
            'sent' => (clone $all)->where(['status' => 'sent'])->count(),
            'paid' => (clone $all)->where(['status' => 'paid'])->count(),
            'cancelled' => (clone $all)->where(['status' => 'cancelled'])->count(),
            'total_value_sum' => (clone $all)->select(['sum' => $all->func()->sum('total_value')])->first()->sum ?? 0,
        ];

        $this->set(compact('salesInvoices', 'kpis'));
    }

    /**
     * View method
     *
     * @param string|null $id Sales Invoice id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $salesInvoice = $this->SalesInvoices->get($id, [
            'contain' => ['Clients', 'BankAccounts'],
        ]);

        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        
        $this->set(compact('salesInvoice', 'settings'));
    }

    /**
     * Send Sales Invoice via email (inline HTML + PDF attachment)
     *
     * @param string|null $id Sales Invoice id
     * @return \Cake\Http\Response|null|void Redirects back to view
     */
    public function send($id = null)
    {
        $this->request->allowMethod(['post']);
        $invoice = $this->SalesInvoices->get($id, [
            'contain' => ['Clients']
        ]);

        // Determine recipients: client email or 'to' query param fallback
        $to = [];
        if (!empty($invoice->client->email)) {
            $to[] = (string)$invoice->client->email;
        }
        $explicit = trim((string)($this->request->getQuery('to') ?? ''));
        if ($explicit !== '') {
            // support semicolon/comma separated list
            $more = preg_split('/[;,\s]+/', $explicit, -1, PREG_SPLIT_NO_EMPTY);
            $to = array_merge($to, $more ?: []);
        }
        $to = array_values(array_unique(array_filter($to)));

        if (empty($to)) {
            $this->Flash->error(__('No recipient email found. Please add a client email or pass ?to=user@example.com'));
            return $this->redirect(['action' => 'view', $id]);
        }

        $accessToken = $this->request->getSession()->read('Auth.AccessToken');
        $notifier = new \App\Service\ApprovalNotificationService($accessToken);
        $ok = $notifier->sendSalesInvoice((int)$invoice->id, $to, [], ['attachPdf' => true]);

        if ($ok) {
            // Mark as sent if currently draft
            if ($invoice->status === 'draft') {
                $invoice->status = 'sent';
                $this->SalesInvoices->save($invoice);
            }
            $this->Flash->success(__('Sales invoice emailed successfully.'));
        } else {
            $reason = $notifier->getLastError();
            $this->Flash->error(__('Failed to send sales invoice email.') . ($reason ? ' ' . __('Reason: {0}', $reason) : ''));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $salesInvoice = $this->SalesInvoices->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Calculate total_value from quantity and unit_price
            if (!empty($data['quantity']) && !empty($data['unit_price'])) {
                $data['total_value'] = $data['quantity'] * $data['unit_price'];
            }
            
            $salesInvoice = $this->SalesInvoices->patchEntity($salesInvoice, $data);
            
            // Set default values
            if (empty($salesInvoice->status)) {
                $salesInvoice->status = 'draft';
            }
            if (empty($salesInvoice->currency)) {
                $salesInvoice->currency = 'NGN';
            }
            if (empty($salesInvoice->invoice_date)) {
                $salesInvoice->invoice_date = FrozenDate::now();
            }
            
            if ($this->SalesInvoices->save($salesInvoice)) {
                $this->Flash->success(__('The sales invoice has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales invoice could not be saved. Please, try again.'));
        }
        
        // Get all clients for dropdown
        $clients = $this->SalesInvoices->Clients->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->order(['Clients.name' => 'ASC']);
        
        // Get all bank accounts for dropdown (legacy SGC accounts)
        $bankAccounts = $this->SalesInvoices->BankAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return sprintf('%s - %s (%s)', $entity->account_name, $entity->account_number, $entity->currency);
            }
        ])->order(['BankAccounts.account_name' => 'ASC']);
        
        // Get banks for dropdown (new bank system)
        $banks = $this->SalesInvoices->Banks->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return sprintf('%s - %s (%s)', $entity->bank_name, $entity->account_number ?? 'N/A', $entity->currency);
            }
        ])->where([
            'is_active' => true,
            'bank_type IN' => ['sales', 'both']
        ])->order(['Banks.bank_name' => 'ASC']);
        
        $this->set(compact('salesInvoice', 'clients', 'bankAccounts', 'banks'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sales Invoice id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $salesInvoice = $this->SalesInvoices->get($id, [
            'contain' => [],
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Calculate total_value from quantity and unit_price
            if (!empty($data['quantity']) && !empty($data['unit_price'])) {
                $data['total_value'] = $data['quantity'] * $data['unit_price'];
            }
            
            $salesInvoice = $this->SalesInvoices->patchEntity($salesInvoice, $data);
            
            if ($this->SalesInvoices->save($salesInvoice)) {
                $this->Flash->success(__('The sales invoice has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales invoice could not be saved. Please, try again.'));
        }
        
        // Get all clients for dropdown
        $clients = $this->SalesInvoices->Clients->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->order(['Clients.name' => 'ASC']);
        
        // Get all bank accounts for dropdown (legacy SGC accounts)
        $bankAccounts = $this->SalesInvoices->BankAccounts->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return sprintf('%s - %s (%s)', $entity->account_name, $entity->account_number, $entity->currency);
            }
        ])->order(['BankAccounts.account_name' => 'ASC']);
        
        // Get banks for dropdown (new bank system)
        $banks = $this->SalesInvoices->Banks->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return sprintf('%s - %s (%s)', $entity->bank_name, $entity->account_number ?? 'N/A', $entity->currency);
            }
        ])->where([
            'is_active' => true,
            'bank_type IN' => ['sales', 'both']
        ])->order(['Banks.bank_name' => 'ASC']);
        
        $this->set(compact('salesInvoice', 'clients', 'bankAccounts', 'banks'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sales Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salesInvoice = $this->SalesInvoices->get($id);
        
        if ($this->SalesInvoices->delete($salesInvoice)) {
            $this->Flash->success(__('The sales invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The sales invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
