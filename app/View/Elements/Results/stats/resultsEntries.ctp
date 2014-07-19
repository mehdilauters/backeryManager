<?php
$defaultConfig = array('id'=>0,'interactive'=>true);
	if(isset($config))
	{
		$config = array_merge($defaultConfig, $config);
	}
	else
	{
		$config = $defaultConfig;
	}
	
	
	
 $fields = array('date'=>true, 'shop'=>true, 'productType'=>true);

if(isset($this->request->data['group']))
{
    $resultsEntries['group'] = $this->request->data['group'];
}
 
 
 if($resultsEntries['group']['shop'])
  {
    if( $resultsEntries['group']['time'] == '' )
    {
      $fields['date'] = false;
    }
	 if( ! $resultsEntries['group']['productType'] )
    {
      $fields['productType'] = false;
    }
  }
  
  if($resultsEntries['group']['time'] != '')
  {
    if( ! $resultsEntries['group']['shop'] )
    {
      $fields['shop'] = false;
    }
	if( ! $resultsEntries['group']['productType']  )
    {
      $fields['productType'] = false;
    }
	
  if($resultsEntries['group']['productType'] )
  {
		if( ! $resultsEntries['group']['shop'] )
		{
		  $fields['shop'] = false;
		}
		if( $resultsEntries['group']['time'] == '' )
		{
		  $fields['date'] = false;
		}
	}
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

<div id="resultsEntriesChart_<?php echo $config['id'] ?>Container" >
	<div>
		<h3>Historique comptable par <?php echo $titleDate[$resultsEntries['group']['time']] ?></h3>
		<div id="resultsEntriesChart_<?php echo $config['id'] ?>" class="chartDiv" >Chargement en cours... <img src="<?php echo $this->webroot ?>img/icons/load.gif" /></div>
		<div class="control"></div>
	</div>
	<div class="<?php if(!$config['interactive']) echo 'hideJs' ?>" >
		<table id="resultsEntriesStatValues_<?php echo $config['id'] ?>Legend" class="hideJs">
			<tr>
				<td class="noDisplay approxColumn label_curve_totalApprox" >Total € (approximation)</td>
				<td class="label_curve_total" >Total € </td>
				<?php
				  $curves = array();
				  if($fields['productType'] && $fields['shop'])
				  {
				    foreach($resultsEntries['productTypes'] as $productTypeId => $productTypeName)
				    {
				      foreach($resultsEntries['shops'] as $shopId => $shopName)
				      {
					$curves['productType'.$productTypeId.'_shop'.$shopId] = $productTypeName.' '.$shopName;
				      }
				    }
				  }
				  else
				  {
				    if(! $fields['productType'] && !$fields['shop'])
				    {
				      $curves['all'] = 'Tout';
				    }
				    else
				    {
				      if($fields['productType'])
				      {
					foreach($resultsEntries['productTypes'] as $productTypeId => $productTypeName)
					{
					    $curves['productType'.$productTypeId] = $productTypeName;
					}
				      }
				      
				      if($fields['shop'])
				      {
					foreach($resultsEntries['shops'] as $shopId => $shopName)
					{
					    $curves['shop'.$shopId] = $shopName;
					}
				      }
				    }
				  }
				
				  foreach($curves as $id => $name)
				  { ?>
				    <td class="label_curve_<?php echo $id ?>" ><?php echo  $name; ?></td>
				    <td class="noDisplay approxColumn label_curve_approx_<?php echo $id ?>" ><?php echo  $name; ?> (approximation) </td>
				 <?php } ?>

			</tr>
		</table>
		<table id="resultsEntriesStatValues_<?php echo $config['id'] ?>" class="table table-striped tablePreview">
		  <tr class="legend plot" >
			<?php if($fields['date']) { ?><th>Date</th><?php } ?>
			<?php if($fields['shop']) { ?><th class="shop" >Magasin</th> <?php } ?>
			<th class="productType" >Type de Produit</th>
			<th class="cash" >Somme €</th>
			<th class="noDisplay approxColumn cash" >Somme approx €</th>
			<?php if($fields['date']) { ?><th class="date" style="display:none" >Date</th><?php } ?>
		  </tr>
			<?php 
			$i = 0;
			 foreach($resultsEntries['resultsEntries'] as $i=>$resultsEntry):
			 $date = new DateTime($resultsEntry['ResultsEntry']['date']);
			 
			 //$total = $result[0]['total'];
			?>
			<tr class=" plot" >
			  <?php if($fields['date']) { ?>
			  <td>
				<?php //debug($resultsEntries['group']['time']);
					switch($resultsEntries['group']['time'])
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
			  <?php } 
			    $curveId = '';
			    if($fields['productType'] && $fields['shop'])
			    {
				$curveId = 'productType'.$resultsEntry['ProductTypes']['id'].'_shop'.$resultsEntry['Shop']['id'];
			    }
			    else
			    {
				if(! $fields['productType'] && !$fields['shop'])
				{
				  $curveId = 'all';
				}
				else
				{
				  if($fields['productType'])
				  {
				    $curveId = 'productType'.$resultsEntry['ProductTypes']['id'];
				  }
				  
				  if($fields['shop'])
				  {
				    $curveId = 'shop'.$resultsEntry['Shop']['id'];
				  }
			      }
			    }
			  ?>
			  <td class="shop" ><?php if($fields['shop']) { echo  $resultsEntry['Shop']['name']; } else { echo 'Tous'; } ?></td>
			  <td class="productType "><?php if($fields['productType']) { echo $resultsEntry['ProductTypes']['name']; } else {echo 'Tous';} ?></td>
			  <td class="rowTotal curve_total curve_<?php echo $curveId; ?> "><?php echo round($resultsEntry[0]['result'], 2) ?></td>
			  <td class="approxColumn noDisplay  rowTotal  curve_totalApprox curve_approx_<?php echo $curveId; ?>  ?>"><?php echo round($resultsEntry[0]['approximation'], 2) ?></td>
			   <?php if($fields['date']) { ?><td class="date" style="display:none" ><?php 
						switch($resultsEntries['group']['time'])
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
			  ?></td>
			  <?php } ?>
		  
			</tr>
		  <?php
			$i++;
		  endforeach ?>
		</table>
	</div>
	<script>
  $(document).ready(function(){
  
  		<?php if($config['interactive'])
		{
		?>
// 		// js exception
// 			var tfConfig2 = {
//               base_path: '<?php echo $this->webroot ?>js/TableFilter/',
// 			  /*extensions: {
// 					name:['ColsVisibility'],
// 					src:['<?php echo $this->webroot ?>/js/TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.js'],
// 					description:['Columns visibility manager'],
// 					initialize:[function(o){o.SetColsVisibility(); o.HideCol(0); o.HideCol(3);}]
// 					},*/
//               rows_counter:true,
//               on_after_refresh_counter: function(o,i){ histogram('resultsEntriesStatValues_<?php echo $config['id'] ?>','resultsEntriesChart_<?php echo $config['id'] ?>', true); }
//               };
// 			  
// // 			  setFilterGrid("resultsEntriesStatValues",tfConfig2);  
// // 			  
// 			tf1 = new TF('resultsEntriesStatValues_<?php echo $config['id'] ?>', tfConfig2); tf1.AddGrid();
// 			
			histogram('resultsEntriesStatValues_<?php echo $config['id'] ?>','resultsEntriesChart_<?php echo $config['id'] ?>');
				<?php }else{ ?>
		histogram('resultsEntriesStatValues_<?php echo $config['id'] ?>','resultsEntriesChart_<?php echo $config['id'] ?>');
	<?php } ?>
				
	
  });
</script>


</div>