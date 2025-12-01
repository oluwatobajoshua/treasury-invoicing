<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class DashboardController extends AppAdminController
{
    public function index()
    {
        // Load models
        $this->loadModel('Users');
        $this->loadModel('FreshInvoices');
        $this->loadModel('FinalInvoices');
        $this->loadModel('Clients');
        $this->loadModel('Products');
        $this->loadModel('Vessels');
        $this->loadModel('Contracts');
        $this->loadModel('SgcAccounts');
        
        // Get statistics
        $stats = [
            'users_count' => $this->Users->find()->count(),
            'fresh_invoices_count' => $this->FreshInvoices->find()->count(),
            'final_invoices_count' => $this->FinalInvoices->find()->count(),
            'clients_count' => $this->Clients->find()->count(),
            'products_count' => $this->Products->find()->count(),
            'vessels_count' => $this->Vessels->find()->count(),
            'contracts_count' => $this->Contracts->find()->count(),
            'sgc_accounts_count' => $this->SgcAccounts->find()->count(),
        ];
        
        // Fresh invoices by status
        $freshStatuses = ['draft', 'pending_treasurer_approval', 'approved', 'rejected', 'sent_to_export'];
        $freshStatusCounts = array_fill_keys($freshStatuses, 0);
        $query = $this->FreshInvoices->find()
            ->select(['status', 'count' => $this->FreshInvoices->find()->func()->count('*')])
            ->group('status')
            ->enableHydration(false);
        foreach ($query as $row) {
            $status = $row['status'] ?? 'unknown';
            $freshStatusCounts[$status] = (int)$row['count'];
        }
        
        // Final invoices by status
        $finalStatuses = ['draft', 'pending_treasurer_approval', 'approved', 'rejected', 'sent_to_sales'];
        $finalStatusCounts = array_fill_keys($finalStatuses, 0);
        $query = $this->FinalInvoices->find()
            ->select(['status', 'count' => $this->FinalInvoices->find()->func()->count('*')])
            ->group('status')
            ->enableHydration(false);
        foreach ($query as $row) {
            $status = $row['status'] ?? 'unknown';
            $finalStatusCounts[$status] = (int)$row['count'];
        }
        
        // Recent Fresh Invoices
        $recentFreshInvoices = $this->FreshInvoices->find()
            ->contain(['Clients', 'Products'])
            ->order(['FreshInvoices.created' => 'DESC'])
            ->limit(10)
            ->all();
        
        // Recent users
        $recentUsers = $this->Users->find()
            ->order(['Users.created' => 'DESC'])
            ->limit(5)
            ->all();
        
        $this->set(compact('stats', 'freshStatusCounts', 'finalStatusCounts', 'recentFreshInvoices', 'recentUsers'));
    }
}
