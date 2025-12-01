<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Microsoft Graph Test');
?>

<style>
.test-container {
    max-width: 1200px;
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

.success-badge {
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
    font-weight: 600;
}

.error-badge {
    background: #ef4444;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 1rem;
    margin: 1rem 0;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
}

.user-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    padding: 1rem;
}

.user-item {
    padding: 0.5rem;
    border-bottom: 1px solid #f3f4f6;
}

.user-item:last-child {
    border-bottom: none;
}
</style>

<div class="test-container">
    <h1><i class="fas fa-vial"></i> Microsoft Graph API Test</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="test-section">
            <h3><i class="fas fa-exclamation-triangle"></i> Errors</h3>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= h($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="test-section">
        <h3><i class="fas fa-info-circle"></i> Test Status</h3>
        <?php if ($results['success'] ?? false): ?>
            <span class="success-badge"><i class="fas fa-check-circle"></i> All Tests Passed</span>
        <?php else: ?>
            <span class="error-badge"><i class="fas fa-times-circle"></i> Tests Failed</span>
        <?php endif; ?>
        
        <div class="info-grid" style="margin-top: 1.5rem;">
            <div class="info-label">Logged in as:</div>
            <div><?= h($authUser['name'] ?? 'Unknown') ?> (<?= h($authUser['email'] ?? '') ?>)</div>
            
            <div class="info-label">Access Token:</div>
            <div><?= isset($authUser) ? '<span style="color: #10b981;"><i class="fas fa-check"></i> Available</span>' : '<span style="color: #ef4444;"><i class="fas fa-times"></i> Missing</span>' ?></div>
        </div>
    </div>
    
    <?php if ($results['success'] ?? false): ?>
        
        <!-- Test 1: User Count -->
        <div class="test-section">
            <h3><i class="fas fa-users"></i> Test 1: Fetch All Users</h3>
            <div class="info-grid">
                <div class="info-label">Total Users:</div>
                <div><strong><?= h($results['userCount'] ?? 0) ?></strong> users fetched from Azure AD</div>
            </div>
            
            <?php if (!empty($results['users'])): ?>
                <h4>Sample Users (first 10):</h4>
                <div class="user-list">
                    <?php foreach (array_slice($results['users'], 0, 10) as $user): ?>
                        <div class="user-item">
                            <i class="fas fa-user"></i> <strong><?= h($user['name']) ?></strong><br>
                            <small>
                                <i class="fas fa-envelope"></i> <?= h($user['email']) ?>
                                <?php if (!empty($user['jobTitle'])): ?>
                                    | <i class="fas fa-briefcase"></i> <?= h($user['jobTitle']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Test 2: Dropdown Format -->
        <div class="test-section">
            <h3><i class="fas fa-list"></i> Test 2: Users for Dropdown</h3>
            <div class="info-grid">
                <div class="info-label">Dropdown Count:</div>
                <div><strong><?= h($results['dropdownCount'] ?? 0) ?></strong> users formatted for dropdowns</div>
            </div>
            
            <h4>Sample Dropdown Format:</h4>
            <select class="form-control" style="max-width: 500px;">
                <option value="">-- Select User --</option>
                <?php foreach (array_slice($results['dropdown'], 0, 10, true) as $id => $display): ?>
                    <option value="<?= h($id) ?>"><?= h($display) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Test 3: My Profile -->
        <div class="test-section">
            <h3><i class="fas fa-id-card"></i> Test 3: Current User Profile</h3>
            <?php if (!empty($results['myProfile'])): ?>
                <div class="info-grid">
                    <div class="info-label">Name:</div>
                    <div><?= h($results['myProfile']['name'] ?? '') ?></div>
                    
                    <div class="info-label">Email:</div>
                    <div><?= h($results['myProfile']['email'] ?? '') ?></div>
                    
                    <div class="info-label">Job Title:</div>
                    <div><?= h($results['myProfile']['jobTitle'] ?? 'N/A') ?></div>
                    
                    <div class="info-label">Department:</div>
                    <div><?= h($results['myProfile']['department'] ?? 'N/A') ?></div>
                    
                    <div class="info-label">User ID:</div>
                    <div><code><?= h($results['myProfile']['id'] ?? '') ?></code></div>
                </div>
            <?php else: ?>
                <p style="color: #ef4444;">Failed to fetch profile</p>
            <?php endif; ?>
        </div>
        
    <?php endif; ?>
    
    <div class="test-section">
        <h3><i class="fas fa-paper-plane"></i> Test Email Sending</h3>
        <p>Send a test email via Microsoft Graph API</p>
        <?= $this->Html->link('Go to Email Test Page', ['action' => 'sendTestEmail'], [
            'class' => 'btn btn-primary'
        ]) ?>
    </div>
    
    <div class="test-section">
        <h3><i class="fas fa-arrow-left"></i> Navigation</h3>
        <?= $this->Html->link('Back to Home', ['controller' => 'Pages', 'action' => 'display', 'home'], [
            'class' => 'btn'
        ]) ?>
        <?= $this->Html->link('Admin Dashboard', ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'], [
            'class' => 'btn'
        ]) ?>
    </div>
</div>
