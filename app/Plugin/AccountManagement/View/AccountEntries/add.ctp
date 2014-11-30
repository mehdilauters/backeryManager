<div class="accountEntries form">
<?php echo $this->Form->create('AccountEntry'); ?>
	<fieldset>
		<legend><?php echo __('Add Account Entry'); ?></legend>
	<?php
		echo $this->Form->input('date', array('type'=> 'text', 'class' => 'datepicker', 'value' => $date));
		echo $this->Form->input('name');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Accounts'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>
