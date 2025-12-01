<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Router;

/**
 * ApprovalNotificationService
 *
 * Sends approval workflow notifications based on Admin > Approver Settings.
 * Uses Microsoft Graph for email delivery and can attach a PDF of the invoice.
 */
class ApprovalNotificationService
{
    private Client $http;
    private array $azureConfig;
    private ?string $accessToken;
    private bool $isAppOnly = false;
    private TableLocator $tables;
    /**
     * Holds last failure reason for diagnostic surfacing in controllers.
     */
    private ?string $lastError = null;

    public function __construct(?string $accessToken)
    {
        $this->http = new Client(['timeout' => 30]);
        $this->azureConfig = (array)Configure::read('Azure');
        $this->accessToken = $accessToken;
        $this->tables = new TableLocator();
    }

    /**
     * Send notifications for a Fresh Invoice stage
     *
     * @param int $invoiceId FreshInvoice ID
     * @param string $stage 'pending_approval' | 'approved' | 'rejected'
     * @param array $opts [ 'attachPdf' => bool ]
     * @return void
     */
    public function notifyFresh(int $invoiceId, string $stage, array $opts = []): void
    {
        $FreshInvoices = $this->tables->get('FreshInvoices');
        $ApproverSettings = $this->tables->get('ApproverSettings');
        $Settings = $this->tables->get('Settings');

        $invoice = $FreshInvoices->find()
            ->contain(['Clients', 'Products', 'Contracts', 'Vessels', 'SgcAccounts'])
            ->where(['FreshInvoices.id' => $invoiceId])
            ->first();
        if (!$invoice) {
            Log::warning("ApprovalNotificationService: FreshInvoice {$invoiceId} not found");
            return;
        }

        $appSettings = $Settings->find()->first();
        $companyName = $appSettings->company_name ?? 'Sunbeth Global Concepts';

        // Recipients from settings
        $rules = $ApproverSettings->getRecipients('fresh', $stage);
        if (empty($rules)) {
            Log::info("ApprovalNotificationService: No approver settings for fresh:$stage, skipping email");
            return;
        }

        // Prepare email content
        $subject = $this->buildSubject('Fresh', $stage, (string)($invoice->invoice_number ?? $invoice->id));
        $bodyHtml = $this->buildBodyHtml('fresh', $stage, [
            'invoice' => $invoice,
            'companyName' => $companyName,
            'appSettings' => $appSettings,
        ]);

        // Optional PDF attachment
        $attachments = [];
        $attachPdf = (bool)($opts['attachPdf'] ?? true);
        if ($attachPdf) {
            $pdfBytes = $this->renderFreshPdf($invoice, $appSettings);
            if ($pdfBytes) {
                $attachments[] = [
                    'name' => sprintf('FreshInvoice_%s.pdf', $invoice->invoice_number ?? $invoice->id),
                    'contentType' => 'application/pdf',
                    'contentBytes' => base64_encode($pdfBytes),
                ];
            }
        }

        $this->deliverEmails($rules, $subject, $bodyHtml, $attachments);
        // Optional Teams message
        $this->notifyTeams($subject, $this->stripHtmlForTeams($bodyHtml));
    }

