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
	
	$group = array('time' => '', 'shop'=>'', 'productType'=>'');
	$fields = array('date'=>true, 'shop'=>true, 'comment'=> false);
	
	if(isset($this->request->data['group']))
  {
    $group['time'] = $this->request->data['group']['time'];
    $group['shop'] = $this->request->data['group']['shop'];
  }
  
  
  
    if($group['shop'] != '')
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
    }
	 if( $group['productType'] == '' )
    {
      $fields['productType'] = false;
    }
  }
  
  if($group['time'] != '')
  {
    if( $group['shop'] == '' )
    {
      $fields['shop'] = false;
    }
  }
  
  if($group['time'] == 'day')
  {
	$fields['comment'] = true;
  }
  
?>
<div>
	<div>
		<div id="resultsChart" class="chartDiv" ></div>
		<div class="control"></div>
	</div>
	<div class="<?php if(!$config['interactive']) echo 'hideJs' ?>" >
		<table id="resultsStatValues" class="tablePreview table-striped" >
		  <tr class="legend plot" >
			<?php if($fields['date']) { ?><th class="date" style="display:none" >Date</th>
				<th>date</th>
			<?php } ?>
			<?php if($fields['shop']) { ?><th class="shop" >Magasin</th><th class="shop" >Approximation</th><?php } ?>
			<th class="rowTotal label_curve_total label_curve_totalApprox" >Total</th>
			<th class="cash" >Especes %</th>
			<th class="check" >Cheques %</th>
			<th class="card" >Carte Bleue %</th>
			<?php if($fields['comment']) { ?><th class="comment" >Commentaire</th><?php } ?>
		  </tr>
			<?php 
		foreach($results as $i=>$result):
			$date = new DateTime($result['Result']['date']);
			$total = $result[0]['total'];
			?>
			<tr class="plot" >
			  <?php if($fields['date']) { ?>
				<td class="date" style="display:none" >
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
			  <?php if($fields['shop']) { ?>
				<td class="shop label_curve_Shop<?php echo  $result['Shop']['id']; ?> label_curve_ShopApprox<?php echo  $result['Shop']['id']; ?>" ><?php echo  $result['Shop']['name']; ?></td>
				<td class="shop curve_totalApprox curve_ShopApprox<?php echo  $result['Shop']['id']; ?>" ><?php 
					/*$coefficients = unserialize($result['Shop']['equation_parameters']);
					
					$lastDateDiff = $initDate->diff($lastDate);
					$Y = 0;
					$dateDiff = $initDate->diff($date);
					// $x = $dateDiff->days;
					// debug($lastDateDiff->days);
					// debug($dateDiff->days);
					
					$xMax = min($lastDateDiff->days+$nbDaysMax,$dateDiff->days);
					$xMin = $lastDateDiff->days;
					
					for($j = $xMin; $j<= $xMax ; $j++)
					{
						$y = 0;
						$order = 0;
						// $str = '';
						foreach($coefficients as $coef)
						{
							// $str .= '+ ( ' .$coef.'x^'.$order.' )';
							 $y += $coef * pow($j,$order);
							$order++;
						}
						$Y += $y;
					}
					if($y > 0)
					{
						echo  $Y / ($xMax - $xMin +1);
					}
					else
					{
						echo 0;
					}
					$lastDate = $date;*/
					echo $result[0]['approximation'];
					?></td>
			<?php } ?>
			  <td class="rowTotal noDisplay curve_total curve_Shop<?php echo  $result['Shop']['id']; ?>"><?php echo round($total,2) ?></td>
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