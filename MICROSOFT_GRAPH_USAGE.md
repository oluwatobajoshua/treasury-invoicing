# Microsoft Graph Integration Guide

## Overview
The Treasury Invoice System uses Microsoft Graph API for:
- **User Management**: Fetch users from Azure AD for dropdowns
- **Email Notifications**: Send invoice approval emails via Microsoft Graph

## Setup

### 1. Azure AD App Permissions
Ensure your Azure AD app has these permissions configured:
- `User.Read` - Read user profile
- `User.ReadBasic.All` - Read all users' basic profiles
- `User.Read.All` - Read all users' full profiles
- `Mail.Send` - Send mail as the signed-in user

### 2. Configuration
The permissions are already configured in `config/azure.php`:
```php
'scopes' => [
    'openid',
    'profile',
    'email',
    'offline_access',
    'User.Read',
    'User.ReadBasic.All',
    'User.Read.All',
    'Mail.Send'
]
```

## Usage

### Using the MicrosoftGraphService Class

#### In Controllers (Without Component)
```php
// Get access token from session
$session = $this->request->getSession();
$accessToken = $session->read('Auth.AccessToken');

// Initialize service
$graphService = new \App\Service\MicrosoftGraphService($accessToken);

// Get all users
$users = $graphService->getAllUsers();
// Returns: [['id' => '', 'name' => '', 'email' => '', 'jobTitle' => '', ...], ...]

// Get users for dropdown
$dropdown = $graphService->getUsersForDropdown();
// Returns: ['user_id' => 'Name (email@example.com)', ...]

// Send email
$result = $graphService->sendEmail([
    'to' => ['recipient@example.com', 'another@example.com'],
    'cc' => ['cc@example.com'], // optional
    'subject' => 'Invoice Approval Required',
    'body' => '<h1>Invoice Details</h1><p>Please review...</p>',
    'attachments' => [ // optional
        [
            'name' => 'invoice.pdf',
            'contentType' => 'application/pdf',
            'contentBytes' => base64_encode($pdfContent)
        ]
    ]
]);

if ($result['success']) {
    // Email sent successfully
} else {
    // Handle error: $result['message']
}
```

### Using the MicrosoftGraphComponent

#### Load in Controller
```php
// In your controller's initialize() method
public function initialize(): void
{
    parent::initialize();
    $this->loadComponent('MicrosoftGraph');
}
```

#### Usage in Actions
```php
public function someAction()
{
    // Get all users
    $users = $this->MicrosoftGraph->getAllUsers();
    
    // Get users for dropdown
    $usersList = $this->MicrosoftGraph->getUsersForDropdown();
    $this->set(compact('usersList'));
    
    // Send email
    $result = $this->MicrosoftGraph->sendEmail([
        'to' => ['user@example.com'],
        'subject' => 'Test Email',
        'body' => '<p>This is a test email</p>'
    ]);
    
    if ($result['success']) {
        $this->Flash->success('Email sent successfully');
    } else {
        $this->Flash->error($result['message']);
    }
}
```

## User Dropdowns in Templates

### Method 1: Load via AJAX (Recommended for large user lists)
```javascript
// In your template
<script>
$(document).ready(function() {
    // Fetch users from Microsoft Graph
    $.ajax({
        url: '/auth/get-graph-users',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Error:', response.error);
                if (response.redirect) {
                    window.location.href = '/auth/login';
                }
                return;
            }
            
            // Populate dropdown
            const $select = $('#user-select');
            $select.empty();
            $select.append('<option value="">-- Select User --</option>');
            
            response.users.forEach(function(user) {
                const display = user.name + ' (' + user.email + ')';
                $select.append($('<option>', {
                    value: user.id,
                    text: display,
                    'data-email': user.email,
                    'data-jobtitle': user.jobTitle
                }));
            });
        },
        error: function() {
            console.error('Failed to fetch users');
        }
    });
});
</script>
```

### Method 2: Pass from Controller (For smaller lists)
```php
// In controller
public function add()
{
    $this->loadComponent('MicrosoftGraph');
    $users = $this->MicrosoftGraph->getUsersForDropdown();
    $this->set(compact('users'));
}
```

```php
// In template
<?= $this->Form->control('user_id', [
    'options' => $users,
    'empty' => '-- Select User --',
    'label' => 'Assign User'
]) ?>
```

## Email Sending Examples

### Simple Text Email
```php
$graphService->sendEmail([
    'to' => ['user@example.com'],
    'subject' => 'Simple Notification',
    'body' => '<p>Your invoice has been approved.</p>'
]);
```

