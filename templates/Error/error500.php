<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Database\StatementInterface $error
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')) :
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error500.php');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
    <strong>SQL Query Params: </strong>
    <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?php if ($error instanceof Error) : ?>
    <?php $file = $error->getFile() ?>
    <?php $line = $error->getLine() ?>
    <strong>Error in: </strong>
    <?= $this->Html->link(sprintf('%s, line %s', Debugger::trimPath($file), $line), Debugger::editorUrl($file, $line)); ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');

    $this->end();
endif;
?>

<?php if (!Configure::read('debug')): ?>
<!-- User-friendly error page -->
<div style="max-width: 600px; margin: 50px auto; text-align: center; font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;">
    <div style="font-size: 80px; margin-bottom: 20px;">😕</div>
    <h2 style="color: #dc3545; margin-bottom: 15px; font-size: 28px;">Oops! Something went wrong</h2>
    <p style="color: #6c757d; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
        We're sorry, but we encountered an unexpected error. This could be due to:
    </p>
    <ul style="list-style: none; padding: 0; color: #6c757d; text-align: left; max-width: 400px; margin: 0 auto 30px;">
        <li style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">🌐 Network connection issues</li>
        <li style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">⚙️ Temporary service problems</li>
        <li style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">🔌 External service unavailability</li>
    </ul>
    <div style="margin-top: 30px;">
        <a href="javascript:history.back()" 
           style="display: inline-block; padding: 12px 30px; background: #0c5343; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin-right: 10px; transition: all 0.3s ease;"
           onmouseover="this.style.background='#0a4636'" 
           onmouseout="this.style.background='#0c5343'">
            ← Go Back
        </a>
        <a href="<?= $this->Url->build(['controller' => 'TravelRequests', 'action' => 'index']) ?>" 
           style="display: inline-block; padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;"
           onmouseover="this.style.background='#5a6268'" 
           onmouseout="this.style.background='#6c757d'">
            🏠 Go Home
        </a>
    </div>
    <p style="color: #adb5bd; font-size: 14px; margin-top: 30px;">
        If this problem persists, please contact the system administrator.
    </p>
</div>
<?php else: ?>
<!-- Debug mode error details -->
<h2><?= __d('cake', 'An Internal Error Has Occurred.') ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= h($message) ?>
</p>
<?php endif; ?>
