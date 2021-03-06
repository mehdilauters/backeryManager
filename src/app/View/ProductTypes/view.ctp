<div class="productTypes view">
<?php   
if($tokens['isAdmin'])
{
	echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$resultsEntries, 'config'=>array('interactive'=>false, 'shopComparative'=>true))); 
}
	?>
  <?php echo $this->element('ProductTypes/Preview', array('productType'=>$productType)); ?>
</div>
<div class="actions">
  <?php if($tokens['isAdmin']) : ?>
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
<div class="clear" ></div>
<div class="related">
  <h3><?php echo __('Produits associés'); ?></h3>
  <?php if (!empty($productType['Product'])): ?>
  <ul id="productList">
  <?php
    $i = 0;
    foreach ($productType['Product'] as $product): ?>
    <li>
      <?php echo $this->element('Products/Preview', array('product'=>$product, 'tokens'=>$tokens)); ?>
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
