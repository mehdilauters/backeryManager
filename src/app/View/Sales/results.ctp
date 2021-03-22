<div class="results">
<form id="salesDateSelect" method="POST" >
  <input type="text" class="datepicker" name="dateStart" id="dateStartValue" value="<?php echo $dateStart ?>" />
  <input type="text" class="datepicker" name="dateEnd" id="dateEndValue" value="<?php echo $dateEnd ?>" />
  <input type="submit" name="dateSelect" id="dateSelect" value="Select" />
<ul>
<?php 
  
  foreach($data as $shopId => $productsTypesData)
  {
    ?>
    <li>
      <h3><?php echo $shops[$shopId] ?></h3>
	<table>
	  <tr>
<!-- 	  <th>date</th> -->
	  <?php
	  foreach($productsTypesData as $typeId => $productsType)
	  {
// 	    debug($productsType);
	    ?>
	    <th><?php echo $productTypes[$typeId] ?></th>
	  <?php }
	  ?>
	 </tr>
	<tr>
	<?php
// 	  $date = true;
	  foreach($productsTypesData as $typeId => $productsType)
	  {
// 	    if($date)
// 	    {
// 	      $date = false;
// 	      $dateTime = new DateTime($productsType['Sales'][0]['Sale']['date']);
// 	      echo '<td>'.$dateTime->format('d/m/Y').'</td>';
// 	    }

	    ?>
	     <td>
		<?php 
			$breakage = 0;
			if(isset($productsType['Breakages'][0][0]['breakage']))
			{
			  $breakage = $productsType['Breakages'][0][0]['breakage'];
			}
			$price = 0;
			if( isset($productsType['Sales'][0][0]['price']) )
			{
			  $price = $productsType['Sales'][0][0]['price'];
			}
			echo round(($price - $breakage),2) ?>
	    </td>
	<?php
	  }
	    ?>
	</tr>
      </table>
    </li>
<?php
  }


?>
</ul>
</div>
