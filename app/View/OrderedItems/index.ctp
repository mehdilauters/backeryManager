<div class="orderedItems index">
	<h2><?php echo __('Ordered Items'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('tva'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('unity'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity'); ?></th>
			<th><?php echo $this->Paginator->sort('comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orderedItems as $orderedItem): ?>
	<tr>
		<td><?php echo h($orderedItem['OrderedItem']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($orderedItem['Order']['id'], array('controller' => 'orders', 'action' => 'view', $orderedItem['Order']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($orderedItem['Product']['name'], array('controller' => 'products', 'action' => 'view', $orderedItem['Product']['id'])); ?>
		</td>
		<td><?php echo h($orderedItem['OrderedItem']['created']); ?>&nbsp;</td>
		<td><?php echo h($orderedItem['OrderedItem']['tva']); ?>&nbsp;</td>
		<td><?php echo h($orderedItem['OrderedItem']['price']); ?>&nbsp;</td>
		<td><?php echo h($orderedItem['OrderedItem']['unity']); ?>&nbsp;</td>
		<td><?php echo h($orderedItem['OrderedItem']['quantity']); ?>&nbsp;</td>
		<td><?php echo h($orderedItem['OrderedItem']['comment']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orderedItem['OrderedItem']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orderedItem['OrderedItem']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orderedItem['OrderedItem']['id']), null, __('Are you sure you want to delete # %s?', $orderedItem['OrderedItem']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Ordered Item'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
