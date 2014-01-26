<div class="productTypes view">
<h2><?php  echo $productType['ProductType']['name']; ?></h2>
  <?php echo $this->element('ProductTypes/Preview', array('productType'=>$productType)); ?>
</div>
<div class="actions">
  <?php debug($tokens);if($tokens['isAdmin']) : ?>
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
      <li><?php echo $this->Html->link(__('Edit Product Type'), array('action' => 'edit', $productType['ProductType']['id'])); ?> </li>
      <li><?php echo $this->Form->postLink(__('Delete Product Type'), array('action' => 'delete', $productType['ProductType']['id']), null, __('Are you sure you want to delete # %s?', $productType['ProductType']['id'])); ?> </li>
      <li><?php echo $this->Html->link(__('New Product Type'), array('action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Products'), array('controller' => 'products', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Product Types'), array('action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
  </ul>
<?php endif ?>
</div>
<div class="related">
  <h3><?php echo __('Produits'); ?></h3>
  <?php if (!empty($productType['Products'])): ?>
  <ul id="productList">
  <?php
    $i = 0;
    foreach ($productType['Products'] as $product): ?>
    <li>
      <?php echo $this->element('Products/Preview', array('product'=>$product, 'isCalendarAvailable'=> $isCalendarAvailable, 'tokens'=>$tokens)); ?>
    </li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php if($tokens['isAdmin']) : ?>
  <div class="actions">
    <ul>
      <li><?php echo $this->Html->link(__('New Products'), array('controller' => 'products', 'action' => 'add')); ?> </li>
    </ul>
  </div>
<?php endif; ?>
</div>
