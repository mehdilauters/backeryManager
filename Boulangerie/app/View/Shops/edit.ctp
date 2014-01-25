<div class="shops form">
<?php echo $this->Form->create('Shop'); ?>
  <fieldset>
    <legend><?php echo __('Edit Shop'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('media_id');
    echo $this->Form->input('event_type_id');
    echo $this->Form->input('name');
    echo $this->Form->input('phone');
    echo $this->Form->input('description');
    echo $this->Form->input('address');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Shop.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Shop.id'))); ?></li>
    <li><?php echo $this->Html->link(__('List Shops'), array('action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
  </ul>
</div>
<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showCalendars=0&amp;mode=WEEK&amp;height=600&amp;wkst=2&amp;bgcolor=%23FFFFFF&amp;src=<?php echo $this->request->data['EventType']['calendar_id'] ?>&amp;color=%232952A3&amp;ctz=Europe%2FParis" style=" border-width:0 " width="800" height="600" frameborder="0" scrolling="no"></iframe>