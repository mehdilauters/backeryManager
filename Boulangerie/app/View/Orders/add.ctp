<div class="orders form">
<?php echo $this->Form->create('Order'); ?>
	<fieldset>
		<legend><?php echo __('Add Order'); ?></legend>
	<?php
		echo $this->Form->input('shop_id');
		echo $this->Form->input('user_id');
		//echo $this->Form->input('status',array('options'=>array('reserved'=>'réservée','available'=>'disponible','waiting'=>'en attente', 'paid'=>'payée')));
		echo $this->Form->input('delivery_date', array('type'=>'text', 'class'=>'datetimepicker' ));
		echo $this->Form->input('comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Orders'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Ordered Items'), array('controller' => 'ordered_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ordered Item'), array('controller' => 'ordered_items', 'action' => 'add')); ?> </li>
	</ul>
</div>
