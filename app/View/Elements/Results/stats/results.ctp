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
	
// 	$results['group'] = array('time' => '', 'shop'=>'', 'productType'=>'');
	$fields = array('date'=>true, 'shop'=>true, 'comment'=> false);
	
  
  
    if($results['group']['shop'])
  {
    if( $results['group']['time'] == '' )
    {
      $fields['date'] = false;
    }
	 if( !$results['group']['productType'])
    {
      $fields['productType'] = false;
    }
  }
  
  if($results['group']['time'] != '')
  {
    if( !$results['group']['shop'] )
    {
      $fields['shop'] = false;
    }
  }
  
  if($results['group']['time'] == 'day')
  {
	$fields['comment'] = true;
  }
  
    
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
<div>
	<div>
		<h3>Historique comptable par <?php echo $titleDate[$results['group']['time']] ?></h3>
		<div id="resultsChart" class="chartDiv" >Chargement en cours... <img src="<?php echo $this->webroot ?>img/icons/load.gif" /></div>
		<div class="control"></div>
	</div>
	<div class="<?php if(!$config['interactive']) echo 'hideJs'; ?>" >
		<table id="resultsStatValuesLegend" class="hideJs">
			<tr>
				<td class="label_curve_totalApprox" >Total € (approximation)</td>
				<td class="label_curve_total" >Total € </td>
				<?php if($fields['shop']) :
					foreach($results['shops'] as $shopId => $shopName):
				?>
				
					<td class="label_curve_Shop<?php echo  $shopId; ?>"  ><?php echo  $shopName; ?> €</td>
					<td class="label_curve_ShopApprox<?php echo  $shopId; ?>" ><?php echo  $shopName; ?> (approximation) €</td>
				<?php endforeach;
				else: ?>
					<td class="label_curve_Shop<?php echo  0; ?>"  ><?php echo  'Tous'; ?> €</td>
					<td class="label_curve_ShopApprox<?php echo  0; ?>" ><?php echo  'Tous'; ?> (approximation) €</td>
				  <?php
					endif;
					?>
			</tr>
		</table>
		<div class="alert alert-info">Filtrez les colonnes qui vous interesse pour en afficher le graphe correspondant</div>
		<table id="resultsStatValues" class="tablePreview table-striped" >
		  <tr class="legend plot" >
			<?php if($fields['date']) { ?><th class="date" style="display:none" >Date</th>
				<th>date</th>
			<?php } ?>
			<th class="shop" >Magasin</th><th class="shop" >Approximation</th>
			<th class="rowTotal" >Total</th>
			<th class="cash" >Especes %</th>
			<th class="check" >Cheques %</th>
			<th class="card" >Carte Bleue %</th>
			<?php if($fields['comment']) { ?><th class="comment" >Commentaire</th><?php } ?>
		  </tr>
			<?php 
		foreach($results['results'] as $i=>$result):
			if(!isset($result['Result']['date'])) debug($result);
			$date = new DateTime($result['Result']['date']);
			$total = $result[0]['total'];
			?>
			<tr class="plot" >
			  <?php if($fields['date']) { ?>
				<td class="date" style="display:none" >
					<?php 
						switch($results['group']['time'])
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
								$nbDaysMax = 8;
							break;
							case 'month':
								$dateDisplay = $date->format('01/m/Y');
								$nbDaysMax = 32;
							break;
							case 'year':
								$dateDisplay = $date->format('01/01/Y');
								$nbDaysMax = 366;
							break;
							default:
								$dateDisplay = $date->format('d/m/Y'); 
							break;
						}
						echo  $dateDisplay;
					?>
				</td>
				<td class="humanDate" >
					<?php 
						switch($results['group']['time'])
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
				<td class="shop" ><?php if($fields['shop']) {echo  $result['Shop']['name'];} else { echo 'Tous' ;} ?></td>
				<td class="shop curve_totalApprox curve_ShopApprox<?php if($fields['shop']) { echo  $result['Shop']['id']; } else {echo 0; } ?>" ><?php echo round($result[0]['approximation'],2); ?></td>
			  <td class="rowTotal noDisplay curve_total curve_Shop<?php if($fields['shop']) { echo  $result['Shop']['id']; } else {echo 0;} ?>"><?php echo round($total,2) ?></td>
			  <td class="cash"><?php if($total != 0){ echo round($result[0]['cash'] / $total *100, 2); } ?></td>
			  <td class="check"><?php if($total != 0){ echo round($result[0]['check'] / $total *100, 2); } ?></td>
			  <td class="card"><?php if($total != 0){ echo round($result[0]['card'] / $total *100, 2); } ?></td>
			  
			  <?php if($fields['comment']) { ?><td class="comment" ><?php echo $result['Result']['comment'] ?></td><?php } ?>
			</tr>
		  <?php 
			endforeach ?>
		</table>
	</div>
	<script>


  $(document).ready(function(){
	
		<?php if($config['interactive'])
		{
		?>
  
      var tfConfig1 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
			  extensions: {
					name:['ColsVisibility'],
					src:['<?php echo $this->webroot ?>/js/TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.js'],
					description:['Columns visibility manager'],
					initialize:[function(o){o.SetColsVisibility(); o.HideCol(0);}]
					},
              on_after_refresh_counter: function(o,i){ histogram('resultsStatValues','resultsChart', true);  }
              };
              tf = new TF('resultsStatValues', tfConfig1); tf.AddGrid();
	<?php }else{ ?>
		histogram('resultsStatValues','resultsChart');
	<?php } ?>
	
  });
	</script>
</div>