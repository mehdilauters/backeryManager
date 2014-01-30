
<?php
  $group = array('time' => '', 'product'=>'', 'shop'=>'');
  $fields = array('date'=>true, 'day'=>true, 'week'=> true, 'product' => true, 'productType'=>true, 'shop'=>true);
  if(isset($this->request->data['group']))
  {
    $group['time'] = $this->request->data['group']['time'];
    $group['product'] = $this->request->data['group']['product'];
    $group['shop'] = $this->request->data['group']['shop'];
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
    <label>Produit</label>
    <select name="group[product]" >
      <option value="" ></option>
      <option value="product" <?php echo ($group['product'] == 'product') ? 'selected="selected"' : ''; ?> >Produit</option>
    </select>
    <label>Magasin</label>
    <select name="group[shop]">
      <option value="" ></option>
      <option value="shop" <?php echo ($group['shop'] == 'shop') ? 'selected="selected"' : ''; ?> >magasin</option>
    </select>
    <input type="submit" />
  </form>
</div>
<div>
	<div id="histogramChart" style="width=500px;height=600px;" class="chartDiv" ></div>
	<div class="control" ></div>
</div>
<table id="statValues">
<tr class="plot" >
  <?php if($fields['date']) { ?><th>Date</th><?php } ?>
  <?php if($fields['day']) { ?><th class="day" >Jour</th><?php } ?>
  <?php if($fields['week']) { ?><th class="week" >Semaine</th><?php } ?>
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
      $rowClass = 'even';
      $class = 'lostProducts';
      if($sale[0]['lost'] <= 0 )
      {
        $class = 'notLostProducts';    
      }
      
      if($i % 2 != 0)
      {
          $rowClass = 'odd';
      }
   ?>
      <tr class="<?php echo $rowClass ?> plot" >
      <?php if($fields['date']) { ?><td class="date" ><?php echo $this->Time->format('d/m/Y',$sale['Sale']['date']); ?></td><?php } ?>
      <?php if($fields['day']) { ?><td class="day" ><?php echo $this->Dates->getJourFr(date('w',$this->Time->fromString($sale['Sale']['date']) )); ?></td><?php } ?>
      <?php if($fields['week']) { ?><td class="week" ><?php echo date('W',$this->Time->fromString($sale['Sale']['date']) ); ?></td><?php } ?>
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
      var tfConfig = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              //rows_counter_text: 'Selected files: ',
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
              
  });
</script>

