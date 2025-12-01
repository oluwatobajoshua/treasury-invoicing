<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Admin ApproverSettings Controller
 * 
 * Manages notification recipients for invoice approval workflows
 * Only accessible by admin role users
 */
class ApproverSettingsController extends AppAdminController
{
    /**
     * Index method - List all approver settings
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadModel('ApproverSettings');
        
        $settings = $this->ApproverSettings->find()
            ->order([
                'invoice_type' => 'ASC',
                'stage' => 'ASC',
                'role' => 'ASC'
            ])
            ->all();

        // Group by invoice type and stage for better display
        $groupedSettings = [];
        foreach ($settings as $setting) {
            $key = $setting->invoice_type . '_' . $setting->stage;
            $groupedSettings[$key][] = $setting;
        }

        $this->set(compact('settings', 'groupedSettings'));
    }

    /**
     * View method
     *
     * @param string|null $id Approver Setting id.
     * @return \Cake\Http\Response|null
     */
    public function view($id = null)
    {
        $this->loadModel('ApproverSettings');
        $approverSetting = $this->ApproverSettings->get($id);
        
        $this->set(compact('approverSetting'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('ApproverSettings');
        $approverSetting = $this->ApproverSettings->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $approverSetting = $this->ApproverSettings->patchEntity($approverSetting, $this->request->getData());
            if ($this->ApproverSettings->save($approverSetting)) {
                $this->Flash->success('The approver setting has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to save the approver setting. Please check the form.');
        }
        
        // Extended to support Sales & Sustainability straight notification workflows (no approval cycle)
        $roles = [
            'treasurer' => 'Treasurer',
            'export' => 'Export Team',
            'sales' => 'Sales Team',
            'sustainability' => 'Sustainability Team'
        ];
        $invoiceTypes = [
            'fresh' => 'Fresh Invoice',
            'final' => 'Final Invoice',
            'sales' => 'Sales Invoice',
            'sustainability' => 'Sustainability Invoice'
        ];
        // Added 'sent' for the simple Sales & Sustainability flows
        $stages = [
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'sent' => 'Sent'
        ];
        
        $this->set(compact('approverSetting', 'roles', 'invoiceTypes', 'stages'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Approver Setting id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     */
    public function edit($id = null)
    {
        $this->loadModel('ApproverSettings');
        $approverSetting = $this->ApproverSettings->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $approverSetting = $this->ApproverSettings->patchEntity($approverSetting, $this->request->getData());
            if ($this->ApproverSettings->save($approverSetting)) {
                $this->Flash->success('The approver setting has been updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to update the approver setting. Please check the form.');
        }
        
        $roles = [
            'treasurer' => 'Treasurer',
            'export' => 'Export Team',
            'sales' => 'Sales Team',
            'sustainability' => 'Sustainability Team'
        ];
        $invoiceTypes = [
            'fresh' => 'Fresh Invoice',
            'final' => 'Final Invoice',
            'sales' => 'Sales Invoice',
            'sustainability' => 'Sustainability Invoice'
        ];
        $stages = [
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'sent' => 'Sent'
        ];
        
        $this->set(compact('approverSetting', 'roles', 'invoiceTypes', 'stages'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Approver Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('ApproverSettings');
        $approverSetting = $this->ApproverSettings->get($id);
        
        if ($this->ApproverSettings->delete($approverSetting)) {
            $this->Flash->success('The approver setting has been deleted.');
        } else {
            $this->Flash->error('Unable to delete the approver setting.');
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Toggle active status
     *
     * @param string|null $id Approver Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function toggle($id = null)
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('ApproverSettings');
        $approverSetting = $this->ApproverSettings->get($id);
        
        $approverSetting->is_active = !$approverSetting->is_active;
        
        if ($this->ApproverSettings->save($approverSetting)) {
            $status = $approverSetting->is_active ? 'activated' : 'deactivated';
            $this->Flash->success("The approver setting has been {$status}.");
        } else {
            $this->Flash->error('Unable to update the approver setting.');
        }
        
        return $this->redirect(['action' => 'index']);
    }
}
