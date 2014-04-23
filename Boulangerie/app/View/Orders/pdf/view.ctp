<div class="orders view">
<div>
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
  <center><h2><?php echo __('Facture'); ?> de <?php 
  $date = new DateTime($order['Order']['delivery_date']);
  echo $this->Dates->getMoisFr($date->format('m')).' '.$date->format('Y') ?></h2></center>
<center>
	  Facture #<?php echo $order['Order']['id'] ?>
	  <?php if($order['Order']['discount'] != 0 ): ?>
		  <p>Remise de <?php echo $order['Order']['discount'] ?>%</p>
	  <?php endif; ?>

  <h3><?php echo __('Totaux'); ?></h3>
  <table cellpadding = "0" cellspacing = "0" class="table" id="tvaTotal" >
    <tr>
      <th>TVA %</th>
      <th>HT</th>
      <th>TVA €</th>
      <th>TTC</th>
    </tr>
  <?php foreach ($total['tva'] as $tva => $data):  ?>
  <tr>
    <td><?php echo $tva ?>%</td>
    <td><?php echo round($data['HT'],2) ?></td>
    <td><?php echo round($data['tva_total'],2) ?></td>
    <td><?php echo round($data['TTC'],2) ?></td>
  </tr>
  <?php endforeach; ?>
  </table>



	  <div>
		  <p>Arrêtée à la somme de <b><?php echo round($total['total']['TTC'],2); ?>€</b></p>
	  </div>
	  <?php if($order['User']['rib_on_orders']): ?>
	  <h3>Rib</h3>
	  <img class="rib" src="<?php echo APP.'webroot/img/photos/normal/'.$company['Media']['path']; ?>" />
	  <?php endif; ?>
</center>
	<p id="legalMentions">
		<?php echo $company['Company']['order_legals_mentions']; ?>
	</p>
</div>
<?php 
  $class = '';
  if(count($order['OrderedItem']) > Configure::read('Order.pageBreakItemsMax') )
  {
    $class = 'page-break-before:always';
  }
?>
<div class="related" style="<?php echo $class ?>" >
	<h3>Détail</h3>
	<?php if (!empty($order['OrderedItem'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="table" >
	<tr>
		<th><?php echo __('Produit'); ?></th>
		<th><?php echo __('Le'); ?></th>
		<th><?php echo __('Tva'); ?></th>
		<th><?php echo __('Prix TTC'); ?></th>
		<th><?php echo __('Prix HT'); ?></th>
		<?php if ($order['Order']['discount'] != 0 ) { ?><th><?php echo __('Après remise'); ?></th><?php } ?>
		<th><?php echo __('Quantité'); ?></th>
		<th><?php echo __('Prix total HT'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($order['OrderedItem'] as $orderedItem): 
		$class = 'even';
		if($i % 2 != 0)
		{
		  $class = 'odd';
		}
		?>
		<tr class="<?php echo $class; ?>" >
			<td><a href="<?php echo $this->webroot.'produits/details/'.$orderedItem['Product']['id'] ?>" ><?php echo $orderedItem['Product']['name']; ?></a></td>
			<td><?php $date = new DateTime ($orderedItem['created']);
			echo $date->format('d/m/Y'); ?></td>
			<td><?php echo $orderedItem['tva']; ?>%</td>
			<td><?php echo $orderedItem['price']; ?></td>
			<td><?php echo round($orderedItem['without_taxes'],3); ?></td>
			<?php if ($order['Order']['discount'] != 0 ) { ?> <td><?php echo round($orderedItem['discount_HT'],2); ?></td><?php  }  ?>
			<td><?php  echo $orderedItem['quantity']; ?></td>
			
			<td><?php echo round(
				$orderedItem['discount_HT'] * $orderedItem['quantity']
				,3); ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	</table>
<?php endif; ?>
</div>
</div>