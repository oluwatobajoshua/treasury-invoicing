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
            background: linear-gradient(135deg, #0c5343 0%, #0a4636 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .email-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .email-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .email-body {
            padding: 2rem;
        }
        
        .greeting {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .details-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
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
        
        .purpose-section {
            margin: 1.5rem 0;
        }
        
        .purpose-section h3 {
            color: #0c5343;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .purpose-text {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #0c5343;
        }
        
        .action-buttons {
            margin: 2rem 0;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            margin: 0.5rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }
        
        .btn-approve {
            background: #28a745;
            color: white !important;
        }
        
        .btn-approve:hover {
            background: #218838;
        }
        
        .btn-reject {
            background: #dc3545;
            color: white !important;
        }
        
        .btn-reject:hover {
            background: #c82333;
        }
        
        .btn-view {
            background: #17a2b8;
            color: white !important;
        }
        
        .btn-view:hover {
            background: #138496;
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
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-value {
                text-align: left;
                margin-top: 0.25rem;
            }
            
            .btn {
                display: block;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>✈️ Travel Request Approval Needed</h1>
            <p>You have a new travel request pending your approval</p>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                Hello <?= h($lineManager->first_name ?? 'Manager') ?>,
            </div>
            
            <p>
                A new travel request has been submitted and requires your approval. 
                Please review the details below and take appropriate action.
            </p>
            
            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">📋 Request Number:</span>
                    <span class="detail-value"><?= h($travelRequest->request_number) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">👤 Traveler:</span>
                    <span class="detail-value"><?= h($travelerName) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">📍 Destination:</span>
                    <span class="detail-value"><?= h($travelRequest->destination) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">✈️ Travel Type:</span>
                    <span class="detail-value"><?= h(ucfirst($travelRequest->travel_type)) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">🛫 Departure:</span>
                    <span class="detail-value"><?= $travelRequest->departure_date->format('F d, Y') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">🛬 Return:</span>
                    <span class="detail-value"><?= $travelRequest->return_date->format('F d, Y') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">📅 Duration:</span>
                    <span class="detail-value"><?= h($travelRequest->duration_days) ?> days</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">🏨 Accommodation:</span>
                    <span class="detail-value"><?= $travelRequest->accommodation_required ? 'Required' : 'Not Required' ?></span>
                </div>
            </div>
            
            <div class="purpose-section">
                <h3>📝 Purpose of Travel:</h3>
                <div class="purpose-text">
                    <?= nl2br(h($travelRequest->purpose_of_travel)) ?>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="<?= $approveUrl ?>" class="btn btn-approve">✅ Approve Request</a>
                <a href="<?= $rejectUrl ?>" class="btn btn-reject">❌ Reject Request</a>
                <a href="<?= $viewUrl ?>" class="btn btn-view">👁️ View Full Details</a>
            </div>
            
            <p style="color: #6c757d; font-size: 0.9rem; margin-top: 2rem;">
                <strong>Note:</strong> This request is awaiting your approval. Please respond as soon as possible 
                to ensure the traveler can make necessary arrangements.
            </p>
        </div>
        
        <div class="email-footer">
            <p><strong>Travel Request Management System</strong></p>
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>&copy; <?= date('Y') ?> All rights reserved.</p>
        </div>
    </div>
</body>
</html>