    /**
     * Send notifications for a Final Invoice stage
     */
    public function notifyFinal(int $invoiceId, string $stage, array $opts = []): void
    {
        $FinalInvoices = $this->tables->get('FinalInvoices');
        $ApproverSettings = $this->tables->get('ApproverSettings');
        $Settings = $this->tables->get('Settings');

        $invoice = $FinalInvoices->find()
            ->contain(['FreshInvoices' => ['Clients', 'Products', 'Contracts', 'Vessels'], 'SgcAccounts'])
            ->where(['FinalInvoices.id' => $invoiceId])
            ->first();
        if (!$invoice) {
            Log::warning("ApprovalNotificationService: FinalInvoice {$invoiceId} not found");
            return;
        }

        $appSettings = $Settings->find()->first();
        $companyName = $appSettings->company_name ?? 'Sunbeth Global Concepts';

        $rules = $ApproverSettings->getRecipients('final', $stage);
        if (empty($rules)) {
            Log::info("ApprovalNotificationService: No approver settings for final:$stage, skipping email");
            return;
        }

        $subject = $this->buildSubject('Final', $stage, (string)($invoice->invoice_number ?? $invoice->id));
        $bodyHtml = $this->buildBodyHtml('final', $stage, [
            'invoice' => $invoice,
            'companyName' => $companyName,
            'appSettings' => $appSettings,
        ]);

        $attachments = [];
        $attachPdf = (bool)($opts['attachPdf'] ?? true);
        if ($attachPdf) {
            $pdfBytes = $this->renderFinalPdf($invoice, $appSettings);
            if ($pdfBytes) {
                $attachments[] = [
                    'name' => sprintf('FinalInvoice_%s.pdf', $invoice->invoice_number ?? $invoice->id),
                    'contentType' => 'application/pdf',
                    'contentBytes' => base64_encode($pdfBytes),
                ];
            }
        }

        $this->deliverEmails($rules, $subject, $bodyHtml, $attachments);
        // Optional Teams message
        $this->notifyTeams($subject, $this->stripHtmlForTeams($bodyHtml));
    }

    private function buildSubject(string $kind, string $stage, string $id): string
    {
        $stageText = [
            'pending_approval' => 'Approval Required',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ][$stage] ?? ucfirst($stage);
        return sprintf('[%s] %s: #%s', $kind, $stageText, $id);
    }

