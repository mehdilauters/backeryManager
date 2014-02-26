<div class="orders view">
<h2><?php  echo __('Commande'); ?></h2>
	<dl>
		<dt><?php echo __('Magasin'); ?></dt>
		<dd>
			<?php echo $this->Html->link($order['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $order['Shop']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php 
			$date = new DateTime ($order['Order']['created']);
			echo h($date->format('d/m/Y H:i')); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($order['Order']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date de production'); ?></dt>
		<dd>
			<?php echo h($order['Order']['delivery_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($order['User']['name'], array('controller' => 'users', 'action' => 'view', $order['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($order['Order']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Order'), array('action' => 'edit', $order['Order']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Supprimer commande'), array('action' => 'delete', $order['Order']['id']), null, __('Are you sure you want to delete # %s?', $order['Order']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lister les commandes'), array('action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Ordered Items'); ?></h3>
	<?php if (!empty($order['OrderedItem'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Produit'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Tva'); ?></th>
		<th><?php echo __('Price'); ?></th>
		<th><?php echo __('Unity'); ?></th>
		<th><?php echo __('Quantity'); ?></th>
		<th><?php echo __('Prix'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($order['OrderedItem'] as $orderedItem): ?>
		<tr>
			<td><a href="<?php echo $this->webroot.'produits/details/'.$orderedItem['Product']['id'] ?>" ><?php echo $orderedItem['Product']['name']; ?></a></td>
			<td><?php echo $orderedItem['created']; ?></td>
			<td><?php echo $orderedItem['tva']; ?></td>
			<td><?php echo $orderedItem['price']; ?></td>
			<td><?php echo $orderedItem['unity']; ?></td>
			<td><?php echo $orderedItem['quantity']; ?></td>
			<td><?php echo round($orderedItem['total'],2); ?></td>
			<td><?php echo $orderedItem['comment']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'ordered_items', 'action' => 'view', $orderedItem['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'ordered_items', 'action' => 'edit', $orderedItem['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'ordered_items', 'action' => 'delete', $orderedItem['id']), null, __('Are you sure you want to delete # %s?', $orderedItem['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('Ajouter un item a la commande'), array('controller' => 'ordered_items', 'action' => 'add' ,$order['Order']['id'])); ?> </li>
		</ul>
	</div>
</div>
