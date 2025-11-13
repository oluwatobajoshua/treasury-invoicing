<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Dashboard');
?>
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 0.9rem 1rem;
        border-radius: 6px;
        margin-bottom: 0.8rem;
        box-shadow: 0 3px 12px rgba(12, 83, 67, 0.15);
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 60%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .hero-section h1 {
        margin: 0 0 0.3rem 0;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .hero-section p {
        font-size: 0.75rem;
        opacity: 0.95;
        margin: 0 0 0.6rem 0;
    }
    .quick-actions {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .quick-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        font-size: 0.75rem;
    }
    .quick-action-btn:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.6rem;
        margin-bottom: 0.8rem;
    }
    .stat-card {
        background: white;
        border-radius: 6px;
        padding: 0.7rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 35px;
        height: 35px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.4rem;
    }
    .stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.1rem;
        line-height: 1;
    }
    .stat-label {
        color: var(--muted);
        font-size: 0.7rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .quick-actions {
            flex-direction: column;
        }
        .quick-action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="hero-section">
    <div class="hero-content">
        <h1>👋 Welcome, <?= h($authUser['name'] ?? 'User') ?>!</h1>
        <p>Manage your business travel requests efficiently and professionally</p>
        <div class="quick-actions">
            <?= $this->Html->link('✈️ New Travel Request', ['controller' => 'TravelRequests', 'action' => 'add'], ['class' => 'quick-action-btn']) ?>
            <?= $this->Html->link('📋 My Requests', ['controller' => 'TravelRequests', 'action' => 'index'], ['class' => 'quick-action-btn']) ?>
            <?= $this->Html->link('👤 My Profile', ['controller' => 'Users', 'action' => 'profile'], ['class' => 'quick-action-btn']) ?>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
            ✅
        </div>
        <div class="stat-value"><?= $stats['approved'] ?? 0 ?></div>
        <div class="stat-label">Approved Requests</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #FFC107 0%, #FFA000 100%); color: white;">
            ⏳
        </div>
        <div class="stat-value"><?= $stats['pending'] ?? 0 ?></div>
        <div class="stat-label">Pending Review</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white;">
            🌍
        </div>
        <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
        <div class="stat-label">Total Requests</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%); color: white;">
            📝
        </div>
        <div class="stat-value"><?= $stats['draft'] ?? 0 ?></div>
        <div class="stat-label">Draft Requests</div>
    </div>
</div>

<div class="content-card">
    <div style="text-align: center; padding: 3rem 1rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🚀</div>
        <h2 style="color: var(--primary); margin-bottom: 1rem;">Welcome to Travel Request System</h2>
        <p style="color: var(--muted); max-width: 600px; margin: 0 auto 2rem; line-height: 1.6;">
            Start by creating a new travel request or view your existing requests. 
            Make sure to discuss your travel plans with your line manager via email before submitting.
        </p>
        <?= $this->Html->link('✈️ Create New Travel Request', 
            ['controller' => 'TravelRequests', 'action' => 'add'], 
            ['class' => 'btn btn-primary', 'style' => 'font-size: 1.1rem; padding: 1rem 2rem;']) ?>
    </div>
</div>
