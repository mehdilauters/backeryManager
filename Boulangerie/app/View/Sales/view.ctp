<div class="sales view">
<h2><?php  echo __('Sale'); ?></h2>
  <dl>
    <dt><?php echo __('Id'); ?></dt>
    <dd>
      <?php echo h($sale['Sale']['id']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Date'); ?></dt>
    <dd>
      <?php echo h($sale['Sale']['date']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Product'); ?></dt>
    <dd>
      <?php echo $this->Html->link($sale['Product']['name'], array('controller' => 'products', 'action' => 'view', $sale['Product']['id'])); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Shop Id'); ?></dt>
    <dd>
      <?php echo $this->Html->link($sale['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $sale['Shop']['id'])); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Produced'); ?></dt>
    <dd>
      <?php echo h($sale['Sale']['produced']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Sold'); ?></dt>
    <dd>
      <?php echo h($sale['Sale']['sold']); ?>
      &nbsp;
    </dd>
  </dl>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('Edit Sale'), array('action' => 'edit', $sale['Sale']['id'])); ?> </li>
    <li><?php echo $this->Form->postLink(__('Delete Sale'), array('action' => 'delete', $sale['Sale']['id']), null, __('Are you sure you want to delete # %s?', $sale['Sale']['id'])); ?> </li>
    <li><?php echo $this->Html->link(__('List Sales'), array('action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Sale'), array('action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
  </ul>
</div>
