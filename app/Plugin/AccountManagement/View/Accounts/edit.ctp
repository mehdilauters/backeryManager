<div class="accounts form">
<?php echo $this->Form->create('Account'); ?>
	<fieldset>
		<legend><?php echo __('Editer compte'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
                if($tokens['isRoot'])
                {
                  echo $this->Form->input('company_id');
                }
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $this->Form->value('Account.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Account.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Liste des comtes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Nouvelle entrée'), array('controller' => 'account_entries', 'action' => 'add')); ?> </li>
	</ul>
</div>
