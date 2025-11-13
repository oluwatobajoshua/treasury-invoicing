<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TravelRequest $travelRequest
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $jobLevels
 */
$this->assign('title', 'New Travel Request');
?>
<style>
    .add-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 0.7rem 1rem;
        border-radius: 6px;
        margin-bottom: 0.6rem;
        box-shadow: 0 3px 12px rgba(12, 83, 67, 0.12);
        text-align: center;
    }
    .add-header h1 {
        margin: 0 0 0.2rem 0;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .add-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 0.7rem;
    }
    .form-container {
        background: white;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        border: 1px solid var(--gray-200);
        padding: 0.8rem;
        margin-bottom: 0.6rem;
    }
    .info-card-highlight {
        background: linear-gradient(135deg, #e8f5f1 0%, #d4e9e2 100%);
        border-left: 3px solid var(--primary);
        padding: 0.6rem;
        border-radius: 4px;
        margin-bottom: 0.8rem;
    }
    .info-card-highlight h3 {
        color: var(--primary);
        margin: 0 0 0.25rem 0;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .info-card-highlight p {
        margin: 0;
        line-height: 1.5;
        font-size: 0.7rem;
    }
    .file-upload-area {
        border: 2px dashed var(--gray-300);
        border-radius: 6px;
        padding: 0.8rem;
        text-align: center;
        background: var(--gray-50);
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 0.6rem;
    }
    .file-upload-area:hover {
        border-color: var(--primary);
        background: rgba(12, 83, 67, 0.02);
    }
    .file-upload-area.drag-over {
        border-color: var(--accent);
        background: rgba(246, 69, 0, 0.04);
        transform: scale(1.01);
    }
    .file-icon {
        font-size: 1.8rem;
        margin-bottom: 0.4rem;
        color: var(--primary);
    }
    .file-upload-text {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text);
        font-size: 0.75rem;
    }
    .file-upload-hint {
        font-size: 0.7rem;
        color: var(--muted);
    }
    .file-selected {
        background: var(--success-bg);
        border-color: var(--success);
        padding: 0.5rem;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }
    .file-selected-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .form-full {
        grid-column: 1 / -1;
    }
    .duration-display {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 0.9rem;
        border-radius: 6px;
        text-align: center;
        margin: 0.75rem 0;
    }
    .duration-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.35rem;
    }
    .duration-label {
        font-size: 0.8rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    @media (max-width: 768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        .form-grid-3 {
            grid-template-columns: 1fr;
        }
        .progress-steps {
            overflow-x: auto;
            justify-content: flex-start;
            padding-bottom: 0.5rem;
        }
        .add-header {
            padding: 1rem;
        }
        .add-header h1 {
            font-size: 1.3rem;
        }
        .add-header p {
            font-size: 0.8rem;
        }
        .progress-circle {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
        .progress-label {
            font-size: 0.65rem;
        }
        .file-upload-area {
            padding: 1rem;
        }
        .file-icon {
            font-size: 2rem;
        }
        .duration-number {
            font-size: 1.75rem;
        }
    }
    @media (max-width: 640px) {
        .add-header {
            padding: 0.85rem 0.75rem;
        }
        .add-header h1 {
            font-size: 1.2rem;
        }
        .progress-steps {
            gap: 0.75rem;
            padding: 0 0.5rem 0.5rem;
        }
        .form-container {
            padding: 1rem;
        }
        .info-card-highlight {
            padding: 0.75rem;
        }
        .info-card-highlight h3 {
            font-size: 0.85rem;
        }
        .info-card-highlight p {
            font-size: 0.75rem;
        }
    }
</style>

<?= $this->element('progress_tracker', ['currentStep' => 1]) ?>

<div class="add-header">
    <h1>✈️ Create New Travel Request</h1>
    <p>Complete the form below to submit your travel request for approval</p>
</div>

<div class="form-container">
    <?= $this->Form->create($travelRequest, ['type' => 'file', 'id' => 'travelRequestForm']) ?>
    
    <div class="info-card-highlight">
        <h3>📧 Step 1: Email Conversation Required</h3>
        <p>
            Before submitting this request, you must have discussed your travel plans with your Line Manager via email. 
            Please upload a PDF of this email conversation showing their informal approval or acknowledgment.
            <strong>This is required for all travel requests.</strong>
        </p>
    </div>

    <div class="form-group">
        <label style="font-weight: 600; font-size: 1.05rem; margin-bottom: 0.75rem; display: block;">
            📎 Email Conversation PDF <span style="color: var(--danger);">*</span>
        </label>
        
        <div class="file-upload-area" id="fileUploadArea">
            <div class="file-icon">📄</div>
            <div class="file-upload-text">Click to browse or drag & drop your PDF file here</div>
            <div class="file-upload-hint">Maximum file size: 5MB | Format: PDF only</div>
            <?= $this->Form->file('email_conversation', [
                'id' => 'fileInput',
                'accept' => '.pdf',
                'required' => true,
                'style' => 'display: none;'
            ]) ?>
        </div>
        
        <div id="fileSelected" class="file-selected" style="display: none;">
            <div class="file-selected-info">
                <span style="font-size: 2rem;">📄</span>
                <div>
                    <div style="font-weight: 600;" id="fileName"></div>
                    <div style="font-size: 0.85rem; color: var(--muted);" id="fileSize"></div>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" id="removeFile">🗑️ Remove</button>
        </div>
    </div>

    <hr style="margin: 2rem 0; border: none; border-top: 2px solid var(--gray-200);">

    <h3 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.25rem;">
        � Traveler Information
    </h3>

    <div class="form-grid-3">
        <div class="form-group">
            <label>👤 Select Traveler <span style="color: var(--danger);">*</span></label>
            <div style="position: relative;">
                <input type="text" id="selectedUserDisplay" readonly 
                       placeholder="Click to select traveler from Microsoft directory..."
                       required
                       style="width: 100%; padding: 0.75rem; padding-right: 3rem; border: 1px solid var(--gray-300); border-radius: 6px; background: white; cursor: pointer;"
                       onclick="openUserModal()">
                <button type="button" onclick="openUserModal()" 
                        style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: var(--primary); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">
                    🔍 Browse
                </button>
            </div>
            <input type="hidden" name="user_id" id="userId" required>
            <input type="hidden" name="user_email" id="userEmail">
            <input type="hidden" name="user_graph_id" id="userGraphId">
            <input type="hidden" name="user_display_name" id="userDisplayName">
            <small style="display: block; margin-top: 0.5rem; color: var(--muted);">
                Select the employee traveling.
            </small>
        </div>

        <div class="form-group">
            <label>👔 Select Line Manager <span style="color: var(--danger);">*</span></label>
            <div style="position: relative;">
                <input type="text" id="selectedLineManagerDisplay" readonly 
                       placeholder="Click to select line manager from Microsoft directory..."
                       required
                       style="width: 100%; padding: 0.75rem; padding-right: 3rem; border: 1px solid var(--gray-300); border-radius: 6px; background: white; cursor: pointer;"
                       onclick="openLineManagerModal()">
                <button type="button" onclick="openLineManagerModal()" 
                        style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: var(--primary); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">
                    🔍 Browse
                </button>
            </div>
            <input type="hidden" name="line_manager_id" id="lineManagerId" required>
            <input type="hidden" name="line_manager_email" id="lineManagerEmail">
            <input type="hidden" name="line_manager_graph_id" id="lineManagerGraphId">
            <input type="hidden" name="line_manager_display_name" id="lineManagerDisplayName">
            <small style="display: block; margin-top: 0.5rem; color: var(--muted);">
                Manager who approves request.
            </small>
        </div>

        <div class="form-group">
            <label>💼 Job Level <span style="color: var(--danger);">*</span></label>
            <?= $this->Form->control('job_level_id', [
                'options' => $jobLevels,
                'empty' => '-- Select Job Level --',
                'required' => true,
                'label' => false,
                'class' => 'form-control'
            ]) ?>
            <small style="display: block; margin-top: 0.5rem; color: var(--muted);">
                Determines travel allowances.
            </small>
        </div>
    </div>

    <hr style="margin: 2rem 0; border: none; border-top: 2px solid var(--gray-200);">

    <h3 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.25rem;">
        �� Travel Information
    </h3>

    <div class="form-group form-full">
        <label>📝 Purpose of Travel <span style="color: var(--danger);">*</span></label>
        <?= $this->Form->textarea('purpose_of_travel', [
            'placeholder' => 'Describe the business purpose, objectives, and expected outcomes of this travel...',
            'required' => true,
            'rows' => 4
        ]) ?>
    </div>

    <div class="form-grid-2">
        <div class="form-group">
            <label>📍 Destination <span style="color: var(--danger);">*</span></label>
            <?= $this->Form->text('destination', [
                'placeholder' => 'e.g., Lagos, Nigeria',
                'required' => true
            ]) ?>
        </div>

        <div class="form-group">
            <label>✈️ Travel Type <span style="color: var(--danger);">*</span></label>
            <?= $this->Form->select('travel_type', [
                'local' => '🇳🇬 Local Travel (Nigeria)',
                'international' => '🌍 International Travel'
            ], [
                'empty' => '-- Select travel type --',
                'required' => true,
                'id' => 'travelType'
            ]) ?>
        </div>
    </div>

    <div class="form-grid-2">
        <div class="form-group">
            <label>🛫 Departure Date <span style="color: var(--danger);">*</span></label>
            <?= $this->Form->date('departure_date', [
                'id' => 'departureDate',
                'required' => true,
                'min' => date('Y-m-d')
            ]) ?>
        </div>

        <div class="form-group">
            <label>🛬 Return Date <span style="color: var(--danger);">*</span></label>
            <?= $this->Form->date('return_date', [
                'id' => 'returnDate',
                'required' => true,
                'min' => date('Y-m-d')
            ]) ?>
        </div>
    </div>

    <div class="duration-display" id="durationDisplay" style="display: none;">
        <div class="duration-number" id="durationDays">0</div>
        <div class="duration-label">Days Duration</div>
    </div>

    <?= $this->Form->hidden('duration_days', ['id' => 'durationDaysHidden']) ?>

    <div class="form-group">
        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
            <?= $this->Form->checkbox('accommodation_required', [
                'id' => 'accommodation'
            ]) ?>
            <span>🛏️ Accommodation Required</span>
        </label>
        <small style="margin-left: 2.25rem; display: block; color: var(--muted);">
            Check this box if you need hotel accommodation during your travel
        </small>
    </div>

    <hr style="margin: 2rem 0; border: none; border-top: 2px solid var(--gray-200);">

    <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.25rem;">
        💰 Travel Allowances
    </h3>

    <div class="info-card-highlight">
        <h3>ℹ️ Allowance Calculation Process</h3>
        <p>
            Allowances will be automatically calculated by the admin <strong>after Line Manager approval</strong>. 
            The calculation will be based on the selected traveler's job level, travel type, and duration.
            You don't need to enter any financial details at this stage.
        </p>
    </div>

    <div class="form-group form-full">
        <label>📌 Additional Notes (Optional)</label>
        <?= $this->Form->textarea('notes', [
            'placeholder' => 'Any additional information, special requirements, or important details...',
            'rows' => 3
        ]) ?>
    </div>

    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--gray-200);">
        <?= $this->Html->link('❌ Cancel', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        <?= $this->Form->button('📤 Submit Travel Request', ['class' => 'btn btn-primary', 'style' => 'font-size: 1.05rem;']) ?>
    </div>

    <?= $this->Form->end() ?>
</div>

<!-- User Selection Modal -->
<div id="userModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2>👥 Select Traveler from Microsoft Directory</h2>
            <button onclick="closeUserModal()" class="modal-close">&times;</button>
        </div>
        
        <div class="modal-search">
            <div style="position: relative;">
                <input type="text" id="userSearchInput" placeholder="🔍 Search by name, email, or job title..." 
                       style="width: 100%; padding: 0.75rem 1rem; padding-left: 2.5rem; border: 2px solid var(--gray-300); border-radius: 8px; font-size: 1rem;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted);">🔍</span>
            </div>
            <div id="searchStats" style="margin-top: 0.5rem; color: var(--muted); font-size: 0.85rem;"></div>
        </div>
        
        <div id="userListContainer" class="modal-content">
            <div id="loadingUsers" class="loading-state">
                <div class="spinner"></div>
                <p>Loading users from Microsoft Graph...</p>
            </div>
            <div id="userList" style="display: none;"></div>
            <div id="noResults" style="display: none; text-align: center; padding: 3rem; color: var(--muted);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                <p>No users found matching your search.</p>
            </div>
        </div>
        
        <div class="modal-footer">
            <button onclick="closeUserModal()" class="btn btn-outline">Cancel</button>
        </div>
    </div>
</div>

<!-- Line Manager Selection Modal -->
<div id="lineManagerModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2>👔 Select Line Manager from Microsoft Directory</h2>
            <button onclick="closeLineManagerModal()" class="modal-close">&times;</button>
        </div>
        
        <div class="modal-search">
            <div style="position: relative;">
                <input type="text" id="lineManagerSearchInput" placeholder="🔍 Search by name, email, or job title..." 
                       style="width: 100%; padding: 0.75rem 1rem; padding-left: 2.5rem; border: 2px solid var(--gray-300); border-radius: 8px; font-size: 1rem;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted);">🔍</span>
            </div>
            <div id="lineManagerSearchStats" style="margin-top: 0.5rem; color: var(--muted); font-size: 0.85rem;"></div>
        </div>
        
        <div id="lineManagerListContainer" class="modal-content">
            <div id="loadingLineManagers" class="loading-state">
                <div class="spinner"></div>
                <p>Loading managers from Microsoft Graph...</p>
            </div>
            <div id="lineManagerList" style="display: none;"></div>
            <div id="noLineManagerResults" style="display: none; text-align: center; padding: 3rem; color: var(--muted);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                <p>No managers found matching your search.</p>
            </div>
        </div>
        
        <div class="modal-footer">
            <button onclick="closeLineManagerModal()" class="btn btn-outline">Cancel</button>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    animation: fadeIn 0.2s ease;
}

.modal-container {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: var(--primary);
    font-size: 1.35rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--muted);
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: var(--gray-100);
    color: var(--danger);
}

