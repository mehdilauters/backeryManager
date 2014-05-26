<div class="results form">
<form id="resultsDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" value="" class="dateSearch" />
</form>
<form id="resultsAdd" method="POST" >
<input type="hidden" id="date" name="date" value="<?php echo $date ?>" />
<h2>Le <?php echo $date ?></h2>
<ul id="resultShops" >
<?php foreach($shops as $shopId => $shopName){ ?>
<li id="resultsShop_<?php echo $shopId ?>" >
	<h3><?php echo $shopName; ?></h3>
	    <fieldset>
	      <?php
		  $resultId='';
		  $cash = '';
		  $check = '';
		  $card = '';
		  $comment = '';
		  if(isset($data['entries'][$shopId]['entries'][0]))
		  {
		    $resultId=$data['entries'][$shopId]['entries'][0]['resultId'];
		    $cash = $data['entries'][$shopId]['entries'][0]['cash'];
		    $check = $data['entries'][$shopId]['entries'][0]['check'];
			$card = $data['entries'][$shopId]['entries'][0]['card'];
		    $comment = $data['entries'][$shopId]['entries'][0]['comment'];
		  }
		?>
		    <legend>Totaux</legend>
		    <label>Especes</label><input id="resultShop_<?php echo $shopId ?>_cash" type="text" name="Result[<?php echo $shopId; ?>][cash]" value="<?php echo $cash ?>" size="10" class="spinner totalShop resultShop_<?php echo $shopId ?>" />€
		    <br/>
		    <label>Cheques</label><input id="resultShop_<?php echo $shopId ?>_check" type="text" name="Result[<?php echo $shopId; ?>][check]" value="<?php echo $check ?>" size="10" class="spinner totalShop resultShop_<?php echo $shopId ?>" />€
		    <br/>
			<label>Carte Bleue</label><input id="resultShop_<?php echo $shopId ?>_card" type="text" name="Result[<?php echo $shopId; ?>][card]" value="<?php echo $card ?>" size="10" class="spinner totalShop resultShop_<?php echo $shopId ?>" />€
		    <br/>
		    <label>Commentaire</label><textarea name="Result[<?php echo $shopId; ?>][comment]" ><?php echo $comment ?></textarea>
		    <input type="hidden" name="Result[<?php echo $shopId; ?>][resultId]" value="<?php echo $resultId; ?>" />
	    </fieldset>
	    <fieldset>
		    <legend><?php echo __('Product types'); ?></legend>
    <table>
      <tr>
	<th>Type de produit</th>
	<th>Prix</th>
      </tr>
      <?php foreach($productTypes as $typeId => $typeName){ ?>
      <?php
	  $result = '';
	  $resultEntryId = '';
	  if(isset($data['entries'][$shopId]['entries'][0]['productTypes'][$typeId]))
	  {
	    $result = $data['entries'][$shopId]['entries'][0]['productTypes'][$typeId]['result'];
	    $resultEntryId = $data['entries'][$shopId]['entries'][0]['productTypes'][$typeId]['resultEntryId'];  
	  }
      ?>
	<tr>
	  <td><?php echo $typeName ?></td>
	  <td><input type="text" name="Result[<?php echo $shopId ?>][productTypes][<?php echo $typeId ?>][result]"  value="<?php echo $result ?>" size="10" class="spinner totalShopCategory" />€
	  <input type="hidden" name="Result[<?php echo $shopId ?>][productTypes][<?php echo $typeId ?>][resultEntryId]"  value="<?php echo $resultEntryId ?>" />
	</td>
	</tr>
      <?php } ?>
    </table>
	    <?php
	    ?>
	    </fieldset>
</li>
<?php } ?>
</ul>
<input type="submit" value="" class="save" />
</div>
<script>

 $(document).ready(function(){



      $("#dateSelectValue").change(function(){
	  if( $(this).val() != $('#date').val() )
	  {
	    $('#resultsAdd').hide();
	  }
	  else
	  {
	    $('#resultsAdd').show();
	  }
	});
	var data = {};
	$('#resultShops li').each(function( index ) {
		var idShop = $( this ).attr('id');
	
		data[idShop] = {};
		data[idShop]['totalPrice'] = 0 ;
		data[idShop]['totalCategories'] = 0 ;
		$( this ).find('input.totalShop').each(function( index ) {
				var val = parseInt($(this).val());
				if( !isNaN(val))
				{
					data[idShop]['totalPrice'] += parseInt(val);
				}
			});
		$( this ).find('input.totalShopCategory').each(function( index ) {
				var val = parseInt($(this).val());
				if( !isNaN(val))
				{
					data[idShop]['totalCategories'] += parseInt(val);
				}
			});
	});
	
	for(var shop in data)
	{
		if(data[shop]['totalPrice'] != data[shop]['totalCategories'])
		{
			console.log(shop + ' nok');
		}
		else
		{
			console.log(shop + ' ok');
		}
	}
	
  });

</script>