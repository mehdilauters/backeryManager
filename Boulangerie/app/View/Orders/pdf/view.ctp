<div class="orders view">
<table class="header" >
  <tr>
    <td>
      <?php echo $this->element('Companies/Preview', array('company'=>$company)); ?>
    </td>
<td></td>
    <td>
      <?php echo $this->element('Users/Preview', array('user'=>$order)); ?>
    </td>
</table>
<h2><?php echo __('Commande'); ?> #<?php echo $order['Order']['id'] ?></h2>
<?php echo $this->element('Orders/Preview', array('order'=>$order)); ?>

	<div class="related">
		<h3><?php echo __('Items commandés'); ?></h3>
		<?php if (!empty($order['OrderedItem'])): ?>
		<table cellpadding = "0" cellspacing = "0" class="listTable table" >
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
		</tr>
		<?php
			$i = 0;
			foreach ($order['OrderedItem'] as $orderedItem): ?>
			<tr>
				<td>
				<?php
					echo $this->MyHtml->link(
						$orderedItem['Product']['name'],
						array(
							'controller' => 'produits',
							'action' => 'details',
							'full_base' => true,
							$orderedItem['Product']['id']
						)
					);
				?></td>
				<td><?php $date = new DateTime ($orderedItem['created']);
				echo $date->format('d/m/Y'); ?></td>
				<td><?php echo $orderedItem['tva']; ?>%</td>
				<td><?php echo $orderedItem['price']; ?></td>
				<td><?php echo $orderedItem['unity']; ?></td>
				<td><?php echo $orderedItem['quantity']; ?></td>
				<td><?php echo round($orderedItem['without_taxes'],2); ?></td>
				<td><?php echo round($orderedItem['total_HT'],2); ?></td>
				<td><?php echo $orderedItem['comment']; ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div>
		<h3>Total</h3>
		<table cellpadding = "0" cellspacing = "0" class="table" >
			<tr>
				<th>Total HT</th>
				<th>% TVA</th>
				<th>Total TVA</th>
				<th>Total TTC</th>
			</tr>
			<tr>
				<td><?php echo round($total['HT'],2); ?></td>
				<td><?php echo round($total['tva_percent'],2); ?></td>
				<td><?php echo round($total['tva_total'],2); ?></td>
				<td><?php echo round($total['TTC'],2); ?></td>
			</tr>
		</table>
	</div>
	<div>
		<p>Arrêté à la somme de <b><?php echo round($total['HT'],2); ?>€</b></p>
	</div>
	<?php if($order['User']['rib_on_orders']): ?>
	<h3>Rib</h3>
	<img class="rib" src="<?php echo APP.'webroot/img/photos/normal/'.$company['Media']['path']; ?>" />
	<?php endif; ?>
	
</div>