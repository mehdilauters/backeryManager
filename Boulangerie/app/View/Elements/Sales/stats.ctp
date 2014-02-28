<?php
$fields = array('date'=>true, 'day'=>true, 'week'=> true, 'product' => true, 'productType'=>true, 'shop'=>true);
  $group = array('time' => '', 'product'=>'', 'shop'=>'');
  if(isset($this->request->data['group']))
  {
    $group['time'] = $this->request->data['group'];
  }

  if($group['product'] != '')
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
      $fields['day'] = false;
      $fields['week'] = false;
    }
    if( $group['shop'] == '' )
    {
      $fields['shop'] = false;
    }
  }
  
  if($group['shop'] != '')
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
      $fields['day'] = false;
      $fields['week'] = false;
    }
    if( $group['product'] == '' )
    {
      $fields['product'] = false;
      $fields['productType'] = false;
    }
  }
  
  if($group['time'] != '')
  {
    if( $group['product'] == '' )
    {
      $fields['product'] = false;
      $fields['productType'] = false;
    }
    if( $group['shop'] == '' )
    {
      $fields['shop'] = false;
    }
  }
 ?>


?>
<div>
	<div id="histogramChart" style="width=500px;height=600px;" class="chartDiv" ></div>
	<div class="control" ></div>
</div>
<table id="statValues" class="<?php if(!$config['interactive']) echo 'hideJs' ?>" >
<tr class="plot" >
  <?php if($fields['date']) { ?><th style="display:none" >Date</th>
  <th>Date</th>
  <?php }	?>
  <?php if($fields['product']) { ?><th>Produit</th><?php } ?>
  <?php if($fields['productType']) { ?><th>Type de produit</th><?php } ?>
  <?php if($fields['shop']) { ?><th>Magasin</th><?php } ?>
  <th class="label_curve_produced" >Production</th>
  <th class="label_curve_lost" >Perte</th>
  <th class="label_curve_sold" >Vente</th>
  <th class="label_curve_totalPrice" >Prix (€)</th>
  <th class="label_curve_totalLost" >Perte (€)</th>
  <th>Commentaires</th>
</tr>
<?php
  $i = 0;
   foreach($sales as $sale)
   {
      
      $class = 'lostProducts';
      if($sale[0]['lost'] <= 0 )
      {
        $class = 'notLostProducts';    
      }
      
	  $date = new DateTime($sale['Sale']['date']);
   ?>
      <tr class="table-striped plot" >
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
      <?php if($fields['product']) { ?><td class="productName"><?php echo $products[$sale['Sale']['product_id']]['Product']['name'] ?></td><?php } ?>
      <?php if($fields['productType']) { ?><td class="productTypeName"><?php echo $products[$sale['Sale']['product_id']]['ProductType']['name'] ?></td><?php } ?>
      <?php if($fields['shop']) { ?><td class="shopName"><?php echo $shops[$sale['Sale']['shop_id']] ?></td><?php } ?>
      <td class="produced curve_produced" ><?php echo $sale[0]['produced'] ?></td>
      <td class="<?php echo $class ?> lost curve_lost" ><?php echo $sale[0]['lost'] ?></td>
      <td class="sold  curve_sold"><?php echo $sale[0]['sold'] ?></td>
      <td class="totalPrice  curve_totalPrice"><?php echo round($sale[0]['totalPrice'],2) ?></td>
      <td class="totalLost curve_totalLost"><?php echo round($sale[0]['totalLost'],2) ?></td>
      <td class="comment"><?php echo $sale['Sale']['comment'] ?></td>
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
					initialize:[function(o){o.SetColsVisibility(); o.HideCol(0);}]
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
