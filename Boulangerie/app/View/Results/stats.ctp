<div class="results index">
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/plotTable.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />

<hr/>
<hr/>
<?php
  $group = array('time' => '', 'shop'=>'', 'productType'=>'');
  $fields = array('date'=>true, 'day'=>true, 'week'=> true, 'shop'=>true);
  if(isset($this->request->data['group']))
  {
    $group['time'] = $this->request->data['group']['time'];
    $group['shop'] = $this->request->data['group']['shop'];
	$group['productType'] = $this->request->data['group']['productType'];
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
	if( $group['productType'] == '' )
    {
      $fields1['productType'] = false;
    }
  }
  
  $fields1 = $fields;
  if(!isset($fields1['productType']))
  {
    $fields1['productType'] = true;
  }
  

 ?>
<div>
  Grouper par
  <form method="POST" >
    <label>date</label>
    <select name="group[time]" >
      <option value="" ></option>
      <option value="day" <?php echo ($group['time'] == 'day') ? 'selected="selected"' : ''; ?>  >jour</option>
      <option value="week" <?php echo ($group['time'] == 'week') ? 'selected="selected"' : ''; ?>  >semaine</option>
      <option value="month" <?php echo ($group['time'] == 'month') ? 'selected="selected"' : ''; ?> >mois</option>
      <option value="year" <?php echo ($group['time'] == 'year') ? 'selected="selected"' : ''; ?> >année</option>
    </select>
    <label>Magasin</label>
    <select name="group[shop]">
      <option value="" ></option>
      <option value="shop" <?php echo ($group['shop'] == 'shop') ? 'selected="selected"' : ''; ?> >magasin</option>
    </select>
	<label>Type de produit</label>
	 <select name="group[productType]">
      <option value="" ></option>
      <option value="shop" <?php echo ($group['productType'] == 'productType') ? 'selected="selected"' : ''; ?> >Type de produit</option>
    </select>
    <input type="submit" />
  </form>
</div>
<div>
	<div id="resultsChart" class="chartDiv" ></div>
	<div class="control"></div>
</div>
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
<div>
	<div id="resultsEntriesChart" class="chartDiv" ></div>
	<div class="control"></div>
</div>
<table id="resultsEntriesStatValues" class="tablePreview">
  <tr class="legend plot" >
    <?php if($fields1['date']) { ?><th class="date" >Date</th><?php } ?>
    <?php if($fields1['day']) { ?><th class="day" >Jour</th><?php } ?>
    <?php if($fields1['week']) { ?><th class="week" >Semaine</th><?php } ?>
    <?php if($fields1['shop']) { ?><th class="shop" >Magasin</th><?php } ?>
    <?php if($fields1['productType']) { ?><th class="productType" >Type de Produit</th><?php } ?>
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
     //$total = $result[0]['total'];
    ?>
    <tr class="<?php echo $rowClass ?> plot" >
      <?php if($fields1['date']) { ?><td class="date" ><?php echo $date->format('d/m/Y'); ?></td><?php } ?>
      <?php if($fields1['day']) { ?><td class="day" ><?php echo $this->Dates->getJourFr($date->format('w')); ?></td><?php } ?>
      <?php if($fields1['week']) { ?><td class="week" ><?php echo $date->format('W'); ?></td><?php } ?>
	  <?php if($fields1['shop']) { ?><td class="shop" ><?php echo  $resultsEntry['Shop']['name']; ?></td><?php } ?>
	  <?php if($fields1['productType']) { ?><td class="productType label_curve_productType<?php echo $resultsEntry['ProductTypes']['id']; ?>"><?php echo $resultsEntry['ProductTypes']['name']; ?></td><?php } ?>
	  <td class="rowTotal  curve_total curve_productType<?php echo $resultsEntry['ProductTypes']['id']; ?>"><?php echo round($resultsEntry[0]['result'], 2) ?></td>
  
    </tr>
  <?php
	$i++;
  endforeach ?>
</table>
<script>
  $(document).ready(function(){
      var tfConfig1 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ histogram('resultsStatValues','resultsChart'); }
              };
              tf = new TF('resultsStatValues', tfConfig1); tf.AddGrid();
			  
              
              $('#histogramChart').bind("contextmenu",function(e){
                            histogramPlot.resetZoom();
                            return false;
            
                    });
			var tfConfig2 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ histogram('resultsEntriesStatValues','resultsEntriesChart'); }
              };
			tf1 = new TF('resultsEntriesStatValues', tfConfig2); tf1.AddGrid();
				
				
	
  });
</script>
</div>