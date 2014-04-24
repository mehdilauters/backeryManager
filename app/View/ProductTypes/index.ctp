<div class="productTypes index">

<?php   
if($tokens['isAdmin'])
{
	echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$resultsEntries, 'config'=>array('interactive'=>false))); 
}
	?>


<!--<label for="toggleTodayProductsButton" >Afficher uniquement les produits du jour</label>
<input id="toggleTodayProductsButton" type="checkbox" name="toggleTodayProductsButton" onChange="toggleTodayProducts()" />-->
<script>


function toggleTodayProducts()
{
  if($('#toggleTodayProductsButton').is(':checked') )
  {
    $('.productPreview:not(.today)').hide();
  }
  else
  {
    $('.productPreview:not(.today)').show();
  }
  
}

$(document).ready(function(){  
    $('#toggleTodayProductsButton').attr('checked', false);
	$(".productTypeContent").click(function() {
			var divId = $( this ).attr('id');
			var id= divId.replace('productTypeContent_','');
			console.log(id);
			$("#productList_" + id).toggle('slow');
		});
  //$('.productPreview:not(.today)').hide();
  });
</script>
<ul id="productTypesList" >
  <?php foreach ($productTypes as $productType): ?>
  <li class="">
    <a name="productType_<?php echo $productType['ProductType']['id'] ?>" />
    <?php 
//debug($productType);
     echo $this->element('ProductTypes/Preview', array('productType'=>$productType, 'tokens'=>$tokens)); ?>
<?php if(isset($productType['Product'])) { ?>
    <ul id="productList_<?php echo $productType['ProductType']['id'] ?>" class="hideJs" >
      <?php foreach ($productType['Product'] as $product): ?>
	<li >
	  <?php echo $this->element('Products/Preview', array('product'=>$product, 'isCalendarAvailable', $isCalendarAvailable, 'tokens'=>$tokens)); ?>	  
      </li>
      <?php endforeach; ?>      
    </ul>
   <?php } ?>
  </li>
<?php endforeach; ?>
</ul>
</div>
  <?php if($tokens['isAdmin']) : ?>
    <div class="actions">
      <h3><?php echo __('Actions'); ?></h3>
      <ul>
      <li><?php echo $this->Html->link(__('New Product Type'), array('action' => 'add')); ?></li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Products'), array('controller' => 'products', 'action' => 'add')); ?> </li>
  </ul>
  </div>
    <?php endif ?>
