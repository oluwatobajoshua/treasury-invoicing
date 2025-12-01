<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Vessels Controller
 *
 * @property \App\Model\Table\VesselsTable $Vessels
 */
class VesselsController extends AppAdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Vessels Management');
        
        $vessels = $this->paginate($this->Vessels, [
            'order' => ['Vessels.name' => 'ASC']
        ]);

        $this->set(compact('vessels'));
    }

    /**
     * View method
     *
     * @param string|null $id Vessel id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vessel = $this->Vessels->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('vessel'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vessel = $this->Vessels->newEmptyEntity();
        if ($this->request->is('post')) {
            $vessel = $this->Vessels->patchEntity($vessel, $this->request->getData());
            if ($this->Vessels->save($vessel)) {
                $this->Flash->success(__('The vessel has been saved successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vessel could not be saved. Please, try again.'));
        }
        $this->set(compact('vessel'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Vessel id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vessel = $this->Vessels->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vessel = $this->Vessels->patchEntity($vessel, $this->request->getData());
            if ($this->Vessels->save($vessel)) {
                $this->Flash->success(__('The vessel has been updated successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vessel could not be updated. Please, try again.'));
        }
        $this->set(compact('vessel'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Vessel id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vessel = $this->Vessels->get($id);
        if ($this->Vessels->delete($vessel)) {
            $this->Flash->success(__('The vessel has been deleted successfully.'));
        } else {
            $this->Flash->error(__('The vessel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
