<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #28a745 0%, #20793a 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        
        .email-header .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .email-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .email-header p {
            margin: 0;
            opacity: 0.95;
            font-size: 1rem;
        }
        
        .email-body {
            padding: 2rem;
        }
        
        .success-message {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .success-message h2 {
            color: #155724;
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }
        
        .success-message p {
            color: #155724;
            margin: 0;
            font-size: 1rem;
        }
        
        .details-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .details-box h3 {
            color: #28a745;
            margin: 0 0 1rem 0;
            font-size: 1.1rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .detail-value {
            color: #212529;
            text-align: right;
        }
        
        .comments-section {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            border-radius: 4px;
            margin: 1.5rem 0;
        }
        
        .comments-section h4 {
            color: #856404;
            margin: 0 0 0.5rem 0;
            font-size: 1rem;
        }
        
        .comments-section p {
            color: #856404;
            margin: 0;
        }
        
        .next-steps {
            background: #e7f3ff;
            border-left: 4px solid #0c5343;
            padding: 1.5rem;
            border-radius: 4px;
            margin: 1.5rem 0;
        }
        
        .next-steps h3 {
            color: #0c5343;
            margin: 0 0 1rem 0;
            font-size: 1.1rem;
        }
        
        .next-steps ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #0a4636;
        }
        
        .next-steps li {
            margin: 0.5rem 0;
        }
        
        .action-button {
            text-align: center;
            margin: 2rem 0;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: #0c5343;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        
        .btn:hover {
            background: #0a4636;
        }
        
        .email-footer {
            background: #f8f9fa;
            padding: 1.5rem;
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            border-top: 1px solid #e9ecef;
        }
        
        .email-footer p {
            margin: 0.5rem 0;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .email-header {
                padding: 2rem 1.5rem;
            }
            
            .email-header h1 {
                font-size: 1.5rem;
            }
            
            .email-body {
                padding: 1.5rem;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-value {
                text-align: left;
                margin-top: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">✅</div>
            <h1>Request Approved!</h1>
            <p>Your travel request has been approved</p>
        </div>
        
        <div class="email-body">
            <div class="success-message">
                <h2>🎉 Great News!</h2>
                <p>Your line manager has approved your travel request. The admin team will now process allowances and finalize the details.</p>
            </div>
            
            <div class="details-box">
                <h3>📋 Travel Request Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Request Number:</span>
                    <span class="detail-value"><strong><?= h($travelRequest->request_number) ?></strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Destination:</span>
                    <span class="detail-value"><?= h($travelRequest->destination) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Travel Type:</span>
                    <span class="detail-value"><?= h(ucfirst($travelRequest->travel_type)) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Departure Date:</span>
                    <span class="detail-value"><?= $travelRequest->departure_date->format('F d, Y') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Return Date:</span>
                    <span class="detail-value"><?= $travelRequest->return_date->format('F d, Y') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value"><?= h($travelRequest->duration_days) ?> days</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #28a745; font-weight: bold;">✅ Approved</span>
                </div>
            </div>
            
            <?php if (!empty($travelRequest->line_manager_comments)): ?>
            <div class="comments-section">
                <h4>💬 Manager's Comments</h4>
                <p><?= nl2br(h($travelRequest->line_manager_comments)) ?></p>
            </div>
            <?php endif; ?>
            
            <div class="next-steps">
                <h3>📌 Next Steps</h3>
                <ul>
                    <li><strong>Admin Review:</strong> The admin team will now calculate your travel allowances based on your job level and travel type.</li>
                    <li><strong>Allowance Calculation:</strong> You'll receive another notification once allowances are calculated and finalized.</li>
                    <li><strong>Travel Arrangements:</strong> You can begin making preliminary travel arrangements, but wait for final approval before booking.</li>
                    <li><strong>Stay Updated:</strong> Check the system regularly for updates on your request status.</li>
                </ul>
            </div>
            
            <div class="action-button">
                <a href="<?= $viewUrl ?>" class="btn">👁️ View Request Details</a>
            </div>
            
            <p style="color: #6c757d; font-size: 0.9rem; text-align: center; margin-top: 2rem;">
                You will receive further notifications as your request progresses through the approval workflow.
            </p>
        </div>
        
        <div class="email-footer">
            <p><strong>Travel Request Management System</strong></p>
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>If you have questions, please contact your line manager or the admin team.</p>
            <p>&copy; <?= date('Y') ?> All rights reserved.</p>
        </div>
    </div>
</body>
</html>
