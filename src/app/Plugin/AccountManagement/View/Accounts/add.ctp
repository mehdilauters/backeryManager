<div class="accounts form">
<?php echo $this->Form->create('Account'); ?>
	<fieldset>
		<legend><?php echo __('Ajouter un compte'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('Liste des comptes'), array('action' => 'index')); ?></li>
	</ul>
</div>
