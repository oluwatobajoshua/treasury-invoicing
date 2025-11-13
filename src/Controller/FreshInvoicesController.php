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
}
