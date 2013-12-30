<div class="productTypes view">
<h2><?php  echo __('Product Type'); ?></h2>
  <dl>
    <dt><?php echo __('Id'); ?></dt>
    <dd>
      <?php echo h($productType['ProductType']['id']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Media'); ?></dt>
    <dd>
       <?php echo $this->element('Medias/Medias/Preview', array('media'=>$productType));?>
      &nbsp;
    </dd>
    <dt><?php echo __('Name'); ?></dt>
    <dd>
      <?php echo h($productType['ProductType']['name']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Description'); ?></dt>
    <dd>
      <?php echo h($productType['ProductType']['description']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Created'); ?></dt>
    <dd>
      <?php echo h($productType['ProductType']['created']); ?>
      &nbsp;
    </dd>
  </dl>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <?php if($tokens['isAdmin']) : ?>
      <li><?php echo $this->Html->link(__('Edit Product Type'), array('action' => 'edit', $productType['ProductType']['id'])); ?> </li>
      <li><?php echo $this->Form->postLink(__('Delete Product Type'), array('action' => 'delete', $productType['ProductType']['id']), null, __('Are you sure you want to delete # %s?', $productType['ProductType']['id'])); ?> </li>
      <li><?php echo $this->Html->link(__('New Product Type'), array('action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Products'), array('controller' => 'products', 'action' => 'add')); ?> </li>
    <?php endif ?>
    <li><?php echo $this->Html->link(__('List Product Types'), array('action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
  </ul>
</div>
<div class="related">
  <h3><?php echo __('Related Products'); ?></h3>
  <?php if (!empty($productType['Products'])): ?>
  <table cellpadding = "0" cellspacing = "0">
  <tr>
    <th><?php echo __('Media'); ?></th>
    <th><?php echo __('Name'); ?></th>
    <th><?php echo __('Description'); ?></th>
    <th class="actions"><?php echo __('Actions'); ?></th>
  </tr>
  <?php
    $i = 0;
    foreach ($productType['Products'] as $products): ?>
    <tr>
      <td><?php echo $this->element('Medias/Medias/Preview', array('media'=>$products));?></td>
      <td><?php echo $products['name']; ?></td>
      <td><?php echo $products['description']; ?></td>
      <td class="actions">
        <?php echo $this->Html->link(__('View'), array('controller' => 'products', 'action' => 'view', $products['id'])); ?>
        <?php if($tokens['isAdmin']) : ?>
          <?php echo $this->Html->link(__('Edit'), array('controller' => 'products', 'action' => 'edit', $products['id'])); ?>
          <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'products', 'action' => 'delete', $products['id']), null, __('Are you sure you want to delete # %s?', $products['id'])); ?>
        <?php endif ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php endif; ?>

  <div class="actions">
    <ul>
      <li><?php echo $this->Html->link(__('New Products'), array('controller' => 'products', 'action' => 'add')); ?> </li>
    </ul>
  </div>
</div>
