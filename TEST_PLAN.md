# 🧪 Travel Request System - Complete Test Plan

## Test Environment
- **Server URL:** http://localhost:8765
- **Date:** November 8, 2025
- **Azure AD:** Configured ✅
- **Database:** travel_requests ✅

---

## 📋 Pre-Test Checklist

### Azure AD App Permissions Required
- [ ] User.Read
- [ ] User.ReadBasic.All
- [ ] Chat.Create
- [ ] Chat.ReadWrite
- [ ] ChatMessage.Send
- [ ] Mail.Send

**⚠️ Important:** Go to Azure Portal → App Registrations → Your App → API Permissions and ensure these are granted with admin consent.

---

## 🧪 Test Scenarios

### Test 1: Login & Authentication
**Objective:** Verify Microsoft OAuth login works

**Steps:**
1. Navigate to http://localhost:8765
2. Click "Sign In" button
3. Authenticate with Microsoft credentials
4. Verify redirect back to application
5. Check session is established

**Expected Result:**
- ✅ Successful login
- ✅ User name displayed in header
- ✅ Access to protected pages

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 2: Add Travel Request Form
**Objective:** Verify all form features work

**Steps:**
1. Navigate to Travel Requests → Add New
2. Test file upload:
   - Click file upload area
   - Select a PDF file
   - Verify file name displays
   - Test remove file button
3. Test traveler selection:
   - Click "Browse" button
   - Verify modal opens
   - Search for a user
   - Select a traveler
   - Verify selection displays
4. Test line manager selection:
   - Click "Browse" button for line manager
   - Verify modal opens
   - Search for a manager
   - Select a line manager
   - Verify selection displays
5. Fill in all required fields:
   - Purpose of travel
   - Destination
   - Travel type (Local/International)
   - Departure date
   - Return date
   - Accommodation checkbox
6. Verify duration calculation appears
7. Submit the form

**Expected Result:**
- ✅ File upload works
- ✅ User modals load Microsoft Graph users
- ✅ Search functionality works
- ✅ Duration calculates automatically
- ✅ Form submits successfully
- ✅ Success message displayed

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 3: Line Manager Notification (Primary Test)
**Objective:** Verify line manager receives approval notification

**Steps:**
1. After submitting travel request, check Flash message
2. Note the success message about notification
3. Check the line manager's account:
   - **Teams:** Open Microsoft Teams
   - Look for new chat message
   - Verify adaptive card displays correctly
   - Check all details are present
   - Verify action buttons exist

**Expected Result:**
- ✅ Flash message confirms notification sent
- ✅ Line manager receives Teams message
- ✅ Adaptive card shows all travel details
- ✅ Approve/Reject/View buttons present
- ✅ Card formatting is correct

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 4: Email Fallback (if Teams fails)
**Objective:** Verify email notification as fallback

**Steps:**
1. If Teams notification fails, check logs
2. Check line manager's email inbox
3. Verify email received
4. Check email formatting
5. Test action buttons in email

**Expected Result:**
- ✅ Email received if Teams fails
- ✅ Professional HTML formatting
- ✅ All details included
- ✅ Action buttons work

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 5: Approval Flow
**Objective:** Test line manager approval process

**Steps:**
1. Line manager clicks "Approve" in Teams/Email
2. Verify redirect to approval page
3. Add optional comments
4. Click "Approve Request" button
5. Confirm approval
6. Check success message
7. Verify request status updated

**Expected Result:**
- ✅ Approval page loads correctly
- ✅ All details displayed
- ✅ Comments field works
- ✅ Approval saves successfully
- ✅ Status changes to "lm_approved"
- ✅ Success message with emoji

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 6: Requester Approval Notification
**Objective:** Verify employee receives approval notification

**Steps:**
1. After line manager approves, wait a moment
2. Check employee's Microsoft Teams
3. Look for approval notification
4. Verify adaptive card displays
5. Check all details present
6. If Teams fails, check email
7. Verify manager's comments included

**Expected Result:**
- ✅ Employee receives Teams notification
- ✅ Green "good" style card
- ✅ Success message clear
- ✅ Manager comments displayed (if any)
- ✅ Next steps guide included
- ✅ View Details button works

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 7: Rejection Flow
**Objective:** Test line manager rejection process

