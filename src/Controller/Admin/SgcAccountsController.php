<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * SGC Accounts Controller
 *
 * @property \App\Model\Table\SgcAccountsTable $SgcAccounts
 */
class SgcAccountsController extends AppAdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'SGC Accounts Management');
        
        $sgcAccounts = $this->paginate($this->SgcAccounts, [
            'order' => ['SgcAccounts.account_name' => 'ASC']
        ]);

        $this->set(compact('sgcAccounts'));
    }

    /**
     * View method
     *
     * @param string|null $id SGC Account id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sgcAccount = $this->SgcAccounts->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('sgcAccount'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sgcAccount = $this->SgcAccounts->newEmptyEntity();
        if ($this->request->is('post')) {
            $sgcAccount = $this->SgcAccounts->patchEntity($sgcAccount, $this->request->getData());
            if ($this->SgcAccounts->save($sgcAccount)) {
                $this->Flash->success(__('The SGC account has been saved successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The SGC account could not be saved. Please, try again.'));
        }
        $this->set(compact('sgcAccount'));
    }

    /**
     * Edit method
     *
     * @param string|null $id SGC Account id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sgcAccount = $this->SgcAccounts->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sgcAccount = $this->SgcAccounts->patchEntity($sgcAccount, $this->request->getData());
            if ($this->SgcAccounts->save($sgcAccount)) {
                $this->Flash->success(__('The SGC account has been updated successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The SGC account could not be updated. Please, try again.'));
        }
        $this->set(compact('sgcAccount'));
    }

    /**
     * Delete method
     *
     * @param string|null $id SGC Account id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sgcAccount = $this->SgcAccounts->get($id);
        if ($this->SgcAccounts->delete($sgcAccount)) {
            $this->Flash->success(__('The SGC account has been deleted successfully.'));
        } else {
            $this->Flash->error(__('The SGC account could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
