<div class="results form">
<form id="resultsDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" value="Select" />
</form>
<form id="resultsAdd" method="POST" >
<input type="hidden" id="date" name="date" value="<?php echo $date ?>" />
<ul>
<?php foreach($shops as $shopId => $shopName){ ?>
<li>
	    <fieldset>
	      <?php
		  $resultId='';
		  $cash = '';
		  $check = '';
		  if(isset($data[$shopId]))
		  {
		    $resultId=$data[$shopId]['resultId'];
		    $cash = $data[$shopId]['cash'];
		    $check = $data[$shopId]['check'];
		  }
		?>
		    <legend><?php echo $shopName; ?></legend>
		    <label>Especes</label><input type="text" name="Result[<?php echo $shopId; ?>][cash]" value="<?php echo $cash ?>" />
		    <label>Cheques</label><input type="text" name="Result[<?php echo $shopId; ?>][check]" value="<?php echo $check ?>" />
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
	  if(isset($data[$shopId]['productTypes'][$typeId]))
	  {
	    $result = $data[$shopId]['productTypes'][$typeId]['result'];
	    $resultEntryId = $data[$shopId]['productTypes'][$typeId]['resultEntryId'];  
	  }
      ?>
	<tr>
	  <td><?php echo $typeName ?></td>
	  <td><input type="text" name="Result[<?php echo $shopId ?>][productTypes][<?php echo $typeId ?>][result]"  value="<?php echo $result ?>" />
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
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Results'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
	</ul>
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