.modal-search {
    padding: 1rem 1.5rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
}

.modal-content {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    min-height: 400px;
    max-height: calc(90vh - 250px);
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--gray-200);
    display: flex;
    justify-content: flex-end;
}

.user-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    margin-bottom: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.user-card:hover {
    border-color: var(--primary);
    background: var(--gray-50);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(12, 83, 67, 0.1);
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    flex-shrink: 0;
}

.user-info {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.user-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.user-email {
    color: var(--muted);
    font-size: 0.85rem;
}

.user-job {
    color: var(--primary);
    font-size: 0.85rem;
    font-weight: 500;
}

.user-department {
    color: var(--muted);
    font-size: 0.85rem;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    color: var(--muted);
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--gray-200);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .modal-container {
        max-width: 100%;
        max-height: 100vh;
        border-radius: 0;
    }
    
    .user-card {
        flex-direction: column;
        text-align: center;
    }
    
    .user-details {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let allUsers = [];
    let filteredUsers = [];
    
    // Load users from Microsoft Graph on page load
    loadGraphUsers();
    
    const fileInput = document.getElementById('fileInput');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileSelected = document.getElementById('fileSelected');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');
    
    // Load users from Microsoft Graph API
    function loadGraphUsers() {
        fetch('<?= $this->Url->build(['controller' => 'Auth', 'action' => 'getGraphUsers']) ?>')
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned invalid response. Please try logging in again.');
                }
                return response.json();
            })
            .then(data => {
                const loadingDiv = document.getElementById('loadingUsers');
                
                if (data.error) {
                    loadingDiv.innerHTML = `
                        <div style="text-align: center; color: var(--danger);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                            <p><strong>Error loading users</strong></p>
                            <p style="font-size: 0.9rem; color: var(--muted);">${data.error}</p>
                        </div>
                    `;
                    
                    // Show error notification
                    if (typeof showError === 'function') {
                        showError(data.error, 'Failed to Load Users');
                    } else {
                        alert('Error: ' + data.error);
                    }
                    
                    // If session expired, redirect to login after 3 seconds
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = '<?= $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>';
                        }, 3000);
                    }
                    return;
                }
                
                allUsers = data.users || [];
                filteredUsers = allUsers;
                
                console.log(`Loaded ${allUsers.length} users from Microsoft Graph`);
                
                if (allUsers.length === 0) {
                    loadingDiv.innerHTML = `
                        <div style="text-align: center; color: var(--muted);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                            <p>No users found in directory</p>
                        </div>
                    `;
                } else {
                    loadingDiv.style.display = 'none';
                    displayUsers(allUsers);
                    updateSearchStats(allUsers.length, allUsers.length);
                }
            })
            .catch(error => {
                console.error('Error loading users:', error);
                document.getElementById('loadingUsers').innerHTML = `
                    <div style="text-align: center; color: var(--danger);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                        <p><strong>Failed to load users</strong></p>
                        <p style="font-size: 0.9rem;">${error.message}</p>
                    </div>
                `;
                
                // Show error notification
                if (typeof showError === 'function') {
                    showError(error.message, 'Connection Error');
                } else {
                    alert('Connection Error: ' + error.message);
                }
            });
    }
    
    // Display users in the modal
    function displayUsers(users) {
        const userList = document.getElementById('userList');
        userList.style.display = 'block';
        
        if (users.length === 0) {
            document.getElementById('noResults').style.display = 'block';
            userList.innerHTML = '';
            return;
        }
        
        document.getElementById('noResults').style.display = 'none';
        
        userList.innerHTML = users.map(user => {
            const initials = user.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            const jobTitle = user.jobTitle || user.job_title || '';
            return `
                <div class="user-card" onclick="selectUser('${escapeHtml(user.id)}', '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', '${escapeHtml(jobTitle)}')">
                    <div class="user-avatar">${initials}</div>
                    <div class="user-info">
                        <div class="user-name">${escapeHtml(user.name)}</div>
                        <div class="user-details">
                            <span class="user-email">📧 ${escapeHtml(user.email)}</span>
                            ${jobTitle ? `<span class="user-job">💼 ${escapeHtml(jobTitle)}</span>` : ''}
                        </div>
                    </div>
                    <div style="color: var(--primary); font-size: 1.5rem;">→</div>
                </div>
            `;
        }).join('');
    }
    
    // Search functionality
    const searchInput = document.getElementById('userSearchInput');
    searchInput.addEventListener('input', debounce(function() {
        const query = this.value.toLowerCase().trim();
        
        if (!query) {
            filteredUsers = allUsers;
        } else {
            filteredUsers = allUsers.filter(user => {
                const name = (user.name || '').toLowerCase();
                const email = (user.email || '').toLowerCase();
                const jobTitle = (user.jobTitle || user.job_title || '').toLowerCase();
                
                return name.includes(query) || 
                       email.includes(query) || 
                       jobTitle.includes(query);
            });
        }
        
        displayUsers(filteredUsers);
        updateSearchStats(filteredUsers.length, allUsers.length);
    }, 300));
    
    // Update search statistics
    function updateSearchStats(showing, total) {
        const stats = document.getElementById('searchStats');
        if (showing === total) {
            stats.textContent = `Showing all ${total} users`;
        } else {
            stats.textContent = `Showing ${showing} of ${total} users`;
        }
    }
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Modal functions (defined globally so onclick handlers work)
    window.openUserModal = function() {
        document.getElementById('userModal').style.display = 'flex';
        document.getElementById('userSearchInput').focus();
        document.body.style.overflow = 'hidden';
    };
    
    window.closeUserModal = function() {
        document.getElementById('userModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('userSearchInput').value = '';
        filteredUsers = allUsers;
        displayUsers(allUsers);
        updateSearchStats(allUsers.length, allUsers.length);
    };
    
    window.selectUser = function(graphId, name, email, jobTitle) {
        // Set hidden fields
        document.getElementById('userId').value = email; // Use email as user_id
        document.getElementById('userEmail').value = email;
        document.getElementById('userGraphId').value = graphId;
        document.getElementById('userDisplayName').value = name;
        
        // Set display field
        const displayText = jobTitle ? `${name} (${jobTitle})` : name;
        document.getElementById('selectedUserDisplay').value = displayText;
        
        // Close modal
        closeUserModal();
        
        // Show success notification
        if (typeof showSuccess === 'function') {
            showSuccess(`Selected traveler: ${name}`, 'Traveler Selected');
        }
    };
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('userModal').style.display === 'flex') {
            closeUserModal();
        }
        if (e.key === 'Escape' && document.getElementById('lineManagerModal').style.display === 'flex') {
            closeLineManagerModal();
        }
    });
    
    // Close modal on overlay click
    document.getElementById('userModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUserModal();
        }
    });
    
    document.getElementById('lineManagerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLineManagerModal();
        }
    });

    // LINE MANAGER MODAL FUNCTIONS
    let allLineManagers = [];
    let filteredLineManagers = [];
    
    window.openLineManagerModal = function() {
        document.getElementById('lineManagerModal').style.display = 'flex';
        document.getElementById('lineManagerSearchInput').focus();
        document.body.style.overflow = 'hidden';
        
        // Load line managers if not already loaded
        if (allLineManagers.length === 0) {
            loadLineManagers();
        } else {
            displayLineManagers(allLineManagers);
            updateLineManagerSearchStats(allLineManagers.length, allLineManagers.length);
        }
    };
    
    window.closeLineManagerModal = function() {
        document.getElementById('lineManagerModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('lineManagerSearchInput').value = '';
        filteredLineManagers = allLineManagers;
        displayLineManagers(allLineManagers);
        updateLineManagerSearchStats(allLineManagers.length, allLineManagers.length);
    };
    
    window.selectLineManager = function(graphId, name, email, jobTitle) {
        // Set hidden fields
        document.getElementById('lineManagerId').value = email;
        document.getElementById('lineManagerEmail').value = email;
        document.getElementById('lineManagerGraphId').value = graphId;
        document.getElementById('lineManagerDisplayName').value = name;
        
        // Set display field
        const displayText = jobTitle ? `${name} (${jobTitle})` : name;
        document.getElementById('selectedLineManagerDisplay').value = displayText;
        
        // Close modal
        closeLineManagerModal();
        
        // Show success notification
        if (typeof showSuccess === 'function') {
            showSuccess(`Selected line manager: ${name}`, 'Line Manager Selected');
        }
    };
    
    function loadLineManagers() {
        fetch('<?= $this->Url->build(['controller' => 'Auth', 'action' => 'getGraphUsers']) ?>')
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned invalid response. Please try logging in again.');
                }
                return response.json();
            })
            .then(data => {
                const loadingDiv = document.getElementById('loadingLineManagers');
                
                if (data.error) {
                    loadingDiv.innerHTML = `
                        <div style="text-align: center; color: var(--danger);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                            <p><strong>Error loading managers</strong></p>
                            <p style="font-size: 0.9rem; color: var(--muted);">${data.error}</p>
                        </div>
                    `;
                    
                    // Show error notification
                    if (typeof showError === 'function') {
                        showError(data.error, 'Failed to Load Managers');
                    } else {
                        alert('Error: ' + data.error);
                    }
                    
                    // If session expired, redirect to login after 3 seconds
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = '<?= $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>';
                        }, 3000);
                    }
                    return;
                }
                
                allLineManagers = data.users || [];
                filteredLineManagers = allLineManagers;
                
                console.log(`Loaded ${allLineManagers.length} managers from Microsoft Graph`);
                
                if (allLineManagers.length === 0) {
                    loadingDiv.innerHTML = `
                        <div style="text-align: center; color: var(--muted);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                            <p>No managers found in directory</p>
                        </div>
                    `;
                } else {
                    loadingDiv.style.display = 'none';
                    displayLineManagers(allLineManagers);
                    updateLineManagerSearchStats(allLineManagers.length, allLineManagers.length);
                }
            })
            .catch(error => {
                console.error('Error loading managers:', error);
                document.getElementById('loadingLineManagers').innerHTML = `
                    <div style="text-align: center; color: var(--danger);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                        <p><strong>Failed to load managers</strong></p>
                        <p style="font-size: 0.9rem;">${error.message}</p>
                    </div>
                `;
                
                // Show error notification
                if (typeof showError === 'function') {
                    showError(error.message, 'Connection Error');
                } else {
                    alert('Connection Error: ' + error.message);
                }
            });
    }
    
    function displayLineManagers(managers) {
        const lineManagerList = document.getElementById('lineManagerList');
        lineManagerList.style.display = 'block';
        
        if (managers.length === 0) {
            document.getElementById('noLineManagerResults').style.display = 'block';
            lineManagerList.innerHTML = '';
            return;
        }
        
        document.getElementById('noLineManagerResults').style.display = 'none';
        
        lineManagerList.innerHTML = managers.map(manager => {
            const initials = manager.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            const jobTitle = manager.jobTitle || manager.job_title || '';
            return `
                <div class="user-card" onclick="selectLineManager('${escapeHtml(manager.id)}', '${escapeHtml(manager.name)}', '${escapeHtml(manager.email)}', '${escapeHtml(jobTitle)}')">
                    <div class="user-avatar" style="background: linear-gradient(135deg, #f64500, #c93500);">${initials}</div>
                    <div class="user-info">
                        <div class="user-name">${escapeHtml(manager.name)}</div>
                        <div class="user-details">
                            <span class="user-email">📧 ${escapeHtml(manager.email)}</span>
                            ${jobTitle ? `<span class="user-job">💼 ${escapeHtml(jobTitle)}</span>` : ''}
                        </div>
                    </div>
                    <div style="color: var(--accent); font-size: 1.5rem;">→</div>
                </div>
            `;
        }).join('');
    }
    
    // Line Manager search functionality
    const lineManagerSearchInput = document.getElementById('lineManagerSearchInput');
    lineManagerSearchInput.addEventListener('input', debounce(function() {
        const query = this.value.toLowerCase().trim();
        
        if (!query) {
            filteredLineManagers = allLineManagers;
        } else {
            filteredLineManagers = allLineManagers.filter(manager => {
                const name = (manager.name || '').toLowerCase();
                const email = (manager.email || '').toLowerCase();
                const jobTitle = (manager.jobTitle || manager.job_title || '').toLowerCase();
                
                return name.includes(query) || 
                       email.includes(query) || 
                       jobTitle.includes(query);
            });
        }
        
        displayLineManagers(filteredLineManagers);
        updateLineManagerSearchStats(filteredLineManagers.length, allLineManagers.length);
    }, 300));
    
    function updateLineManagerSearchStats(showing, total) {
        const stats = document.getElementById('lineManagerSearchStats');
        if (showing === total) {
            stats.textContent = `Showing all ${total} managers`;
        } else {
            stats.textContent = `Showing ${showing} of ${total} managers`;
        }
    }

    
    // File upload area click
    fileUploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFile(e.target.files[0]);
    });
    
    // Drag and drop
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type === 'application/pdf') {
            fileInput.files = e.dataTransfer.files;
            handleFile(file);
        } else {
            if (typeof showWarning === 'function') {
                showWarning('Please upload a PDF file only.', 'Invalid File Type');
            } else {
                alert('Please upload a PDF file only.');
            }
        }
    });
    
    function handleFile(file) {
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileUploadArea.style.display = 'none';
            fileSelected.style.display = 'flex';
        }
    }
    
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        fileUploadArea.style.display = 'block';
        fileSelected.style.display = 'none';
    });
    
    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }
    
    // Calculate duration
    const departureDate = document.getElementById('departureDate');
    const returnDate = document.getElementById('returnDate');
    const durationDisplay = document.getElementById('durationDisplay');
    const durationDays = document.getElementById('durationDays');
    const durationDaysHidden = document.getElementById('durationDaysHidden');
    
    function calculateDuration() {
        if (departureDate.value && returnDate.value) {
            const departure = new Date(departureDate.value);
            const returnD = new Date(returnDate.value);
            
            if (returnD >= departure) {
                const diffTime = Math.abs(returnD - departure);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                durationDays.textContent = diffDays;
                durationDaysHidden.value = diffDays;
                durationDisplay.style.display = 'block';
            } else {
                if (typeof showWarning === 'function') {
                    showWarning('Return date must be on or after departure date', 'Invalid Date Range');
                } else {
                    alert('Return date must be on or after departure date');
                }
                returnDate.value = '';
                durationDisplay.style.display = 'none';
            }
        }
    }
    
    departureDate.addEventListener('change', calculateDuration);
    returnDate.addEventListener('change', calculateDuration);
    
    // Form validation feedback
    const form = document.getElementById('travelRequestForm');
    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            if (typeof showWarning === 'function') {
                showWarning('Please upload the email conversation PDF before submitting.', 'Missing Required File');
            } else {
                alert('Please upload the email conversation PDF before submitting.');
            }
            fileUploadArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
        
        // Show loading state
        if (typeof showLoading === 'function') {
            showLoading('Submitting Request...', 'Please wait while we process your travel request');
        }
    });
});
</script>
