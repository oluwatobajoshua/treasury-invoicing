<?php
/**
 * Progress Tracker Element
 * 
 * @var int $currentStep Current step in the workflow (1-5)
 * @var string|null $status Optional status for additional context
 */

$currentStep = $currentStep ?? 1;

$steps = [
    ['icon' => '📧', 'label' => 'Email<br>Conversation', 'step' => 1],
    ['icon' => '✈️', 'label' => 'Travel<br>Details', 'step' => 2],
    ['icon' => '👔', 'label' => 'LM<br>Approval', 'step' => 3],
    ['icon' => '💰', 'label' => 'Financials<br>Calculated', 'step' => 4],
    ['icon' => '✅', 'label' => 'Complete', 'step' => 5],
];
?>

<style>
    .progress-tracker {
        background: linear-gradient(135deg, #0c5343 0%, #0a4636 100%);
        padding: 0.85rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 16px rgba(12, 83, 67, 0.15);
    }
    .progress-tracker h3 {
        margin: 0 0 0.65rem 0;
        font-size: 0.85rem;
        color: white;
        font-weight: 600;
    }
    .progress-steps-tracker {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        padding: 0.5rem 0;
    }
    .progress-step-tracker {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 1;
    }
    .progress-step-tracker::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: rgba(255, 255, 255, 0.2);
        z-index: 0;
    }
    .progress-step-tracker:last-child::after {
        display: none;
    }
    .progress-step-tracker.completed::after {
        background: #4CAF50;
    }
    .progress-circle-tracker {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        position: relative;
        z-index: 1;
        margin-bottom: 0.35rem;
        transition: all 0.3s ease;
    }
    .progress-step-tracker.completed .progress-circle-tracker {
        background: #4CAF50;
        border-color: #4CAF50;
        color: white;
        box-shadow: 0 0 10px rgba(76, 175, 80, 0.4);
    }
    .progress-step-tracker.active .progress-circle-tracker {
        background: #F64500;
        border-color: #F64500;
        color: white;
        box-shadow: 0 0 12px rgba(246, 69, 0, 0.5);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 12px rgba(246, 69, 0, 0.5);
        }
        50% {
            box-shadow: 0 0 18px rgba(246, 69, 0, 0.8);
        }
    }
    .progress-label-tracker {
        font-size: 0.7rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.2;
    }
    .progress-step-tracker.completed .progress-label-tracker,
    .progress-step-tracker.active .progress-label-tracker {
        color: white;
        font-weight: 600;
    }
    @media (max-width: 768px) {
        .progress-tracker {
            padding: 0.75rem;
        }
        .progress-steps-tracker {
            overflow-x: auto;
            justify-content: flex-start;
            gap: 1.5rem;
            padding-bottom: 0.35rem;
        }
        .progress-step-tracker {
            min-width: 60px;
        }
        .progress-circle-tracker {
            width: 28px;
            height: 28px;
            font-size: 0.9rem;
        }
        .progress-label-tracker {
            font-size: 0.65rem;
        }
    }
    @media (max-width: 640px) {
        .progress-tracker h3 {
            font-size: 0.8rem;
        }
        .progress-circle-tracker {
            width: 26px;
            height: 26px;
            font-size: 0.85rem;
        }
        .progress-label-tracker {
            font-size: 0.6rem;
        }
    }
</style>

<div class="progress-tracker">
    <h3>📊 Request Progress</h3>
    <div class="progress-steps-tracker">
        <?php foreach ($steps as $step): 
            $isCompleted = $step['step'] < $currentStep;
            $isActive = $step['step'] === $currentStep;
            $classes = [];
            if ($isCompleted) $classes[] = 'completed';
            if ($isActive) $classes[] = 'active';
            $classString = implode(' ', $classes);
        ?>
            <div class="progress-step-tracker <?= $classString ?>">
                <div class="progress-circle-tracker"><?= $step['icon'] ?></div>
                <div class="progress-label-tracker"><?= $step['label'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