    private function buildBodyHtml(string $type, string $stage, array $ctx): string
    {
        $invoice = $ctx['invoice'];
        $company = h((string)($ctx['companyName'] ?? ''));
        $fullBase = (string)(Configure::read('App.fullBaseUrl') ?? 'http://localhost:8765');
        $appSettings = $ctx['appSettings'] ?? null;

        if ($type === 'fresh') {
            $viewUrl = Router::url([
                'controller' => 'FreshInvoices', 'action' => 'view', $invoice->id,
                'prefix' => false
            ], true);
            // Approve/Reject links (tokenized)
            $approveUrl = Router::url([
                'controller' => 'Approvals', 'action' => 'fresh', $invoice->id, 'approve',
                'prefix' => false,
                '?' => ['t' => $this->makeToken('fresh', (int)$invoice->id, 'approve')]
            ], true);
            $rejectUrl = Router::url([
                'controller' => 'Approvals', 'action' => 'fresh', $invoice->id, 'reject',
                'prefix' => false,
                '?' => ['t' => $this->makeToken('fresh', (int)$invoice->id, 'reject')]
            ], true);

            $invoiceHtml = $this->renderFreshEmailHtml($invoice, $appSettings);
            $buttons = '';
            if ($stage === 'pending_approval') {
                $buttons = '<a href="' . $approveUrl . '" style="background:#059669;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none;margin-right:8px">Approve</a>'
                    . '<a href="' . $rejectUrl . '" style="background:#dc2626;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none">Reject</a>';
            }
            $amount = number_format((float)($invoice->total_value ?? ($invoice->quantity * $invoice->unit_price)), 2);
            $wrapper = '<div style="font:14px/1.6 -apple-system,Segoe UI,Arial,sans-serif;color:#111827;background:#f5f7f9;padding:16px">'
                . '<div style="max-width:800px;margin:0 auto">'
                . '<p style="margin:0 0 8px">Hello,</p>'
                . '<p style="margin:0 0 12px"><strong>Fresh Invoice</strong> <strong>#' . h((string)($invoice->invoice_number ?? $invoice->id)) . '</strong> for client <strong>' . h((string)($invoice->client->name ?? 'N/A')) . '</strong> is currently <strong>' . h(ucfirst(str_replace('_', ' ', (string)$stage))) . '</strong>.</p>'
                . '<p style="margin:0 0 14px">Amount Payable: <strong>$' . $amount . '</strong></p>'
                . '<div style="margin:12px 0 16px">'
                . '<a href="' . $viewUrl . '" style="background:#0c5343;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none;margin-right:8px">View in App</a>'
                . $buttons
                . '</div>'
                . $invoiceHtml
                . '<p style="color:#6b7280;margin-top:16px">Sent by ' . $company . ' Treasury Invoicing</p>'
                . '</div></div>';
            return $wrapper;
        }

        // Final invoice
        $viewUrl = Router::url([
            'controller' => 'FinalInvoices', 'action' => 'view', $ctx['invoice']->id,
            'prefix' => false
        ], true);
        $approveUrl = Router::url([
            'controller' => 'Approvals', 'action' => 'final', $ctx['invoice']->id, 'approve',
            'prefix' => false,
            '?' => ['t' => $this->makeToken('final', (int)$ctx['invoice']->id, 'approve')]
        ], true);
        $rejectUrl = Router::url([
            'controller' => 'Approvals', 'action' => 'final', $ctx['invoice']->id, 'reject',
            'prefix' => false,
            '?' => ['t' => $this->makeToken('final', (int)$ctx['invoice']->id, 'reject')]
        ], true);

        $amount = (float)(($ctx['invoice']->landed_quantity ?? 0) * ($ctx['invoice']->unit_price ?? 0));
        $invoiceHtml = $this->renderFinalEmailHtml($ctx['invoice'], $appSettings);
        $buttons = '';
        if ($stage === 'pending_approval') {
            $buttons = '<a href="' . $approveUrl . '" style="background:#059669;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none;margin-right:8px">Approve</a>'
                . '<a href="' . $rejectUrl . '" style="background:#dc2626;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none">Reject</a>';
        }
        $wrapper = '<div style="font:14px/1.6 -apple-system,Segoe UI,Arial,sans-serif;color:#111827;background:#f5f7f9;padding:16px">'
            . '<div style="max-width:800px;margin:0 auto">'
            . '<p style="margin:0 0 8px">Hello,</p>'
            . '<p style="margin:0 0 12px"><strong>Final Invoice</strong> <strong>#' . h((string)($ctx['invoice']->invoice_number ?? $ctx['invoice']->id)) . '</strong> for client <strong>' . h((string)($ctx['invoice']->fresh_invoice->client->name ?? 'N/A')) . '</strong> is currently <strong>' . h(ucfirst(str_replace('_', ' ', (string)$stage))) . '</strong>.</p>'
            . '<p style="margin:0 0 14px">Amount Due: <strong>$' . number_format($amount, 2) . '</strong></p>'
            . '<div style="margin:12px 0 16px">'
            . '<a href="' . $viewUrl . '" style="background:#0c5343;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none;margin-right:8px">View in App</a>'
            . $buttons
            . '</div>'
            . $invoiceHtml
            . '<p style="color:#6b7280;margin-top:16px">Sent by ' . $company . ' Treasury Invoicing</p>'
            . '</div></div>';
        return $wrapper;
    }

    private function deliverEmails(array $rules, string $subject, string $bodyHtml, array $attachments = []): void
    {
        $token = $this->ensureGraphToken();
        if (empty($token)) {
            Log::warning('ApprovalNotificationService: No Graph token available; cannot send emails');
            return;
        }

        $graph = new MicrosoftGraphService($token);
        // If we're using app-only token, we must specify a sender mailbox to use
        $fromUpn = null;
        if ($this->isAppOnly) {
            $fromUpn = (string)($this->azureConfig['senderUpn'] ?? '');
            if (!$fromUpn) {
                Log::warning('ApprovalNotificationService: App-only token in use but Azure.senderUpn not configured; cannot send emails');
                return;
            }
        }
        foreach ($rules as $rule) {
            $to = $this->filterEmails($rule['to'] ?? []);
            $cc = $this->filterEmails($rule['cc'] ?? []);
            if (empty($to) && empty($cc)) {
                continue;
            }
            $result = $graph->sendEmail([
                'to' => $to ?: $cc, // Graph requires at least one To; if only CC configured, fall back
                'cc' => $cc,
                'subject' => $subject,
                'body' => $bodyHtml,
                'attachments' => $attachments,
                'fromUpn' => $fromUpn,
            ]);
            if (!$result['success']) {
                Log::error('ApprovalNotificationService: Email send failed - ' . ($result['message'] ?? 'unknown'));
            }
        }
    }

