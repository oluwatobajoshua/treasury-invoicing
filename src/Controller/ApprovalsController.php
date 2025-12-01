<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ApprovalNotificationService;
use Cake\Log\Log;

/**
 * Public controller to handle approve/reject actions from email links.
 * Uses a signed token to authorize a one-click action without login.
 */
class ApprovalsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // This controller is public; AppController is updated to allow it
        $this->viewBuilder()->setLayout('default');
    }

    /**
     * Handle Fresh Invoice approval/rejection via token
     * GET /approvals/fresh/{id}/{decision}?t=token
     */
    public function fresh(int $id, string $decision)
    {
        $token = (string)$this->request->getQuery('t');
        $service = new ApprovalNotificationService(null);
        $verified = $service->verifyToken($token);
        if (!$verified) {
            $this->Flash->error('Invalid or expired approval link.');
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

        [$type, $tokId, $tokDecision] = $verified;
        if ($type !== 'fresh' || $tokId !== $id || !in_array($tokDecision, ['approve','reject'], true)) {
            $this->Flash->error('Invalid approval token.');
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

    $FreshInvoices = $this->fetchTable('FreshInvoices');
    $invoice = $FreshInvoices->get($id);

        // Only allow if pending treasurer approval
        if ($invoice->status !== 'pending_treasurer_approval') {
            $this->Flash->warning('This invoice is not awaiting approval.');
            return $this->redirect(['controller' => 'FreshInvoices', 'action' => 'view', $id]);
        }

        // If rejecting via email link, require a comment (GET shows form, POST processes)
        if ($tokDecision === 'reject') {
            if ($this->request->is('get')) {
                $this->set('context', [
                    'type' => 'fresh',
                    'id' => $id,
                    'token' => $token,
                    'invoice' => $invoice,
                ]);
                return $this->render('reject_form');
            }

            // POST must include a non-empty comment
            $comment = trim((string)$this->request->getData('treasurer_comments'));
            if ($comment === '') {
                $this->Flash->error('Please provide a comment explaining the reason for rejection.');
                $this->set('context', [
                    'type' => 'fresh',
                    'id' => $id,
                    'token' => $token,
                    'invoice' => $invoice,
                ]);
                return $this->render('reject_form');
            }

            $now = new \DateTime();
            $invoice->treasurer_approval_status = 'rejected';
            $invoice->treasurer_approval_date = $now;
            $invoice->status = 'rejected';
            $invoice->treasurer_comments = $comment;

            if ($FreshInvoices->save($invoice)) {
                $accessToken = $this->request->getSession()->read('Auth.AccessToken');
                $notifier = new ApprovalNotificationService($accessToken);
                $notifier->notifyFresh($id, 'rejected', ['attachPdf' => true]);
                $this->Flash->success('Your decision has been recorded successfully.');
            } else {
                $this->Flash->error('Could not update invoice. Please try again in the app.');
            }

            return $this->redirect(['controller' => 'FreshInvoices', 'action' => 'view', $id]);
        }

        $now = new \DateTime();
        if ($tokDecision === 'approve') {
            $invoice->treasurer_approval_status = 'approved';
            $invoice->treasurer_approval_date = $now;
            $invoice->status = 'approved';
        }

        if ($FreshInvoices->save($invoice)) {
            // Notify next stage via email if needed
            $accessToken = $this->request->getSession()->read('Auth.AccessToken');
            $notifier = new ApprovalNotificationService($accessToken);
            $notifier->notifyFresh($id, 'approved', ['attachPdf' => true]);

            $this->Flash->success('Your decision has been recorded successfully.');
        } else {
            $this->Flash->error('Could not update invoice. Please try again in the app.');
        }

        return $this->redirect(['controller' => 'FreshInvoices', 'action' => 'view', $id]);
    }

    /**
     * Handle Final Invoice approval/rejection via token
     * GET /approvals/final/{id}/{decision}?t=token
     */
    public function final(int $id, string $decision)
    {
        $token = (string)$this->request->getQuery('t');
        $service = new ApprovalNotificationService(null);
        $verified = $service->verifyToken($token);
        if (!$verified) {
            $this->Flash->error('Invalid or expired approval link.');
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

        [$type, $tokId, $tokDecision] = $verified;
        if ($type !== 'final' || $tokId !== $id || !in_array($tokDecision, ['approve','reject'], true)) {
            $this->Flash->error('Invalid approval token.');
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

    $FinalInvoices = $this->fetchTable('FinalInvoices');
    $invoice = $FinalInvoices->get($id);

        if ($invoice->status !== 'pending_treasurer_approval') {
            $this->Flash->warning('This final invoice is not awaiting approval.');
            return $this->redirect(['controller' => 'FinalInvoices', 'action' => 'view', $id]);
        }

        // If rejecting via email link, require a comment (GET shows form, POST processes)
        if ($tokDecision === 'reject') {
            if ($this->request->is('get')) {
                $this->set('context', [
                    'type' => 'final',
                    'id' => $id,
                    'token' => $token,
                    'invoice' => $invoice,
                ]);
                return $this->render('reject_form');
            }

            // POST must include a non-empty comment
            $comment = trim((string)$this->request->getData('treasurer_comments'));
            if ($comment === '') {
                $this->Flash->error('Please provide a comment explaining the reason for rejection.');
                $this->set('context', [
                    'type' => 'final',
                    'id' => $id,
                    'token' => $token,
                    'invoice' => $invoice,
                ]);
                return $this->render('reject_form');
            }

            $now = new \DateTime();
            $invoice->treasurer_approval_status = 'rejected';
            $invoice->treasurer_approval_date = $now;
            $invoice->status = 'rejected';
            $invoice->treasurer_comments = $comment;

            if ($FinalInvoices->save($invoice)) {
                $accessToken = $this->request->getSession()->read('Auth.AccessToken');
                $notifier = new ApprovalNotificationService($accessToken);
                $notifier->notifyFinal($id, 'rejected', ['attachPdf' => true]);
                $this->Flash->success('Your decision has been recorded successfully.');
            } else {
                $this->Flash->error('Could not update the final invoice. Please try again in the app.');
            }

            return $this->redirect(['controller' => 'FinalInvoices', 'action' => 'view', $id]);
        }

        $now = new \DateTime();
        if ($tokDecision === 'approve') {
            $invoice->treasurer_approval_status = 'approved';
            $invoice->treasurer_approval_date = $now;
            $invoice->status = 'approved';
        }

        if ($FinalInvoices->save($invoice)) {
            $accessToken = $this->request->getSession()->read('Auth.AccessToken');
            $notifier = new ApprovalNotificationService($accessToken);
            $notifier->notifyFinal($id, 'approved', ['attachPdf' => true]);
            $this->Flash->success('Your decision has been recorded successfully.');
        } else {
            $this->Flash->error('Could not update the final invoice. Please try again in the app.');
        }

        return $this->redirect(['controller' => 'FinalInvoices', 'action' => 'view', $id]);
    }
}
