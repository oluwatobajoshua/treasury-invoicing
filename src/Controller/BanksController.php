<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Banks Controller
 *
 * @property \App\Model\Table\BanksTable $Banks
 * @method \App\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BanksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Get all banks without pagination (DataTables handles pagination client-side)
        $banks = $this->Banks->find('all', [
            'order' => ['Banks.created' => 'DESC']
        ])->toArray();

        // Get statistics
        $total = $this->Banks->find()->count();
        $active = $this->Banks->find()->where(['is_active' => true])->count();
        $salesBanks = $this->Banks->find()->where(['bank_type IN' => ['sales', 'both']])->count();
        $sustainabilityBanks = $this->Banks->find()->where(['bank_type IN' => ['sustainability', 'both']])->count();
        $shipmentBanks = $this->Banks->find()->where(['bank_type IN' => ['shipment', 'both']])->count();

        $this->set(compact('banks', 'total', 'active', 'salesBanks', 'sustainabilityBanks', 'shipmentBanks'));
    }

    /**
     * View method
     *
     * @param string|null $id Bank id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bank = $this->Banks->get($id);

        // Load settings for company details
        $this->loadModel('Settings');
        $settings = $this->Settings->find()->first();
        $companyName = $settings->company_name ?? 'Company Name';
        $companyAddress = $settings->company_address ?? '';
        $companyPhone = $settings->company_phone ?? '';
        $companyEmail = $settings->company_email ?? '';

        $this->set(compact('bank', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bank = $this->Banks->newEmptyEntity();
        if ($this->request->is('post')) {
            $bank = $this->Banks->patchEntity($bank, $this->request->getData());
            if ($this->Banks->save($bank)) {
                $this->Flash->success(__('The bank has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bank could not be saved. Please, try again.'));
        }
        $this->set(compact('bank'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bank id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bank = $this->Banks->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bank = $this->Banks->patchEntity($bank, $this->request->getData());
            if ($this->Banks->save($bank)) {
                $this->Flash->success(__('The bank has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bank could not be updated. Please, try again.'));
        }
        $this->set(compact('bank'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bank id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bank = $this->Banks->get($id);
        if ($this->Banks->delete($bank)) {
            $this->Flash->success(__('The bank has been deleted.'));
        } else {
            $this->Flash->error(__('The bank could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
