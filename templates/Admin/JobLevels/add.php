<?php
$this->assign('title', 'Add Job Level');
?>
<style>.form-card{background:var(--card);border:var(--border);border-radius:8px;padding:1rem;max-width:640px;margin:0 auto;box-shadow:0 1px 3px rgba(0,0,0,.06);} .form-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:.75rem;} .actions-bar{display:flex;justify-content:flex-end;gap:.5rem;margin-top:.9rem;} </style>
<div class="form-card fade-in">
	<h2 style="margin:0 0 .9rem;font-size:1rem;display:flex;align-items:center;gap:.4rem;">🏷️ Add Job Level</h2>
	<?= $this->Form->create($level) ?>
	<div class="form-row">
		<?= $this->Form->control('level_code', ['label' => 'Level Code']) ?>
		<?= $this->Form->control('level_name', ['label' => 'Level Name']) ?>
		<?= $this->Form->control('description', ['label' => 'Description']) ?>
	</div>
	<div class="actions-bar">
		<?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline btn-sm']) ?>
		<?= $this->Form->button('➕ Create', ['class' => 'btn btn-primary']) ?>
	</div>
	<?= $this->Form->end() ?>
</div>
