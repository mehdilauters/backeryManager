<?php

$defaultConfig = array('id'=>0,'interactive'=>true, 'shopComparative'=>false);
	if(isset($config))
	{
		$config = array_merge($defaultConfig, $config);
	}
	else
	{
		$config = $defaultConfig;
	}
	
	
	
 $group = array('time' => '', 'shop'=>'', 'productType'=>'');
 $fields = array('date'=>true, 'shop'=>true, 'productType'=>true);

if(isset($this->request->data['group']))
{
    $group = $this->request->data['group'];
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
	if( $group['productType'] == '' )
    {
      $fields['productType'] = false;
    }
	
  if($group['productType'] != '')
  {
		if( $group['shop'] == '' )
		{
		  $fields['shop'] = false;
		}
		if( $group['time'] == '' )
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

<div>
	<div>
		<h3>Historique comptable par <?php echo $titleDate[$group['time']] ?></h3>
		<div id="resultsEntriesChart_<?php echo $config['id'] ?>" class="chartDiv" ></div>
		<div class="control"></div>
	</div>
	<div class="<?php if(!$config['interactive']) echo 'hideJs' ?>" >
		<table id="resultsEntriesStatValues_<?php echo $config['id'] ?>" class="table-striped tablePreview">
		  <tr class="legend plot" >
			<?php if($fields['date']) { ?><th>Date</th><?php } ?>
			<?php if($fields['shop']) { ?><th class="shop" >Magasin</th> <?php } ?>
			<?php if($fields['productType']) { ?><th class="productType" >Type de Produit</th><?php } ?>
			<th class="cash label_curve_total" >Somme €</th>
			<th class="cash label_curve_totalApprox" >Somme approx €</th>
			<?php if($fields['date']) { ?><th class="date" style="display:none" >Date</th><?php } ?>
			<?php if($fields['shop']) { ?> <th style="display:none" >Courbes</th> <?php } ?>
		  </tr>
			<?php 
			$i = 0;
			 foreach($resultsEntries as $i=>$resultsEntry):
			 $date = new DateTime($resultsEntry['ResultsEntry']['date']);
			 
			 $curveShopComparative = 'productType'.$resultsEntry['ProductTypes']['id'];
			if($config['shopComparative'])
			{
				$curveShopComparative = 'productType'.$resultsEntry['ProductTypes']['id'].'_shop'.$resultsEntry['Shop']['id'];
			}
			 
			 //$total = $result[0]['total'];
			?>
			<tr class=" plot" >
			  <?php if($fields['date']) { ?>
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
			  <?php if($fields['shop']) { ?><td class="shop" ><?php echo  $resultsEntry['Shop']['name']; ?></td><?php } ?>
			  <?php if($fields['productType']) { ?><td class="productType label_curve_productType<?php echo $resultsEntry['ProductTypes']['id']; ?>"><?php echo $resultsEntry['ProductTypes']['name']; ?></td><?php } ?>
			  <td class="rowTotal noDisplay curve_total curve_<?php echo $curveShopComparative; ?>  ?>"><?php echo round($resultsEntry[0]['result'], 2) ?></td>
			  <td class="rowTotal  curve_totalApprox curve_approx_<?php echo $curveShopComparative; ?>  ?>"><?php echo round($resultsEntry[0]['approximation'], 2) ?></td>
			   <?php if($fields['date']) { ?><td class="date" style="display:none" ><?php 
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
			  ?></td>
			  <?php } ?>
			  <?php if($fields['shop']) { ?><td class="shopProduct label_curve_<?php echo $curveShopComparative ?>" style="display:none" ><?php echo  $resultsEntry['ProductTypes']['name'].' '.$resultsEntry['Shop']['name']; ?></td><?php } ?>
			  <?php if($fields['shop']) { ?><td class="shopProduct label_curve_approx_<?php echo $curveShopComparative ?>" style="display:none" ><?php echo  $resultsEntry['ProductTypes']['name'].' '.$resultsEntry['Shop']['name']; ?></td><?php } ?>
		  
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
		// js exception
	/*		var tfConfig2 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
			  extensions: {
					name:['ColsVisibility'],
					src:['<?php echo $this->webroot ?>/js/TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.js'],
					description:['Columns visibility manager'],
					initialize:[function(o){o.SetColsVisibility(); o.HideCol(0); o.HideCol(3);}]
					},
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ histogram('resultsEntriesStatValues_<?php echo $config['id'] ?>','resultsEntriesChart_<?php echo $config['id'] ?>', true); }
              };
			  
			  setFilterGrid("resultsEntriesStatValues",tfConfig2);  
			  
			//tf1 = new TF('resultsEntriesStatValues', tfConfig2); tf1.AddGrid();
			*/
			histogram('resultsEntriesStatValues_<?php echo $config['id'] ?>','resultsEntriesChart_<?php echo $config['id'] ?>');
				<?php }else{ ?>
		histogram('resultsEntriesStatValues_<?php echo $config['id'] ?>','resultsEntriesChart_<?php echo $config['id'] ?>');
	<?php } ?>
				
	
  });
</script>


</div>