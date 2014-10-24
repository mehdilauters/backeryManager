<div class="orderedItems form">

<?php echo $this->element('Orders/Preview', array('order'=>$order)); ?>

<?php echo $this->Form->create('OrderedItem', array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Add Ordered Item'); ?></legend>
	<?php
		echo $this->Form->input('product_id',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Produit'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		$date = new DateTime();
		$date = $date->format('d/m/Y');
		echo $this->Form->input('created', array('type'=>'text', 'value'=>$date,
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Date'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control datepicker'
					  ));
		echo $this->Form->input('quantity', array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Quantité'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control spinner'
					  ));
		echo $this->Form->input('comment',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Commentaire'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control textEditor'
					  ));
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
