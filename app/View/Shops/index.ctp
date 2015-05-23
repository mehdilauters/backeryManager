<div class="shops index">
  <h2><?php echo __('Shops'); ?></h2>
  <table cellpadding="0" cellspacing="0">
  <tr>
      <th><?php echo $this->Paginator->sort('media_id'); ?></th>
      <!-- <th><?php echo $this->Paginator->sort('event_type_id'); ?></th> !-->
      <th><?php echo $this->Paginator->sort('name'); ?></th>
      <th><?php echo $this->Paginator->sort('phone'); ?></th>
      <th><?php echo $this->Paginator->sort('address'); ?></th>
      <th><?php echo $this->Paginator->sort('description'); ?></th>
      <th><?php echo $this->Paginator->sort('enable'); ?></th>
      <th class="actions"><?php echo __('Actions'); ?></th>
  </tr>
  <?php foreach ($shops as $shop): ?>
  <tr>
    <td>
      <?php echo $this->element('Medias/Medias/Preview', array('media'=>$shop, 'config'=>array('name'=>false, 'description'=>false))) ?>
    </td>
    <!-- <td><?php echo h($shop['Shop']['event_type_id']); ?>&nbsp;</td> !-->
    <td><?php echo h($shop['Shop']['name']); ?>&nbsp;</td>
    <td><?php echo h($shop['Shop']['phone']); ?>&nbsp;</td>
    <td><?php echo h($shop['Shop']['address']); ?>&nbsp;</td>
    <td><?php echo h($shop['Shop']['description']); ?>&nbsp;</td>
    <td><?php echo h($shop['Shop']['enabled']); ?>&nbsp;</td>
    <td class="actions">
      <?php echo $this->Html->link(__('View'), array('action' => 'view', $shop['Shop']['id'])); ?>
      <?php if($tokens['isAdmin']) : ?>
        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $shop['Shop']['id'])); ?>
        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $shop['Shop']['id']), null, __('Are you sure you want to delete # %s?', $shop['Shop']['id'])); ?>
      <?php endif ?>
    </td>
  </tr>
<?php endforeach; ?>
  </table>
  <p>
  <?php
  echo $this->Paginator->counter(array(
  'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
  ));
  ?>  </p>
  <div class="paging">
  <?php
    echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
    echo $this->Paginator->numbers(array('separator' => ''));
    echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
  ?>
  </div>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <?php if($tokens['isAdmin']) : ?>
      <li><?php echo $this->Html->link(__('New Shop'), array('action' => 'add')); ?></li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
    <?php endif ?>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
  </ul>
</div>
