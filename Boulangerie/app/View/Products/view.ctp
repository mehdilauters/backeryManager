<?php
echo $this->Html->script(
    array( 
//   'jquery-1.9.1.min',
//         'jquery-ui-1.10.1.custom.min',
         'fullcalendar.min',
         'jquery.qtip-1.0.0-rc3.min',
        ),
     array('inline' => 'false')
  );
echo $this->Html->css(
          'fullcalendar',
           null,
           array('inline' => false)
  );
?>

<div class="products view">
  <?php echo $this->element('Products/Preview', array('product'=>$product, 'tokens'=>$tokens)); ?>
<h3>Dans la catégorie</h3>
<?php echo $this->element('ProductTypes/Preview', array('productType'=>$product, 'tokens'=>$tokens)); ?>
</div>
<?php if($tokens['isAdmin']) : ?>
<div class="actions">
  <ul>
      <li><?php echo $this->Html->link(__('Edit Product'), array('action' => 'edit', $product['Product']['id'])); ?> </li>
      <li><?php echo $this->Form->postLink(__('Delete Product'), array('action' => 'delete', $product['Product']['id']), null, __('Are you sure you want to delete # %s?', $product['Product']['id'])); ?> </li>
      <li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add', 'idProduct'=>$product['Product']['id'])); ?> </li>
	  <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
  </ul>
</div>
<?php endif ?>
