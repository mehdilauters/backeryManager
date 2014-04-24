<div class="results index">
<div>
  <?php
	$group = array('time' => '', 'shop'=>'', 'productType'=>'');
	$conditions = array('shop'=>'', 'productType'=>'');
	
	if(isset($this->request->data['group']))
  {
    $group['time'] = $this->request->data['group']['time'];
    $group['shop'] = $this->request->data['group']['shop'];
	$group['productType'] = $this->request->data['group']['productType'];
  }
  
    if(isset($this->request->data['conditions']))
  {
    $conditions['shop'] = $this->request->data['conditions']['shop'];
	$conditions['productType'] = $this->request->data['conditions']['productType'];
  }
  ?>
  
  <form method="POST" >
	<fieldset>
		<legend>Grouper par</legend>
		<label>date</label>
		<select name="group[time]" >
		  <option value="" ></option>
		  <option value="weekday" <?php echo ($group['time'] == 'weekday') ? 'selected="selected"' : ''; ?>  >jour de la semaine</option>
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
		  <option value="productType" <?php echo ($group['productType'] == 'productType') ? 'selected="selected"' : ''; ?> >Type de produit</option>
		</select>
	</fieldset>
	<fieldset>
		<legend>filtrer par</legend>
	<label>Magasin</label>
    <select name="conditions[shop]" >
      <option value="" ></option>
	  <?php foreach($shops as $id => $shop): ?>
		<option value="<?php echo $id; ?>" <?php echo ($conditions['shop'] == $id ) ? 'selected="selected"' : ''; ?>  ><?php echo $shop; ?></option>
	  <?php endforeach; ?>
    </select>
	<label>Types de produit</label>
    <select name="conditions[productType]" >
      <option value="" ></option>
	  <?php foreach($productTypes as $id => $productType): ?>
		<option value="<?php echo $id; ?>" <?php echo ($conditions['productType'] == $id ) ? 'selected="selected"' : ''; ?>  ><?php echo $productType; ?></option>
	  <?php endforeach; ?>
    </select>
	</fieldset>
    <input type="submit" class="search" value="" />
  </form>
</div>
<?php   //echo $this->element('Results/stats', array('results'=>$results, 'resultsEntries'=>$resultsEntries, 'shops'=>$shops, 'productTypes'=>$productTypes)); ?>
<?php   echo $this->element('Results/stats/results', array('results'=>$results)); ?>
<?php   echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$resultsEntries, 'config'=>array('shopComparative'=>true))); ?>
</div> 