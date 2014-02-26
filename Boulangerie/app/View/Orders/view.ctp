<div class="orders view">
<h2><?php  echo __('Commande'); ?></h2>
	<?php echo $this->element('Orders/Preview', array('order'=>$order)); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Modifier commande'), array('action' => 'edit', $order['Order']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Supprimer commande'), array('action' => 'delete', $order['Order']['id']), null, __('Are you sure you want to delete # %s?', $order['Order']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lister les commandes'), array('action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Elements commandés'); ?></h3>
	<?php if (!empty($order['OrderedItem'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="listTable" >
	<tr>
		<th><?php echo __('Produit'); ?></th>
		<th><?php echo __('Le'); ?></th>
		<th><?php echo __('Tva'); ?></th>
		<th><?php echo __('Prix'); ?></th>
		<th><?php echo __('Unity'); ?></th>
		<th><?php echo __('Quantity'); ?></th>
		<th><?php echo __('Prix HT'); ?></th>
		<th><?php echo __('Prix total HT'); ?></th>
		<th><?php echo __('Commentaire'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($order['OrderedItem'] as $orderedItem): ?>
		<tr>
			<td><a href="<?php echo $this->webroot.'produits/details/'.$orderedItem['Product']['id'] ?>" ><?php echo $orderedItem['Product']['name']; ?></a></td>
			<td><?php $date = new DateTime ($orderedItem['created']);
			echo $date->format('d/m/Y'); ?></td>
			<td><?php echo $orderedItem['tva']; ?>%</td>
			<td><?php echo $orderedItem['price']; ?></td>
			<td><?php echo $orderedItem['unity']; ?></td>
			<td><?php echo $orderedItem['quantity']; ?></td>
			<td><?php echo round($orderedItem['without_taxes'],2); ?></td>
			<td><?php echo round(
				$orderedItem['without_taxes'] * $orderedItem['quantity']
				,2); ?></td>
			<td><?php echo $orderedItem['comment']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('Details'), array('controller' => 'ordered_items', 'action' => 'view', $orderedItem['id'])); ?>
				<?php echo $this->Html->link(__('Edition'), array('controller' => 'ordered_items', 'action' => 'edit', $orderedItem['id'])); ?>
				<?php echo $this->Form->postLink(__('supprimer'), array('controller' => 'ordered_items', 'action' => 'delete', $orderedItem['id']), null, __('Are you sure you want to delete # %s?', $orderedItem['id'])); ?>
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
