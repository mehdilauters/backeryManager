<div class="orderedItems form">
<?php echo $this->Form->create('OrderedItem'); ?>
	<fieldset>
		<legend><?php echo __('Edit Ordered Item'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('order_id');
		echo $this->Form->input('product_id');
		echo $this->Form->input('tva');
		echo $this->Form->input('price');
		echo $this->Form->input('unity');
		echo $this->Form->input('quantity', array('class'=>'spinner'));
		echo $this->Form->input('comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrderedItem.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('OrderedItem.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Ordered Items'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>