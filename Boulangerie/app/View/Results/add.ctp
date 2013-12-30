<div class="results form">
<form id="resultsDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" value="Valider" />
</form>
<form id="resultsAdd" method="POST" >
<input type="hidden" id="date" name="date" value="<?php echo $date ?>" />
<h2>Le <?php echo $date ?></h2>
<ul>
<?php foreach($shops as $shopId => $shopName){ ?>
<li>
	<h3><?php echo $shopName; ?></h3>
	    <fieldset>
	      <?php
		  $resultId='';
		  $cash = '';
		  $check = '';
		  if(isset($data['entries'][$shopId]['entries'][0]))
		  {
		    $resultId=$data['entries'][$shopId]['entries'][0]['resultId'];
		    $cash = $data['entries'][$shopId]['entries'][0]['cash'];
		    $check = $data['entries'][$shopId]['entries'][0]['check'];
		  }
		?>
		    <legend>Totaux</legend>
		    <label>Especes</label><input type="text" name="Result[<?php echo $shopId; ?>][cash]" value="<?php echo $cash ?>" size="10" />€
		    <br/>
		    <label>Cheques</label><input type="text" name="Result[<?php echo $shopId; ?>][check]" value="<?php echo $check ?>" size="10" />€
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
	  <td><input type="text" name="Result[<?php echo $shopId ?>][productTypes][<?php echo $typeId ?>][result]"  value="<?php echo $result ?>" size="10" />€
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
<?php echo $this->Form->end(__('Valider')); ?>
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
  });

</script>