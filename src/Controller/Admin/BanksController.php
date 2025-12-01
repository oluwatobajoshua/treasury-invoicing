<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Banks Controller (Admin)
 */
class BanksController extends AppAdminController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Banks');
    }

    /**
     * Index method
     */
    public function index()
    {
        $query = $this->Banks->find()->order(['bank_name' => 'ASC']);
        $banks = $this->paginate($query, ['limit' => 25]);
        $this->set(compact('banks'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        try {
            $bank = $this->Banks->get((int)$id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('Bank not found.');
            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('bank'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $bank = $this->Banks->newEmptyEntity();
        if ($this->request->is('post')) {
            $bank = $this->Banks->patchEntity($bank, $this->request->getData());
            if ($this->Banks->save($bank)) {
                $this->Flash->success('Bank has been created.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to create bank.');
        }
        $this->set(compact('bank'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        try {
            $bank = $this->Banks->get((int)$id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('Bank not found.');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $bank = $this->Banks->patchEntity($bank, $this->request->getData());
            if ($this->Banks->save($bank)) {
                $this->Flash->success('Bank has been updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to update bank.');
        }

        $this->set(compact('bank'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $bank = $this->Banks->get((int)$id);
            if ($this->Banks->delete($bank)) {
                $this->Flash->success('Bank has been deleted.');
            } else {
                $this->Flash->error('Unable to delete bank.');
            }
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('Bank not found.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
