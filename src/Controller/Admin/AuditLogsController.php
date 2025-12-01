<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * AuditLogs Controller
 *
 * @property \App\Model\Table\AuditLogsTable $AuditLogs
 */
class AuditLogsController extends AppAdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->AuditLogs->find()
            ->contain(['Users'])
            ->order(['AuditLogs.created' => 'DESC']);
        
        $auditLogs = $this->paginate($query);

        $this->set(compact('auditLogs'));
    }

    /**
     * View method
     *
     * @param string|null $id Audit Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $auditLog = $this->AuditLogs->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('auditLog'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Audit Log id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $auditLog = $this->AuditLogs->get($id);
        if ($this->AuditLogs->delete($auditLog)) {
            $this->Flash->success(__('The audit log has been deleted.'));
        } else {
            $this->Flash->error(__('The audit log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