    /**
     * Generate a secure short-lived token used in approval links.
     */
    public function makeToken(string $type, int $id, string $decision, int $ttlSeconds = 259200): string
    {
        $secret = (string)(Configure::read('Security.salt') ?? 'change-me');
        $exp = time() + $ttlSeconds; // default 3 days
        $payload = $type . ':' . $id . ':' . strtolower($decision) . ':' . $exp;
        $sig = hash_hmac('sha256', $payload, $secret);
        return rtrim(strtr(base64_encode($payload . '|' . $sig), '+/', '-_'), '=');
    }

    /**
     * Verify a token from approval links. Returns [type, id, decision] if valid; null if invalid/expired.
     */
    public function verifyToken(string $token): ?array
    {
        $secret = (string)(Configure::read('Security.salt') ?? 'change-me');
        $raw = base64_decode(strtr($token, '-_', '+/'));
        if (!$raw || !str_contains($raw, '|')) return null;
        [$payload, $sig] = explode('|', $raw, 2);
        $expected = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($expected, $sig)) return null;
        [$type, $id, $decision, $exp] = explode(':', $payload, 4);
        if (time() > (int)$exp) return null;
        return [$type, (int)$id, strtolower($decision)];
    }

    /**
     * Render PDF bytes for Fresh Invoice using a static HTML template.
     */
    private function renderFreshPdf($invoice, $appSettings): ?string
    {
        try {
            $html = $this->renderTemplateToString('pdf/fresh_invoice', [
                'freshInvoice' => $invoice,
                'settings' => $appSettings,
                'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
            ]);
            return $this->htmlToPdf($html);
        } catch (\Throwable $e) {
            Log::error('PDF render (fresh) failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Render PDF bytes for Final Invoice using a static HTML template.
     */
    private function renderFinalPdf($invoice, $appSettings): ?string
    {
        try {
            $html = $this->renderTemplateToString('pdf/final_invoice', [
                'finalInvoice' => $invoice,
                'settings' => $appSettings,
                'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
            ]);
            return $this->htmlToPdf($html);
        } catch (\Throwable $e) {
            Log::error('PDF render (final) failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Read optional PDF logo override from config/app_settings.php
     */
    private function getPdfLogoOverride(): ?string
    {
        $file = CONFIG . 'app_settings.php';
        if (is_file($file)) {
            try {
                $settings = include $file;
                $logo = (string)($settings['pdf_logo'] ?? '');
                if ($logo !== '') {
                    return $logo;
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }
        return null;
    }

    /**
     * Use Cake View to render a template into string (no layout)
     */
    private function renderTemplateToString(string $template, array $vars): string
    {
        $view = new \Cake\View\View();
        foreach ($vars as $k => $v) {
            $view->set($k, $v);
        }
        // Disable layout for partial template rendering
        if (method_exists($view, 'disableAutoLayout')) {
            $view->disableAutoLayout();
        } else {
            // Fallback for older Cake versions
            $view->enableAutoLayout(false);
        }
        return $view->render($template);
    }

    /**
     * Convert HTML to PDF bytes using mPDF
     */
    private function htmlToPdf(string $html): string
    {
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => TMP . 'mpdf',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        $mpdf->WriteHTML($html);
        return $mpdf->Output('', 'S'); // return as string
    }

    private function renderFreshEmailHtml($invoice, $appSettings): string
    {
        return $this->renderTemplateToString('email/html/fresh_invoice_email', [
            'freshInvoice' => $invoice,
            'settings' => $appSettings,
            'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
        ]);
    }

    private function renderFinalEmailHtml($invoice, $appSettings): string
    {
        return $this->renderTemplateToString('email/html/final_invoice_email', [
            'finalInvoice' => $invoice,
            'settings' => $appSettings,
            'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
        ]);
    }

    /**
     * Ensure we have a Microsoft Graph access token; if session token is absent, request app-only token
     */
    private function ensureGraphToken(): ?string
    {
        if (!empty($this->accessToken)) {
            return $this->accessToken;
        }

        // App-only client credentials
        $tenant = (string)($this->azureConfig['tenantId'] ?? '');
        $clientId = (string)($this->azureConfig['clientId'] ?? '');
        $clientSecret = (string)($this->azureConfig['clientSecret'] ?? '');
        if (!$tenant || !$clientId || !$clientSecret) {
            return null;
        }

        try {
            $resp = $this->http->post('https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials',
                'scope' => 'https://graph.microsoft.com/.default',
            ]);
            if (!$resp->isOk()) {
                Log::error('Graph app-only token failed: ' . $resp->getStringBody());
                return null;
            }
            $data = $resp->getJson();
            $this->accessToken = $data['access_token'] ?? null;
            $this->isAppOnly = !empty($this->accessToken);
            return $this->accessToken;
        } catch (\Throwable $e) {
            Log::error('Graph app-only token exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert a logo file into a data URI if the file exists
     */
    private function resolveLogoDataUri(?string $filename): ?string
    {
        if (empty($filename)) return null;
        $candidates = [
            WWW_ROOT . 'img' . DS . $filename,
            WWW_ROOT . $filename,
            WWW_ROOT . 'files' . DS . $filename,
        ];
        foreach ($candidates as $file) {
            if (is_file($file)) {
                $mime = function_exists('mime_content_type') ? mime_content_type($file) : 'image/png';
                $data = @file_get_contents($file);
                if ($data !== false) {
                    return 'data:' . $mime . ';base64,' . base64_encode($data);
                }
            }
        }
        return null;
    }

    /**
     * Simplify HTML into readable text for Teams messages
     */
    private function stripHtmlForTeams(string $html): string
    {
        $text = strip_tags($html);
        return preg_replace('/\s+/', ' ', (string)$text);
    }

    /**
     * Post a simple message to a Teams channel if configured
     */
    private function notifyTeams(string $title, string $content): void
    {
        try {
            $teamId = (string)($this->azureConfig['teams']['teamId'] ?? '');
            $channelId = (string)($this->azureConfig['teams']['channelId'] ?? '');
            if (!$teamId || !$channelId) return;
            $token = $this->ensureGraphToken();
            if (!$token) return;
            $endpoint = rtrim((string)($this->azureConfig['graphApiEndpoint'] ?? 'https://graph.microsoft.com/v1.0'), '/')
                . '/teams/' . $teamId . '/channels/' . $channelId . '/messages';
            $payload = [
                'subject' => $title,
                'body' => [
                    'contentType' => 'html',
                    'content' => '<b>' . h($title) . '</b><br/>' . nl2br(h($content)),
                ],
            ];
            $resp = $this->http->post($endpoint, json_encode($payload), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
            ]);
            if (!$resp->isOk() && $resp->getStatusCode() !== 201) {
                Log::warning('Teams notify non-OK: ' . $resp->getStatusCode() . ' ' . $resp->getStringBody());
            }
        } catch (\Throwable $e) {
            Log::error('Teams notify exception: ' . $e->getMessage());
        }
    }

    /**
     * Send Sales Invoice email with inline HTML + PDF attachment to explicit recipients
     */
    public function sendSalesInvoice(int $invoiceId, array $to, array $cc = [], array $opts = []): bool
    {
        $Sales = $this->tables->get('SalesInvoices');
        $Settings = $this->tables->get('Settings');
        $ApproverSettings = $this->tables->get('ApproverSettings');

        $invoice = $Sales->find()
            ->contain(['Clients', 'Banks'])
            ->where(['SalesInvoices.id' => $invoiceId])
            ->first();
        if (!$invoice) {
            $this->lastError = "SalesInvoice {$invoiceId} not found";
            Log::warning($this->lastError);
            return false;
        }

        $appSettings = $Settings->find()->first();
        $subject = sprintf('[Sales] Invoice: #%s', (string)($invoice->invoice_number ?? $invoice->id));
        $bodyHtml = $this->renderSalesEmailHtml($invoice, $appSettings);

        $attachments = [];
        $attachPdf = (bool)($opts['attachPdf'] ?? true);
        if ($attachPdf) {
            $pdf = $this->renderSalesPdf($invoice, $appSettings);
            if ($pdf) {
                $attachments[] = [
                    'name' => sprintf('SalesInvoice_%s.pdf', $invoice->invoice_number ?? $invoice->id),
                    'contentType' => 'application/pdf',
                    'contentBytes' => base64_encode($pdf),
                ];
            }
        }
        // Dynamic recipients from approver settings for sales 'sent' stage
        $rules = $ApproverSettings->getRecipients('sales', 'sent');
        // Inject explicit recipients as an additional rule if provided
        $explicitTo = $this->filterEmails($to);
        $explicitCc = $this->filterEmails($cc);
        if (!empty($explicitTo) || !empty($explicitCc)) {
            $rules[] = [
                'role' => 'explicit',
                'to' => $explicitTo,
                'cc' => $explicitCc
            ];
        }
        if (empty($rules)) {
            $this->lastError = 'No sales invoice notification recipients configured (approver settings and explicit list empty)';
            Log::info($this->lastError);
            return false;
        }
        $this->deliverEmails($rules, $subject, $bodyHtml, $attachments);
        // We assume success if at least one email attempt fired without capturing an error (errors logged internally per rule)
        return $this->lastError === null;
    }

    /**
     * Send Sustainability Invoice email with inline HTML + PDF attachment to explicit recipients
     */
    public function sendSustainabilityInvoice(int $invoiceId, array $to, array $cc = [], array $opts = []): bool
    {
        $Sust = $this->tables->get('SustainabilityInvoices');
        $Settings = $this->tables->get('Settings');
        $ApproverSettings = $this->tables->get('ApproverSettings');

        $invoice = $Sust->find()
            ->contain(['Clients', 'Banks'])
            ->where(['SustainabilityInvoices.id' => $invoiceId])
            ->first();
        if (!$invoice) {
            Log::warning("SustainabilityInvoice {$invoiceId} not found");
            return false;
        }

        $appSettings = $Settings->find()->first();
        $subject = sprintf('[Sustainability] Invoice: #%s', (string)($invoice->invoice_number ?? $invoice->id));
        $bodyHtml = $this->renderSustainabilityEmailHtml($invoice, $appSettings);

        $attachments = [];
        $attachPdf = (bool)($opts['attachPdf'] ?? true);
        if ($attachPdf) {
            $pdf = $this->renderSustainabilityPdf($invoice, $appSettings);
            if ($pdf) {
                $attachments[] = [
                    'name' => sprintf('SustainabilityInvoice_%s.pdf', $invoice->invoice_number ?? $invoice->id),
                    'contentType' => 'application/pdf',
                    'contentBytes' => base64_encode($pdf),
                ];
            }
        }
        $rules = $ApproverSettings->getRecipients('sustainability', 'sent');
        $explicitTo = $this->filterEmails($to);
        $explicitCc = $this->filterEmails($cc);
        if (!empty($explicitTo) || !empty($explicitCc)) {
            $rules[] = [
                'role' => 'explicit',
                'to' => $explicitTo,
                'cc' => $explicitCc
            ];
        }
        if (empty($rules)) {
            $this->lastError = 'No sustainability invoice notification recipients configured (approver settings and explicit list empty)';
            Log::info($this->lastError);
            return false;
        }
        $this->deliverEmails($rules, $subject, $bodyHtml, $attachments);
        return $this->lastError === null;
    }

    /**
     * Deliver an email to explicit recipients using Graph, handling app-only vs user token.
     */
    private function deliverEmailTo(array $to, array $cc, string $subject, string $bodyHtml, array $attachments = []): bool
    {
        $token = $this->ensureGraphToken();
        if (empty($token)) {
            $this->lastError = 'No Microsoft Graph access token (user session or app-only)';
            Log::warning('deliverEmailTo: No Graph token available; cannot send email');
            return false;
        }

        $graph = new MicrosoftGraphService($token);
        $fromUpn = null;
        if ($this->isAppOnly) {
            $fromUpn = (string)($this->azureConfig['senderUpn'] ?? '');
            if (!$fromUpn) {
                $this->lastError = 'App-only token requires Azure.senderUpn configuration';
                Log::warning('deliverEmailTo: App-only token in use but Azure.senderUpn not configured; aborting send');
                return false;
            }
        }

        $result = $graph->sendEmail([
            'to' => $this->filterEmails($to) ?: $this->filterEmails($cc),
            'cc' => $this->filterEmails($cc),
            'subject' => $subject,
            'body' => $bodyHtml,
            'attachments' => $attachments,
            'fromUpn' => $fromUpn,
        ]);
        if (!$result['success']) {
            $this->lastError = $result['message'] ?? 'Unknown Graph failure';
            Log::error('deliverEmailTo failed: ' . ($result['message'] ?? 'unknown'));
        }
        return (bool)$result['success'];
    }

    /**
     * Return last error message if an operation failed.
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Remove empty, placeholder or invalid email tokens from a list.
     */
    private function filterEmails(array $emails): array
    {
        $clean = [];
        foreach ($emails as $e) {
            $e = trim((string)$e);
            if ($e === '' || $e === 'N/A' || $e === 'NA' || $e === '-') {
                continue;
            }
            if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
                continue; // skip invalid
            }
            $clean[] = $e;
        }
        // Deduplicate preserving order
        $dedup = [];
        foreach ($clean as $e) {
            if (!in_array($e, $dedup, true)) {
                $dedup[] = $e;
            }
        }
        return $dedup;
    }

    private function renderSalesEmailHtml($invoice, $appSettings): string
    {
        return $this->renderTemplateToString('email/html/sales_invoice_email', [
            'invoice' => $invoice,
            'settings' => $appSettings,
            'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
        ]);
    }

    private function renderSustainabilityEmailHtml($invoice, $appSettings): string
    {
        return $this->renderTemplateToString('email/html/sustainability_invoice_email', [
            'invoice' => $invoice,
            'settings' => $appSettings,
            'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
        ]);
    }

    private function renderSalesPdf($invoice, $appSettings): ?string
    {
        try {
            $html = $this->renderTemplateToString('pdf/sales_invoice', [
                'invoice' => $invoice,
                'settings' => $appSettings,
                'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
            ]);
            return $this->htmlToPdf($html);
        } catch (\Throwable $e) {
            Log::error('PDF render (sales) failed: ' . $e->getMessage());
            return null;
        }
    }

    private function renderSustainabilityPdf($invoice, $appSettings): ?string
    {
        try {
            $html = $this->renderTemplateToString('pdf/sustainability_invoice', [
                'invoice' => $invoice,
                'settings' => $appSettings,
                'logoDataUri' => $this->resolveLogoDataUri($this->getPdfLogoOverride() ?? ($appSettings->company_logo ?? null)),
            ]);
            return $this->htmlToPdf($html);
        } catch (\Throwable $e) {
            Log::error('PDF render (sustainability) failed: ' . $e->getMessage());
            return null;
        }
    }
}
