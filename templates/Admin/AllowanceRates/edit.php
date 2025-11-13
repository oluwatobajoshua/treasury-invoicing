<?php
$this->assign('title', 'Edit Allowance Rate');
?>
<style>.form-card{background:var(--card);border:var(--border);border-radius:8px;padding:1rem;max-width:760px;margin:0 auto;box-shadow:0 1px 3px rgba(0,0,0,.06);} .form-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.75rem;} .actions-bar{display:flex;justify-content:flex-end;gap:.5rem;margin-top:.9rem;} </style>
<div class="form-card fade-in">
	<h2 style="margin:0 0 .9rem;font-size:1rem;display:flex;align-items:center;gap:.4rem;">💸 Edit Allowance Rate <small class="muted" style="font-size:.65rem;font-weight:500;">ID #<?= (int)$rate->id ?></small></h2>
	<?= $this->Form->create($rate) ?>
	<div class="form-row">
		<?= $this->Form->control('job_level_id', ['type' => 'select', 'options' => $jobLevels, 'empty' => 'Select job level', 'label' => 'Job Level']) ?>
		<?= $this->Form->control('travel_type', ['type' => 'select', 'options' => ['local' => 'Local', 'international' => 'International'], 'empty' => 'Select type', 'label' => 'Travel Type']) ?>
		<?= $this->Form->control('currency', ['type' => 'select', 'options' => ['NGN' => 'NGN', 'USD' => 'USD'], 'empty' => 'Select currency', 'label' => 'Currency']) ?>
	</div>
	<div class="form-row">
		<?= $this->Form->control('accommodation_rate', ['label' => 'Accommodation (per night)']) ?>
		<?= $this->Form->control('per_diem_rate', ['label' => 'Per Diem (per day)']) ?>
		<?= $this->Form->control('transport_rate', ['label' => 'Transport (per day)']) ?>
	</div>
	<div class="form-row">
		<?= $this->Form->control('flight_class', ['label' => 'Flight Class']) ?>
		<?= $this->Form->control('hotel_standard', ['label' => 'Hotel Standard']) ?>
		<?= $this->Form->control('room_type', ['label' => 'Room Type']) ?>
	</div>
	<div class="actions-bar">
		<?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline btn-sm']) ?>
		<?= $this->Form->button('💾 Save', ['class' => 'btn btn-primary']) ?>
	</div>
	<?= $this->Form->end() ?>
</div>
