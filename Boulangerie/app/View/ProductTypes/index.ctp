<div class="productTypes index">
<ul>
<?php foreach ($productTypes as $productType): ?>
  <li>
     <a href="#productType_<?php echo $productType['ProductType']['id']  ?>" ><?php echo $productType['ProductType']['name']  ?></a>
  </li>
<?php endforeach; ?>
</ul>
<ul id="productTypesList" >
  <?php foreach ($productTypes as $productType): ?>
  <li class="clear">
    <hr/>
    <a name="productType_<?php echo $productType['ProductType']['id'] ?>" />
    <?php 
//debug($productType);
     echo $this->element('ProductTypes/Preview', array('productType'=>$productType)); ?>
    <hr/>
    <ul id="productList" >
      <?php foreach ($productType['Products'] as $product): ?>
	<li >
	  <?php echo $this->element('Products/Preview', array('product'=>$product, 'isCalendarAvailable', $isCalendarAvailable)); ?>	  
      </li>
      <?php endforeach; ?>      
    </ul>
   
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
