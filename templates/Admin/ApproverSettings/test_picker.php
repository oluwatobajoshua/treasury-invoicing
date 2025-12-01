<!DOCTYPE html>
<html>
<head>
    <title>Test User Picker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; }
        .btn-primary { background: #3b82f6; color: white; }
        #debug { margin-top: 2rem; padding: 1rem; background: #f3f4f6; border-radius: 8px; }
        #email-field { width: 100%; padding: 0.75rem; margin: 1rem 0; }
    </style>
</head>
<body>
    <h1>Test Azure AD User Picker</h1>
    
    <div>
        <button type="button" id="test-fetch" class="btn btn-primary">
            <i class="fas fa-download"></i> Test Fetch Users
        </button>
        
        <button type="button" id="test-picker" class="btn btn-primary">
            <i class="fas fa-users"></i> Open User Picker
        </button>
    </div>
    
    <textarea id="email-field" rows="3" placeholder="Selected emails will appear here..."></textarea>
    
    <div id="debug">
        <h3>Debug Log:</h3>
        <pre id="log"></pre>
    </div>

    <script>
        let graphUsers = [];
        
        function log(message) {
            const logEl = document.getElementById('log');
            logEl.textContent += new Date().toLocaleTimeString() + ': ' + message + '\n';
            console.log(message);
        }
        
        // Test fetch
        document.getElementById('test-fetch').addEventListener('click', function() {
            log('Fetching users from /auth/get-graph-users...');
            
            fetch('/auth/get-graph-users')
                .then(response => {
                    log('Response received, status: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    log('Data parsed: ' + JSON.stringify(data).substring(0, 200));
                    
                    if (data.error) {
                        log('ERROR: ' + data.error);
                        alert('Error: ' + data.error);
                        if (data.redirect) {
                            log('Redirecting to login...');
                            window.location.href = '/auth/login';
                        }
                        return;
                    }
                    
                    graphUsers = data.users || [];
                    log('SUCCESS: Loaded ' + graphUsers.length + ' users');
                    alert('Successfully loaded ' + graphUsers.length + ' users from Azure AD!');
                })
                .catch(error => {
                    log('FETCH ERROR: ' + error.message);
                    alert('Fetch failed: ' + error.message);
                });
        });
        
        // Test picker
        document.getElementById('test-picker').addEventListener('click', function() {
            if (graphUsers.length === 0) {
                alert('Please fetch users first!');
                return;
            }
            
            log('Opening user picker with ' + graphUsers.length + ' users...');
            showUserPicker();
        });
        
        function showUserPicker() {
            const modalHtml = `
                <div id="user-picker-modal" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;">
                    <div style="background:white;border-radius:12px;max-width:800px;width:90%;max-height:80vh;display:flex;flex-direction:column;">
                        <div style="padding:1.5rem;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
                            <h3 style="margin:0;"><i class="fas fa-users"></i> Select Users from Azure AD</h3>
                            <button type="button" id="close-picker" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#6b7280;">&times;</button>
                        </div>
                        <div style="padding:1rem;border-bottom:1px solid #e5e7eb;">
                            <input type="text" id="user-search" placeholder="Search by name or email..." style="width:100%;padding:0.75rem;border:2px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div id="user-list" style="flex:1;overflow-y:auto;padding:1rem;max-height:400px;">
                            <!-- Users will be populated here -->
                        </div>
                        <div style="padding:1.5rem;border-top:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
                            <div id="selected-count" style="color:#6b7280;font-size:0.875rem;"></div>
                            <div style="display:flex;gap:0.5rem;">
                                <button type="button" id="cancel-picker" style="background:#6b7280;color:white;padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Cancel</button>
                                <button type="button" id="confirm-picker" style="background:#3b82f6;color:white;padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Add Selected</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            log('Modal inserted into DOM');
            
            // Populate user list
            renderUserList(graphUsers);
            
            // Setup search
            document.getElementById('user-search').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const filtered = graphUsers.filter(user => 
                    user.name.toLowerCase().includes(searchTerm) || 
                    user.email.toLowerCase().includes(searchTerm)
                );
                log('Search: "' + searchTerm + '" - Found ' + filtered.length + ' users');
                renderUserList(filtered);
            });
            
            // Close buttons
            document.getElementById('close-picker').addEventListener('click', closeUserPicker);
            document.getElementById('cancel-picker').addEventListener('click', closeUserPicker);
            
            // Confirm button
            document.getElementById('confirm-picker').addEventListener('click', function() {
                const selected = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                    .map(cb => cb.dataset.email);
                
                log('Confirmed: ' + selected.length + ' users selected');
                
                if (selected.length > 0) {
                    const field = document.getElementById('email-field');
                    const currentValue = field.value.trim();
                    const newValue = currentValue ? currentValue + ', ' + selected.join(', ') : selected.join(', ');
                    field.value = newValue;
                    log('Added emails to field: ' + selected.join(', '));
                }
                
                closeUserPicker();
            });
        }
        
        function renderUserList(users) {
            const userList = document.getElementById('user-list');
            log('Rendering ' + users.length + ' users');
            
            if (users.length === 0) {
                userList.innerHTML = '<p style="text-align:center;color:#6b7280;padding:2rem;">No users found</p>';
                return;
            }
            
            const html = users.map(user => `
                <label style="display:flex;align-items:center;padding:0.75rem;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:0.5rem;cursor:pointer;transition:all 0.2s;" class="user-item">
                    <input type="checkbox" class="user-checkbox" data-email="${user.email}" style="width:18px;height:18px;margin-right:1rem;">
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#111827;"><i class="fas fa-user"></i> ${user.name}</div>
                        <div style="font-size:0.875rem;color:#6b7280;"><i class="fas fa-envelope"></i> ${user.email}</div>
                        ${user.jobTitle ? '<div style="font-size:0.75rem;color:#9ca3af;"><i class="fas fa-briefcase"></i> ' + user.jobTitle + '</div>' : ''}
                    </div>
                </label>
            `).join('');
            
            userList.innerHTML = html;
            
            // Update selected count
            updateSelectedCount();
            
            // Add hover effects
            document.querySelectorAll('.user-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.background = '#f9fafb';
                    this.style.borderColor = '#3b82f6';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.background = 'white';
                    this.style.borderColor = '#e5e7eb';
                });
            });
            
            // Add change listeners
            document.querySelectorAll('.user-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSelectedCount);
            });
            
            log('User list rendered successfully');
        }
        
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.user-checkbox:checked').length;
            const countEl = document.getElementById('selected-count');
            if (countEl) {
                countEl.textContent = selected > 0 ? selected + ' user(s) selected' : 'No users selected';
            }
        }
        
        function closeUserPicker() {
            const modal = document.getElementById('user-picker-modal');
            if (modal) {
                modal.remove();
                log('Modal closed');
            }
        }
    </script>
</body>
</html>
