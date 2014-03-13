<?php 
if(isset($this->request->data['group']))
{
	$group = $this->request->data['group'];
}

?>
<div>
  Grouper par
  <form method="POST" >
    <label>date</label>
    <select name="group[time]" >
      <option value="" ></option>
      <option value="day" <?php echo (isset($group['time']) && $group['time'] == 'day') ? 'selected="selected"' : ''; ?>  >jour</option>
      <option value="week" <?php echo (isset($group['time']) && $group['time'] == 'week') ? 'selected="selected"' : ''; ?>  >semaine</option>
      <option value="month" <?php echo (isset($group['time']) && $group['time'] == 'month') ? 'selected="selected"' : ''; ?> >mois</option>
      <option value="year" <?php echo (isset($group['time']) && $group['time'] == 'year') ? 'selected="selected"' : ''; ?> >année</option>
    </select>
    <label>Produit</label>
    <select name="group[product]" >
      <option value="" ></option>
      <option value="product" <?php echo (isset($group['product']) && $group['product'] == 'product') ? 'selected="selected"' : ''; ?> >Produit</option>
    </select>
    <label>Magasin</label>
    <select name="group[shop]">
      <option value="" ></option>
      <option value="shop" <?php echo (isset($group['shop']) && $group['shop'] == 'shop') ? 'selected="selected"' : ''; ?> >magasin</option>
    </select>
    <input type="submit" />
  </form>
</div>
<?php
echo $this->element('Sales/stats', array('sales'=>$sales)); 

?>