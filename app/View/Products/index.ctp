<div class="products index">
  <h2><?php echo __('Products'); ?></h2>
<ul>
  <?php 
  $productTypeId = -1;
  foreach ($products as $product): 
	if($productTypeId == -1 )
	{
	  $productTypeId = $product['Product']['product_types_id'];
	  ?>
	    <li class="row">
	      <?php echo $this->Html->link($product['ProductType']['name'], array('controller' => 'product_types', 'action' => 'view', $product['ProductType']['id'])); ?>
<?php
	}
	if($productTypeId != $product['Product']['product_types_id'])
	{	
	  $productTypeId = $product['Product']['product_types_id'];
	  ?>
	    </li>
	    <li class="row">
	      <?php echo $this->Html->link($product['ProductType']['name'], array('controller' => 'product_types', 'action' => 'view', $product['ProductType']['id'])); ?>
<?php	}


?>
<?php echo $this->element('Products/Preview', array('product'=>$product));

endforeach; ?>
</div>
<?php if($tokens['isAdmin']) : ?>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
      <li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?></li>
      <li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add', 'idProduct'=>$product['Product']['id'])); ?> </li>
  </ul>
</div>
<?php endif ?>
