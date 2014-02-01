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
	$fields = array('date'=>true, 'day'=>true, 'week'=> true, 'shop'=>true);
	
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
  }
?>
<div>
	<div>
		<div id="resultsChart" class="chartDiv" ></div>
		<div class="control"></div>
	</div>
	<div class="<?php if(!$config['interactive']) echo 'hideJs' ?>" >
		<table id="resultsStatValues" class="tablePreview" >
		  <tr class="legend plot" >
			<?php if($fields['date']) { ?><th class="date" >Date</th><?php } ?>
			<?php if($fields['day']) { ?><th class="day" >Jour</th><?php } ?>
			<?php if($fields['week']) { ?><th class="week" >Semaine</th><?php } ?>
			<?php if($fields['shop']) { ?><th class="shop" >Magasin</th><?php } ?>
			<th class="rowTotal label_curve_total" >Total</th>
			<th class="cash" >Especes</th>
			<th class="check" >Cheques</th>
			<th class="comment" >Commentaire</th>
		  </tr>
			<?php 
			$i = 0;
			 foreach($results as $i=>$result):
			 
			 $rowClass = 'even';
				if($i % 2 != 0)
				{
				  $rowClass = 'odd';
				}
			 
			 $date = new DateTime($result['Result']['date']);
			 $total = $result[0]['total'];
			?>
			<tr class="<?php echo $rowClass ?> plot" >
			  <?php if($fields['date']) { ?><td class="date" ><?php echo $date->format('d/m/Y'); ?></td><?php } ?>
			  <?php if($fields['day']) { ?><td class="day" ><?php echo $this->Dates->getJourFr($date->format('w')); ?></td><?php } ?>
			  <?php if($fields['week']) { ?><td class="week" ><?php echo $date->format('W'); ?></td><?php } ?>
			  <?php if($fields['shop']) { ?><td class="shop label_curve_Shop<?php echo  $result['Shop']['id']; ?>" ><?php echo  $result['Shop']['name']; ?></td><?php } ?>
			  <td class="rowTotal curve_total curve_Shop<?php echo  $result['Shop']['id']; ?>"><?php echo round($total,2) ?></td>
			  <td class="cash"><?php if($total != 0){ echo round($result[0]['cash'] / $total *100, 2); } ?></td>
			  <td class="check"><?php if($total != 0){ echo round($result[0]['check'] / $total *100, 2); } ?></td>
			  
			  <td class="comment" ><?php echo $result['Result']['comment'] ?></td>
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
  
      var tfConfig1 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ histogram('resultsStatValues','resultsChart', true); }
              };
              tf = new TF('resultsStatValues', tfConfig1); tf.AddGrid();
	<?php }else{ ?>
		histogram('resultsStatValues','resultsChart');
	<?php } ?>
	
  });
	</script>
</div>