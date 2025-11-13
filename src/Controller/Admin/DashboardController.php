<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class DashboardController extends AppAdminController
{
    public function index()
    {
        // Minimal stats placeholders; expand with real queries later
        $this->loadModel('Users');
        $this->loadModel('TravelRequests');
        $usersCount = $this->Users->find()->count();
        $requestsCount = $this->TravelRequests->find()->count();

        // Status breakdown
        $statuses = ['submitted','lm_approved','admin_processing','completed','lm_rejected','rejected','draft'];
        $statusCounts = array_fill_keys($statuses, 0);
        $query = $this->TravelRequests->find()
            ->select(['status', 'count' => $this->TravelRequests->find()->func()->count('*')])
            ->group('status')
            ->enableHydration(false);
        foreach ($query as $row) {
            $status = $row['status'] ?? 'unknown';
            $statusCounts[$status] = (int)$row['count'];
        }

        // Recent requests
        $recentRequests = $this->TravelRequests->find()
            ->contain(['Users'])
            ->order(['TravelRequests.created' => 'DESC'])
            ->limit(8)
            ->all();

        $this->set(compact('usersCount', 'requestsCount', 'statusCounts', 'recentRequests'));
    }
}
