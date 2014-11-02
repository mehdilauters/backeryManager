<div class="accounts form">
<?php echo $this->Form->create('Account'); ?>
	<fieldset>
		<legend><?php echo __('Add Account'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Accounts'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Account Entries'), array('controller' => 'account_entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Entry'), array('controller' => 'account_entries', 'action' => 'add')); ?> </li>
	</ul>
</div>
