<?php
$defaultConfig = array('interactive'=>true, 'shopComparative'=>false);
	if(isset($config))
	{
	
		$config = array_merge($defaultConfig, $config);
	}
	else
	{
		$config = $defaultConfig;
	}
	
	
	
 $group = array('time' => '', 'shop'=>'', 'productType'=>'');
 $fields = array('date'=>true, 'day'=>true, 'week'=> true, 'shop'=>true, 'productType'=>true);

 
 
 if($group['shop'] != '')
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
      $fields['day'] = false;
      $fields['week'] = false;
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
		  $fields['day'] = false;
		  $fields['week'] = false;
		}
	}
  }
?>

<div>
	<div>
		<div id="resultsEntriesChart" class="chartDiv" ></div>
		<div class="control"></div>
	</div>
	<div class="<?php if(!$config['interactive']) echo 'hideJs' ?>" >
		<table id="resultsEntriesStatValues" class="tablePreview">
		  <tr class="legend plot" >
			<?php if($fields['date']) { ?><th class="date" >Date</th><?php } ?>
			<?php if($fields['day']) { ?><th class="day" >Jour</th><?php } ?>
			<?php if($fields['week']) { ?><th class="week" >Semaine</th><?php } ?>
			<?php if($fields['shop']) { ?><th class="shop" >Magasin</th><?php } ?>
			<?php if($fields['productType']) { ?><th class="productType" >Type de Produit</th><?php } ?>
			<th class="cash label_curve_total" >Somme €</th>
		  </tr>
			<?php 
			$i = 0;
			 foreach($resultsEntries as $i=>$resultsEntry):
				 $rowClass = 'even';
				if($i % 2 != 0)
				{
				  $rowClass = 'odd';
				}
			 $date = new DateTime($resultsEntry['ResultsEntry']['date']);
			 
			 $curveShopComparative = '';
			if($config['shopComparative'])
			{
				$curveShopComparative = 'curve_productType'.$resultsEntry['ProductTypes']['id'].'_shop'.$resultsEntry['Shop']['id'];
			}
			 
			 //$total = $result[0]['total'];
			?>
			<tr class="<?php echo $rowClass ?> plot" >
			  <?php if($fields['date']) { ?><td class="date" ><?php echo $date->format('d/m/Y'); ?></td><?php } ?>
			  <?php if($fields['day']) { ?><td class="day" ><?php echo $this->Dates->getJourFr($date->format('w')); ?></td><?php } ?>
			  <?php if($fields['week']) { ?><td class="week" ><?php echo $date->format('W'); ?></td><?php } ?>
			  <?php if($fields['shop']) { ?><td class="shop label_<?php echo $curveShopComparative ?>" ><?php echo  $resultsEntry['Shop']['name']; ?></td><?php } ?>
			  <?php if($fields['productType']) { ?><td class="productType label_curve_productType<?php echo $resultsEntry['ProductTypes']['id']; ?>"><?php echo $resultsEntry['ProductTypes']['name']; ?></td><?php } ?>
			  <td class="rowTotal  curve_total curve_productType<?php echo $resultsEntry['ProductTypes']['id'].' '.$curveShopComparative; ?>  ?>"><?php echo round($resultsEntry[0]['result'], 2) ?></td>
		  
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
			var tfConfig2 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ histogram('resultsEntriesStatValues','resultsEntriesChart', true); }
              };
			tf1 = new TF('resultsEntriesStatValues', tfConfig2); tf1.AddGrid();
				<?php }else{ ?>
		histogram('resultsEntriesStatValues','resultsEntriesChart');
	<?php } ?>
				
	
  });
</script>


</div>