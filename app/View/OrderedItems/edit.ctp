<div class="orderedItems form">
<?php echo $this->element('Orders/Preview', array('order'=>$this->request->data['Order'])); ?>
<?php echo $this->Form->create('OrderedItem', array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Edit Ordered Item'); ?></legend>
	<?php
		echo $this->Form->input('id');
		$date = new DateTime($this->request->data['OrderedItem']['created']);
		$date = $date->format('d/m/Y');
		echo $this->Form->input('created', array('type'=>'text', 'value'=>$date,
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Date'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control datepicker'
					  ));
		echo $this->Form->input('product_id', array(
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Produit'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('tva', array(
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Tva'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control spinner'
					  ));
		echo $this->Form->input('price', array(
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Prix'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control spinner'
					  ));
		echo $this->Form->input('unity', array(
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Prix calculé à l\'unité'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('quantity', array(
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Quantité'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control spinner'
					  ));
		echo $this->Form->input('comment', array(
					'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Commentaire'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control texteditor'
					  ));
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
