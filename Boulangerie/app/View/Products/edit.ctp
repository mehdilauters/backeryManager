<div class="products form">
<?php echo $this->Form->create('Product'); ?>
  <fieldset>
    <legend><?php echo __('Edit Product'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('product_types_id');
    echo $this->Form->input('media_id');
    echo $this->Form->input('name');
    echo $this->Form->input('customer_display');
    echo $this->Form->input('production_display');
    echo $this->Form->input('description');
    echo $this->Form->input('price');
    echo $this->Form->input('unity');
    echo $this->Form->input('depends_on_production');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Product.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Product.id'))); ?></li>
    <li><?php echo $this->Html->link(__('List Products'), array('action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Events'), array('controller' => 'events', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
  </ul>
</div>
