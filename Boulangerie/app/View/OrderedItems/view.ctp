<div class="orderedItems view">
<h2><?php  echo __('Ordered Item'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orderedItem['Order']['id'], array('controller' => 'orders', 'action' => 'view', $orderedItem['Order']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orderedItem['Product']['name'], array('controller' => 'products', 'action' => 'view', $orderedItem['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tva'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['tva']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Unity'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['unity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['quantity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($orderedItem['OrderedItem']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Ordered Item'), array('action' => 'edit', $orderedItem['OrderedItem']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Ordered Item'), array('action' => 'delete', $orderedItem['OrderedItem']['id']), null, __('Are you sure you want to delete # %s?', $orderedItem['OrderedItem']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Ordered Items'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ordered Item'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