**Steps:**
1. Submit another travel request
2. Line manager clicks "Reject" in Teams/Email
3. Verify redirect to rejection page
4. Warning message displays
5. Enter rejection reason (required)
6. Click "Confirm Rejection" button
7. Verify confirmation dialog
8. Check success message

**Expected Result:**
- ✅ Rejection page loads correctly
- ✅ Warning box displays
- ✅ Reason field is required
- ✅ Rejection saves successfully
- ✅ Status changes to "lm_rejected"

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 8: Requester Rejection Notification
**Objective:** Verify employee receives rejection notification

**Steps:**
1. After line manager rejects, wait a moment
2. Check employee's Microsoft Teams
3. Look for rejection notification
4. Verify adaptive card displays
5. Check rejection reason is clear
6. If Teams fails, check email
7. Verify next steps guidance

**Expected Result:**
- ✅ Employee receives Teams notification
- ✅ Red "attention" style card
- ✅ Rejection reason prominent
- ✅ Next steps guide included
- ✅ Professional and constructive tone
- ✅ View Details button works

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 9: View Request Details
**Objective:** Verify request view page displays correctly

**Steps:**
1. Click "View Details" from any notification
2. Verify all information displays
3. Check file upload link works
4. Verify status badge correct
5. Check workflow history

**Expected Result:**
- ✅ All details display correctly
- ✅ File download works
- ✅ Status reflects current state
- ✅ Timestamps display

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

### Test 10: Error Handling
**Objective:** Test graceful error handling

**Steps:**
1. Try submitting form without required fields
2. Try uploading non-PDF file
3. Test with expired session
4. Test without selecting users

**Expected Result:**
- ✅ Validation errors display
- ✅ SweetAlert2 warnings show
- ✅ No JavaScript errors
- ✅ Fallbacks work properly

**Status:** [ ] Pass [ ] Fail
**Notes:** _______________

---

## 🔍 Log Monitoring

### Check CakePHP Logs
```bash
# View logs in real-time
tail -f logs/error.log
tail -f logs/debug.log
```

**Look for:**
- Teams notification attempts
- Email fallback triggers
- Any error messages
- Success confirmations

---

## ✅ Test Results Summary

| Test | Status | Notes |
|------|--------|-------|
| 1. Login & Auth | [ ] | |
| 2. Add Request Form | [ ] | |
| 3. LM Notification | [ ] | |
| 4. Email Fallback | [ ] | |
| 5. Approval Flow | [ ] | |
| 6. Requester Approval Notif | [ ] | |
| 7. Rejection Flow | [ ] | |
| 8. Requester Rejection Notif | [ ] | |
| 9. View Details | [ ] | |
| 10. Error Handling | [ ] | |

---

## 🐛 Known Issues / Bugs Found

1. _______________
2. _______________
3. _______________

---

## 📝 Test Completion Notes

**Tested By:** _______________
**Date:** _______________
**Overall Status:** [ ] Pass [ ] Fail [ ] Partial
**Recommendations:** _______________

---

## 🚀 Quick Start Testing

1. **Start Server:** Already running at http://localhost:8765
2. **Login:** Use your Microsoft account
3. **Create Request:** Go to Travel Requests → Add New
4. **Fill Form:** Complete all fields and submit
5. **Check Manager:** Look for Teams notification
6. **Approve/Reject:** Test both flows
7. **Check Employee:** Verify they receive notifications

---

## 🔧 Troubleshooting

### Teams Notifications Not Appearing?
- Check Azure AD permissions granted
- Verify access token is valid
- Check logs for API errors
- Ensure user has Graph ID stored

### Email Not Sending?
- Configure SMTP in config/app_local.php
- Check mailer configuration
- Verify recipient email exists

### Modal Not Loading Users?
- Check browser console for errors
- Verify Graph API token valid
- Check network tab for API calls
- Ensure permissions granted

### Form Not Submitting?
- Check browser console for JS errors
- Verify all required fields filled
- Check CSRF token present
- Review validation errors

---

**Server Running:** ✅ http://localhost:8765
**Ready to Test:** ✅

Good luck with testing! 🎉
