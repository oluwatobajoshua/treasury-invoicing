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
        $this->paginate = [
            'contain' => ['FreshInvoices' => ['Vessels']],
            'order' => ['FinalInvoices.created' => 'DESC']
        ];
        $finalInvoices = $this->paginate($this->FinalInvoices);

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
        ];

        return $this->response->withStringBody(json_encode($payload));
    }
}
