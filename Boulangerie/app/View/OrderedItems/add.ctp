<div class="orderedItems form">

<?php echo $this->element('Orders/Preview', array('order'=>$order)); ?>

<?php echo $this->Form->create('OrderedItem'); ?>
	<fieldset>
		<legend><?php echo __('Add Ordered Item'); ?></legend>
	<?php
		echo $this->Form->input('product_id');
		echo $this->Form->input('quantity');
		echo $this->Form->input('comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Lister Commandes'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
	</ul>
</div>
