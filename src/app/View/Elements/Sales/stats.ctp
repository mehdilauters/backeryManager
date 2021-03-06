<?php
$defaultConfig = array('interactive'=>true);
	if(isset($config))
	{
	
		$config = array_merge($defaultConfig, $config);
	}
	else
	{
		$config = $defaultConfig;
	}

$fields = array('date'=>true, 'day'=>true, 'week'=> true, 'product' => true, 'productType'=>true, 'shop'=>true, 'comment'=>false );
  $group = array('time' => '', 'product'=>'', 'shop'=>'');
  if(isset($this->request->data['group']))
  {
    $group = $this->request->data['group'];
  }

  if($group['product'] )
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
    }
    if( !$group['shop'] )
    {
      $fields['shop'] = false;
    }
  }
  
  if($group['shop'] )
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
    }
    if( !$group['product'] )
    {
      $fields['product'] = false;
      $fields['productType'] = false;
    }
  }
  
  if($group['time'] != '')
  {
    if( !$group['product'] )
    {
      $fields['product'] = false;
      $fields['productType'] = false;
    }
    if( !$group['shop']  )
    {
      $fields['shop'] = false;
    }
  }
  
  if($group['time'] == 'day')
  {
	$fields['comment'] = true;
}
//   debug($group);
//   debug($fields);
  
  	$titleDate = array(
		''=> 'Jour',
		'weekday'=> 'Jour de la semaine',
		'day'=> 'Jour',
		'week'=> 'Semaine',
		'month'=> 'Mois',
		'year'=> 'Année',
		'weekday'=> 'Jour de la semaine',
	);
  
 ?>


<div id="histogramChartContainer">
	<h3>Historique production par <?php echo $titleDate[$group['time']] ?></h3>
	<div id="histogramChart" class="chartDiv" >Chargement en cours... <img src="<?php echo $this->webroot ?>img/icons/load.gif" /></div>
	<div class="control" ></div>
</div>
<div class="alert alert-info">Filtrez les colonnes qui vous interesse pour en afficher le graphe correspondant</div>
<table id="statValues" class="table-striped <?php if(!$config['interactive']) echo 'hideJs' ?>" >
<tr class="plot" >
  <?php if($fields['date']) { ?><th style="display:none" >Date</th>
  <th>Date</th>
  <?php }	?>
  <?php if($fields['product']) { ?><th>Produit</th><?php } ?>
  <?php if($fields['productType']) { ?><th>Type de produit</th><?php } ?>
  <?php if($fields['shop']) { ?><th>Magasin</th><?php } ?>
  <th class="label_curve_produced" >Production</th>
  <th class="approxColumn label_curve_producedApproximation" >Production Approximation</th>
  <th class="label_curve_lost" >Perte</th>
  <th class="approxColumn label_curve_lostApproximation" >Perte Approximation</th>
  <th class="label_curve_sold" >Vente</th>
  <th class="approxColumn label_curve_soldApproximation" >Vente Approximation</th>
  <th class="label_curve_totalPrice" >Prix (€)</th>
  <th class="approxColumn label_curve_totalPriceApproximation" >Prix € Approximation</th>
  <th class="label_curve_totalLost" >Perte (€)</th>
  <th class="approxColumn label_curve_totalLostApproximation" >Perte € Approximation</th>
  <?php if($fields['comment']) { ?><th>Commentaires</th><?php } ?>
