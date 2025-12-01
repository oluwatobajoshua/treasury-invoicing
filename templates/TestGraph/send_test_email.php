<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Send Test Email');
?>

<style>
.test-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
}

.test-section {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.test-section h3 {
    margin-top: 0;
    color: #0c5343;
}
</style>

<div class="test-container">
    <h1><i class="fas fa-paper-plane"></i> Send Test Email via Microsoft Graph</h1>
    
    <?php if (!empty($result)): ?>
        <div class="test-section">
            <h3><i class="fas fa-<?= $result['success'] ? 'check-circle' : 'exclamation-triangle' ?>"></i> Result</h3>
            <?php if ($result['success']): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= h($result['message']) ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?= h($result['message']) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="test-section">
        <h3><i class="fas fa-envelope"></i> Compose Test Email</h3>
        
        <?= $this->Form->create(null, ['type' => 'post']) ?>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="to"><i class="fas fa-user"></i> TO Recipients (comma-separated)</label>
            <input type="text" 
                   name="to" 
                   id="to" 
                   class="form-control" 
                   placeholder="email1@example.com, email2@example.com"
                   value="<?= h($authUser['email'] ?? '') ?>"
                   required
                   style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem;">
            <small style="color: #6b7280;">Enter one or more email addresses separated by commas</small>
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="cc"><i class="fas fa-copy"></i> CC Recipients (optional, comma-separated)</label>
            <input type="text" 
                   name="cc" 
                   id="cc" 
                   class="form-control" 
                   placeholder="cc1@example.com, cc2@example.com"
                   style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem;">
            <small style="color: #6b7280;">Optional: Add CC recipients</small>
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="subject"><i class="fas fa-heading"></i> Subject</label>
            <input type="text" 
                   name="subject" 
                   id="subject" 
                   class="form-control" 
                   value="Test Email from Treasury Invoice System"
                   required
                   style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem;">
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="body"><i class="fas fa-align-left"></i> Email Body (HTML supported)</label>
            <textarea name="body" 
                      id="body" 
                      rows="10" 
                      class="form-control"
                      required
                      style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; font-family: monospace;"><h1>Test Email</h1>
<p>This is a test email sent via <strong>Microsoft Graph API</strong> from the Treasury Invoice System.</p>

<h2>Features Tested:</h2>
<ul>
    <li>HTML Email Content</li>
    <li>Multiple Recipients</li>
    <li>CC Recipients</li>
    <li>Microsoft Graph Integration</li>
</ul>

<p style="color: #0c5343; font-weight: bold;">If you received this email, the integration is working correctly!</p>

<hr>
<p style="color: #6b7280; font-size: 0.875rem;">Sent from Treasury Invoice System | Powered by Microsoft Graph</p></textarea>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.875rem 1.75rem;">
                <i class="fas fa-paper-plane"></i> Send Test Email
            </button>
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Tests', ['action' => 'index'], [
                'class' => 'btn',
                'style' => 'background: #6b7280; color: white; padding: 0.875rem 1.75rem;',
                'escape' => false
            ]) ?>
        </div>
        
        <?= $this->Form->end() ?>
    </div>
    
    <div class="test-section">
        <h3><i class="fas fa-info-circle"></i> Notes</h3>
        <ul>
            <li>The email will be sent from <strong><?= h($authUser['email'] ?? 'your account') ?></strong></li>
            <li>Emails are sent via Microsoft Graph API using the <code>Mail.Send</code> permission</li>
            <li>HTML content is supported in the email body</li>
            <li>The email will be saved to your Sent Items folder</li>
        </ul>
    </div>
</div>
