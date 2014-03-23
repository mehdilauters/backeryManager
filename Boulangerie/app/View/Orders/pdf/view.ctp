<div class="orders view">
<table class="header" >
  <tr>
    <td>
      <?php echo $this->element('Companies/Preview', array('company'=>$company)); ?>
    </td>
<td></td>
    <td>
      <?php echo $this->element('Users/Preview', array('user'=>$order)); ?>
	  Commande #<?php echo $order['Order']['id'] ?>
    </td>
</table>
<center><h2><?php echo __('Commande'); ?> du <?php 
$date = new DateTime($order['Order']['delivery_date']);
echo $date->format('d/m/Y'); ?></h2></center>

<div class="related">
	<h3><?php echo __('Items commandés'); ?></h3>
	<?php if (!empty($order['OrderedItem'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="table" >
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
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
<h3><?php echo __('Totaux'); ?></h3>
<table cellpadding = "0" cellspacing = "0" class="table" >

  <tr>
    <th>HT</th>
    <th>TVA %</th>
    <th>TTC</th>
    <th>TVA €</th>
  </tr>
<?php foreach ($total['tva'] as $tva => $data):  ?>
<tr>
  <td><?php echo round($data['HT'],2) ?></td>
  <td><?php echo $tva ?>%</td>
  <td><?php echo $data['TTC'] ?></td>
  <td><?php echo round($data['tva_total'],2) ?></td>
</tr>
<?php endforeach; ?>
</table>



	<div>
	<?php 
		$finalSum = $total['total']['HT'];
		if($order['Order']['discount'] != 0 ): 
			$percent = $order['Order']['discount'] * $order['Order']['discount'] / 100;
			$finalSum -= $percent;
		?>
		<p>Remise de <?php echo $order['Order']['discount'] ?>%</p>
	<?php endif; ?>
		<p>Arrêté à la somme de <b><?php echo round($finalSum,2); ?>€</b></p>
	</div>
	<?php if($order['User']['rib_on_orders']): ?>
	<h3>Rib</h3>
	<img class="rib" src="<?php echo APP.'webroot/img/photos/normal/'.$company['Media']['path']; ?>" />
	<?php endif; ?>

</div>