### Email with Multiple Recipients
```php
$graphService->sendEmail([
    'to' => ['treasurer@example.com', 'finance@example.com'],
    'cc' => ['audit@example.com'],
    'subject' => 'Invoice Pending Approval',
    'body' => '<h2>Invoice #12345</h2><p>Please review and approve.</p>'
]);
```

### Email with PDF Attachment
```php
// Generate PDF content (using TCPDF, mPDF, or similar)
$pdf = new \TCPDF();
// ... generate PDF content ...
$pdfContent = $pdf->Output('', 'S'); // Get PDF as string

// Send via Graph
$graphService->sendEmail([
    'to' => ['recipient@example.com'],
    'subject' => 'Invoice Document',
    'body' => '<p>Please find attached invoice PDF.</p>',
    'attachments' => [
        [
            'name' => 'invoice_12345.pdf',
            'contentType' => 'application/pdf',
            'contentBytes' => base64_encode($pdfContent)
        ]
    ]
]);
```

### Email Using Approver Settings
```php
// Get recipients from approver settings
$this->loadModel('ApproverSettings');
$recipients = $this->ApproverSettings->getRecipients('fresh', 'pending_approval');

foreach ($recipients as $recipientSetting) {
    $result = $this->MicrosoftGraph->sendEmail([
        'to' => $recipientSetting['to'],
        'cc' => $recipientSetting['cc'],
        'subject' => 'Fresh Invoice Pending Approval',
        'body' => $emailBody,
        'attachments' => [
            [
                'name' => 'invoice.pdf',
                'contentType' => 'application/pdf',
                'contentBytes' => base64_encode($pdfContent)
            ]
        ]
    ]);
    
    if (!$result['success']) {
        $this->log('Failed to send email to ' . $recipientSetting['role'] . ': ' . $result['message'], 'error');
    }
}
```

## Integration with Approver Settings

The system stores notification recipients in the `approver_settings` table. Here's how to use it:

```php
// In your invoice approval action
public function approve($id)
{
    $invoice = $this->FreshInvoices->get($id);
    
    // Update invoice status
    $invoice->status = 'approved';
    $this->FreshInvoices->save($invoice);
    
    // Get notification recipients for this workflow stage
    $this->loadModel('ApproverSettings');
    $recipients = $this->ApproverSettings->getRecipients('fresh', 'approved');
    
    // Generate PDF
    $pdfContent = $this->generateInvoicePdf($invoice);
    
    // Send notifications via Microsoft Graph
    $this->loadComponent('MicrosoftGraph');
    
    foreach ($recipients as $setting) {
        if (!empty($setting['to'])) {
            $result = $this->MicrosoftGraph->sendEmail([
                'to' => $setting['to'],
                'cc' => $setting['cc'] ?? [],
                'subject' => "Fresh Invoice #{$invoice->invoice_number} Approved",
                'body' => $this->renderEmailTemplate($invoice, 'approved'),
                'attachments' => [
                    [
                        'name' => "invoice_{$invoice->invoice_number}.pdf",
                        'contentType' => 'application/pdf',
                        'contentBytes' => base64_encode($pdfContent)
                    ]
                ]
            ]);
            
            if (!$result['success']) {
                $this->log("Failed to notify {$setting['role']}: {$result['message']}", 'error');
            }
        }
    }
    
    $this->Flash->success('Invoice approved and notifications sent');
    return $this->redirect(['action' => 'index']);
}
```

## Troubleshooting

### "No access token provided"
- User must be logged in via Azure AD
- Access token is stored in session: `Auth.AccessToken`
- Token expires after a certain time - implement token refresh

### "Failed to send email"
- Check that `Mail.Send` permission is granted in Azure AD
- Verify the signed-in user has a mailbox
- Check logs for specific Graph API error messages

### "Failed to fetch users"
- Verify `User.Read.All` or `User.ReadBasic.All` permission
- Check admin consent has been granted for the tenant
- Ensure access token has not expired

## Best Practices

1. **Always check for access token** before calling Graph API
2. **Handle errors gracefully** - Graph API calls can fail
3. **Log all email operations** for audit trail
4. **Use HTML email bodies** - Graph supports rich HTML content
5. **Keep attachments reasonable** - Large attachments may cause timeouts
6. **Cache user lists** when appropriate to reduce API calls
7. **Implement retry logic** for transient failures

## API Reference

See the inline documentation in:
- `src/Service/MicrosoftGraphService.php`
- `src/Controller/Component/MicrosoftGraphComponent.php`
