<div class="order email" />
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
	  <?php if($order['Order']['discount'] != 0 ): ?>
		  <p>Remise de <?php echo $order['Order']['discount'] ?>%</p>
	  <?php endif; ?>

  <h3><?php echo __('Totaux'); ?></h3>
  <table cellpadding = "0" cellspacing = "0" class="table" >
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
</center>
</div>