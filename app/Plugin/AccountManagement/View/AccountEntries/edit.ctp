<div class="accountEntries form">
<?php echo $this->Form->create('AccountEntry'); ?>
	<fieldset>
		<legend><?php echo __('Editer l\'entrée'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('account_id');
		echo $this->Form->input('date', array('type'=> 'text', 'class' => 'datepicker'));
		echo $this->Form->input('name');
		echo $this->Form->input('comment');
		echo $this->Form->input('value');
		echo $this->Form->input('checked');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $this->Form->value('AccountEntry.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('AccountEntry.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Liste des comptes'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nouveau compte'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>
