<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;

/**
 * SustainabilityInvoices Controller
 *
 * @property \App\Model\Table\SustainabilityInvoicesTable $SustainabilityInvoices
 * @method \App\Model\Entity\SustainabilityInvoice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SustainabilityInvoicesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Get all invoices without pagination (DataTables handles pagination client-side)
        $sustainabilityInvoices = $this->SustainabilityInvoices->find('all', [
            'contain' => ['Clients', 'Banks'],
            'order' => ['SustainabilityInvoices.created' => 'DESC']
        ])->toArray();

        // KPI metrics for index header
        $all = $this->SustainabilityInvoices->find();
        $kpis = [
            'total' => (clone $all)->count(),
            'draft' => (clone $all)->where(['status' => 'draft'])->count(),
            'sent' => (clone $all)->where(['status' => 'sent'])->count(),
            'paid' => (clone $all)->where(['status' => 'paid'])->count(),
            'cancelled' => (clone $all)->where(['status' => 'cancelled'])->count(),
            'total_value_sum' => (clone $all)->select(['sum' => $all->func()->sum('total_value')])->first()->sum ?? 0,
            'investment_sum' => (clone $all)->select(['sum' => $all->func()->sum('sustainability_investment')])->first()->sum ?? 0,
            'differential_sum' => (clone $all)->select(['sum' => $all->func()->sum('sustainability_differential')])->first()->sum ?? 0,
        ];

        $this->set(compact('sustainabilityInvoices', 'kpis'));
    }

    /**
     * View method
     *
     * @param string|null $id Sustainability Invoice id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sustainabilityInvoice = $this->SustainabilityInvoices->get($id, [
            'contain' => ['Clients'],
        ]);

        // Load company settings
        $settings = $this->fetchTable('Settings')->find()->first();
        
        $this->set(compact('sustainabilityInvoice', 'settings'));
    }

    /**
     * Send Sustainability Invoice via email (inline HTML + PDF attachment)
     *
     * @param string|null $id Sustainability Invoice id
     * @return \Cake\Http\Response|null|void Redirects back to view
     */
    public function send($id = null)
    {
        $this->request->allowMethod(['post']);
        $invoice = $this->SustainabilityInvoices->get($id, [
            'contain' => ['Clients']
        ]);

        $to = [];
        if (!empty($invoice->client->email)) {
            $to[] = (string)$invoice->client->email;
        }
        $explicit = trim((string)($this->request->getQuery('to') ?? ''));
        if ($explicit !== '') {
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
        $ok = $notifier->sendSustainabilityInvoice((int)$invoice->id, $to, [], ['attachPdf' => true]);

        if ($ok) {
            if ($invoice->status === 'draft') {
                $invoice->status = 'sent';
                $this->SustainabilityInvoices->save($invoice);
            }
            $this->Flash->success(__('Sustainability invoice emailed successfully.'));
        } else {
            $this->Flash->error(__('Failed to send sustainability invoice email.'));
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
        $sustainabilityInvoice = $this->SustainabilityInvoices->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Calculate total_value and net_receivable
            if (!empty($data['sustainability_investment']) && !empty($data['sustainability_differential'])) {
                $investment = (float)$data['sustainability_investment'];
                $differential = (float)$data['sustainability_differential'];
                $data['total_value'] = $investment + $differential;
                $data['net_receivable'] = $data['total_value']; // 100% of contract value
            }
            
            $sustainabilityInvoice = $this->SustainabilityInvoices->patchEntity($sustainabilityInvoice, $data);
            
            // Set default values
            if (empty($sustainabilityInvoice->status)) {
                $sustainabilityInvoice->status = 'draft';
            }
            if (empty($sustainabilityInvoice->currency)) {
                $sustainabilityInvoice->currency = 'USD';
            }
            if (empty($sustainabilityInvoice->invoice_date)) {
                $sustainabilityInvoice->invoice_date = FrozenDate::now();
            }
            
            if ($this->SustainabilityInvoices->save($sustainabilityInvoice)) {
                $this->Flash->success(__('The sustainability invoice has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sustainability invoice could not be saved. Please, try again.'));
        }
        
        // Get all clients for dropdown
        $clients = $this->SustainabilityInvoices->Clients->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->order(['Clients.name' => 'ASC']);
        
        // Get banks for dropdown (sustainability type)
        $banks = $this->SustainabilityInvoices->Banks->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return sprintf('%s - %s (%s)', $entity->bank_name, $entity->account_number ?? 'N/A', $entity->currency);
            }
        ])->where([
            'is_active' => true,
            'bank_type IN' => ['sustainability', 'both']
        ])->order(['Banks.bank_name' => 'ASC']);
        
        $this->set(compact('sustainabilityInvoice', 'clients', 'banks'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sustainability Invoice id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sustainabilityInvoice = $this->SustainabilityInvoices->get($id, [
            'contain' => [],
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Calculate total_value and net_receivable
            if (!empty($data['sustainability_investment']) && !empty($data['sustainability_differential'])) {
                $investment = (float)$data['sustainability_investment'];
                $differential = (float)$data['sustainability_differential'];
                $data['total_value'] = $investment + $differential;
                $data['net_receivable'] = $data['total_value']; // 100% of contract value
            }
            
            $sustainabilityInvoice = $this->SustainabilityInvoices->patchEntity($sustainabilityInvoice, $data);
            
            if ($this->SustainabilityInvoices->save($sustainabilityInvoice)) {
                $this->Flash->success(__('The sustainability invoice has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sustainability invoice could not be saved. Please, try again.'));
        }
        
        // Get all clients for dropdown
        $clients = $this->SustainabilityInvoices->Clients->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->order(['Clients.name' => 'ASC']);
        
        // Get banks for dropdown (sustainability type)
        $banks = $this->SustainabilityInvoices->Banks->find('list', [
            'keyField' => 'id',
            'valueField' => function ($entity) {
                return sprintf('%s - %s (%s)', $entity->bank_name, $entity->account_number ?? 'N/A', $entity->currency);
            }
        ])->where([
            'is_active' => true,
            'bank_type IN' => ['sustainability', 'both']
        ])->order(['Banks.bank_name' => 'ASC']);
        
        $this->set(compact('sustainabilityInvoice', 'clients', 'banks'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sustainability Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sustainabilityInvoice = $this->SustainabilityInvoices->get($id);
        
        if ($this->SustainabilityInvoices->delete($sustainabilityInvoice)) {
            $this->Flash->success(__('The sustainability invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The sustainability invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
