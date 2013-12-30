<div class="breakages form">
<?php echo $this->Form->create('Breakage'); ?>
	<fieldset>
		<legend><?php echo __('Add Breakage'); ?></legend>
	<?php
		echo $this->Form->input('date');
		echo $this->Form->input('shop_id');
		echo $this->Form->input('product_types_id');
		echo $this->Form->input('breakage');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Breakages'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
	</ul>
</div>
