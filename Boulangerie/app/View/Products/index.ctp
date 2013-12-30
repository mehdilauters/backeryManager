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
	    <li>
	      <?php echo $this->Html->link($product['ProductType']['name'], array('controller' => 'product_types', 'action' => 'view', $product['ProductType']['id'])); ?>
<?php
	}
	if($productTypeId != $product['Product']['product_types_id'])
	{	
	  $productTypeId = $product['Product']['product_types_id'];
	  ?>
	    </li>
	    <li>
	      <?php echo $this->Html->link($product['ProductType']['name'], array('controller' => 'product_types', 'action' => 'view', $product['ProductType']['id'])); ?>
<?php	}


?>
<?php echo $this->element('Products/Preview', array('product'=>$product));

endforeach; ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <?php if($tokens['isAdmin']) : ?>
      <li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?></li>
      <li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add', 'idProduct'=>$product['Product']['id'])); ?> </li>
    <?php endif ?>
    <li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Events'), array('controller' => 'events', 'action' => 'index')); ?> </li>
  </ul>
</div>