</tr>
<?php
  $i = 0;

   foreach($sales['sales'] as $sale)
   {
      $class = 'lostProducts';
      if($sale[0]['lost'] <= 0 )
      {
        $class = 'notLostProducts';    
      }
      
	  $date = new DateTime($sale['Sale']['date']);
   ?>
      <tr class="plot" >
      <?php if($fields['date']) { ?><td class="date" style="display:none" >
		<?php 
						switch($group['time'])
						{	
							case 'weekday':
								$dateDisplay = $date->format('d/m/Y'); 
							break;
							case 'day':
								$dateDisplay = $date->format('d/m/Y');
							break;
							case 'week':
								$weekNumber = $date->format('W');
								$dateDisplay = date( 'd/m/Y', strtotime('last monday', strtotime('tomorrow', $date->getTimestamp())));
							break;
							case 'month':
								$dateDisplay = $date->format('01/m/Y');
							break;
							case 'year':
								$dateDisplay = $date->format('01/01/Y');
							break;
							default:
								$dateDisplay = $date->format('d/m/Y'); 
							break;
						}
						echo  $dateDisplay;
			?>
	  </td>
	  <td>
		<?php
			switch($group['time'])
			{	
				case 'weekday':
					$dateDisplay = $this->Dates->getJourFr($date->format('w'));
				break;
				case 'day':
					$dateDisplay = $this->Dates->getJourFr($date->format('w')).' '.$date->format('d/m/Y').' w:'.$date->format('W');
				break;
				case 'week':
					$dateDisplay = $date->format('W');
				break;
				case 'month':
					$dateDisplay = $date->format('m/Y');
				break;
				case 'year':
					$dateDisplay = $date->format('Y');
				break;
				default:
					$dateDisplay = $date->format('d/m/Y'); 
				break;
			}
			echo  $dateDisplay;
		?>
	  </td>
	  <?php } ?>
      <?php if($fields['product']) { ?>
	  <td class="productName">
	      <a href="<?php echo $this->webroot ?>products/view/<?php echo $sale['Sale']['product_id']?>" >
		<?php echo $sales['products'][$sale['Sale']['product_id']]['Product']['name'] ?>
	      </a>
	  </td>
      <?php } ?>
      <?php if($fields['productType']) { ?><td class="productTypeName"><a href="<?php echo $this->webroot ?>productTypes/view/<?php echo $sale['Sale']['product_id'] ?>" ><?php echo $sales['products'][$sale['Sale']['product_id']]['ProductType']['name'] ?></a></td><?php } ?>
      <?php if($fields['shop']) { ?><td class="shopName"><a href="<?php echo $this->webroot ?>shops/view/<?php echo $sale['Sale']['shop_id'] ?>" ><?php echo $sales['shops'][$sale['Sale']['shop_id']] ?></a></td><?php } ?>

      <td class="produced curve_produced" ><?php echo $sale[0]['produced'] ?></td>
	  <td class="approxColumn producedApprox noDisplay curve_producedApproximation" ><?php echo round($sale[0]['producedApproximation'],2) ?></td>
      <td class="<?php echo $class ?> lost curve_lost" ><?php echo $sale[0]['lost'] ?></td>
	  <td class="approxColumn lostApprox noDisplay curve_lostApproximation" ><?php echo round($sale[0]['lostApproximation'],2) ?></td>
      <td class="sold curve_sold"><?php echo $sale[0]['sold'] ?></td>
	  <td class="approxColumn soldApprox noDisplay curve_soldApproximation" ><?php echo round($sale[0]['soldApproximation'],2) ?></td>
      <td class="totalPrice curve_totalPrice"><?php echo round($sale[0]['totalPrice'],2) ?></td>
	  <td class="approxColumn totalPriceApprox noDisplay curve_totalPriceApproximation" ><?php echo round($sale[0]['totalPriceApproximation'],2) ?></td>
      <td class="totalLost curve_totalLost"><?php echo round($sale[0]['totalLost'],2) ?></td>
	  <td class="approxColumn totalLostApprox noDisplay curve_totalLostApproximation" ><?php echo round($sale[0]['totalLostApproximation'],2) ?></td>
      <?php if($fields['comment']) { ?><td class="comment"><?php echo $sale['Sale']['comment'] ?></td><?php } ?>
   </tr>
   
   <?php
         $i++;
      }

?>
</table>
<script type="text/javascript">

 //  var histogramPlot;

   
   function shuffle(o){ //v1.0
    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
  };
 

   
  $(document).ready(function(){

<?php if($config['interactive']): ?>

      var tfConfig = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              extensions: {
					name:['ColsVisibility'],
					src:['<?php echo $this->webroot ?>/js/TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.js'],
					description:['Columns visibility manager'],
					initialize:[function(o){o.SetColsVisibility(); o.HideCol(0); o.HideCol(6); o.HideCol(8); o.HideCol(10); o.HideCol(12); o.HideCol(14);}]
					},
              on_after_refresh_counter: function(o,i){ 
				  try
				  {
					histogram('statValues','histogramChart'); 
				  }
				catch (e) {
				console.log(e);
				 }
			  }
              };
              tf = new TF('statValues', tfConfig); tf.AddGrid();

<?php else: ?>
  histogram('statValues','histogramChart'); 

<?php endif; ?>



  });
</script> 
