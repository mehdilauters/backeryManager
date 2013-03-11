<div class="eventTypes view">
<h2><?php  echo __('Event Type'); ?></h2>
  <dl>
    <dt><?php echo __('Id'); ?></dt>
    <dd>
      <?php echo h($eventType['EventType']['id']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Name'); ?></dt>
    <dd>
      <?php echo h($eventType['EventType']['name']); ?>
      &nbsp;
    </dd>
  </dl>
</div>
      <?php echo h($eventType['EventType']['calendar_id']); ?>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('Edit Event Type'), array('action' => 'edit', $eventType['EventType']['id'])); ?> </li>
    <li><?php echo $this->Form->postLink(__('Delete Event Type'), array('action' => 'delete', $eventType['EventType']['id']), null, __('Are you sure you want to delete # %s?', $eventType['EventType']['id'])); ?> </li>
    <li><?php echo $this->Html->link(__('List Event Types'), array('action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Event Type'), array('action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Events'), array('controller' => 'events', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
  </ul>
</div>
<div class="related">
  <h3><?php echo __('Related Events'); ?></h3>
  <?php if (!empty($eventType['Event'])): ?>
  <table cellpadding = "0" cellspacing = "0">
  <tr>
    <th><?php echo __('Id'); ?></th>
    <th><?php echo __('Event Type Id'); ?></th>
    <th><?php echo __('Gevent Id'); ?></th>
    <th><?php echo __('Media Id'); ?></th>
    <th><?php echo __('Product Id'); ?></th>
    <th class="actions"><?php echo __('Actions'); ?></th>
  </tr>
  <?php
    $i = 0;
    foreach ($eventType['Event'] as $event): ?>
    <tr>
      <td><?php echo $event['id']; ?></td>
      <td><?php echo $event['event_type_id']; ?></td>
      <td><?php echo $event['gevent_id']; ?></td>
      <td><?php echo $event['media_id']; ?></td>
      <td><?php echo $event['product_id']; ?></td>
      <td class="actions">
        <?php echo $this->Html->link(__('View'), array('controller' => 'events', 'action' => 'view', $event['id'])); ?>
        <?php echo $this->Html->link(__('Edit'), array('controller' => 'events', 'action' => 'edit', $event['id'])); ?>
        <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'events', 'action' => 'delete', $event['id']), null, __('Are you sure you want to delete # %s?', $event['id'])); ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php endif; ?>

  <div class="actions">
    <ul>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
    </ul>
  </div>
</div>
