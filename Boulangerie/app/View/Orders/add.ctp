<div class="orders form">
<?php echo $this->Form->create('Order'); ?>
	<fieldset>
		<legend><?php echo __('Add Order'); ?></legend>
	<?php
		echo $this->Form->input('shop_id', array('label' => 'Magasin'));
		echo $this->Form->input('user_id', array('label' => 'Compte client'));
		//echo $this->Form->input('status',array('options'=>array('reserved'=>'réservée','available'=>'disponible','waiting'=>'en attente', 'paid'=>'payée')));
		echo $this->Form->input('delivery_date', array('type'=>'text', 'class'=>'datetimepicker', 'label' => 'Date de livraison' ));
		//echo $this->Form->input('discount');
		echo $this->Form->input('comment', array('label' => 'Commentaires'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Lister commandes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Nouveau Compte client'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<!-- <li><?php echo $this->Html->link(__('List Ordered Items'), array('controller' => 'ordered_items', 'action' => 'index')); ?> </li> -->
	</ul>
</div>
