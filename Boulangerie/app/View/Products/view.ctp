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
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
  <?php   echo $this->element('Gevents/jsCalendar', array('data'=>$product)); ?>
  <script>
$(document).ready(function() {

});
  </script>
<div class="products view">
  <?php echo $this->element('Products/Preview', array('product'=>$product, 'tokens'=>$tokens)); ?>
  <div id="calendar" ></div>
<h3>Dans la catégorie</h3>
<?php echo $this->element('ProductTypes/Preview', array('productType'=>$product, 'tokens'=>$tokens)); ?>
</div>
<div class="actions">
  <ul>
    <?php if($tokens['isAdmin']) : ?>
      <li><?php echo $this->Html->link(__('Edit Product'), array('action' => 'edit', $product['Product']['id'])); ?> </li>
      <li><?php echo $this->Form->postLink(__('Delete Product'), array('action' => 'delete', $product['Product']['id']), null, __('Are you sure you want to delete # %s?', $product['Product']['id'])); ?> </li>
      <li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add', 'idProduct'=>$product['Product']['id'])); ?> </li>
	  <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
    <?php endif ?>
  </ul>
</div>
