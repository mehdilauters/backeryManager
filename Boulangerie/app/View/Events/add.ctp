<div class="events form">
<?php echo $this->Form->create('Event'); ?>
  <fieldset>
    <legend><?php echo __('Add Event'); ?></legend>
  <?php
    echo $this->Form->input('media_id');
    echo $this->Form->input('product_id');
    if(! isset($geventId))
    {
       echo $this->Form->input('event_type_id');
       echo $this->Form->input('title',array('type'=>'text'));
       echo $this->Form->input('description',array('type'=>'text'));
       echo $this->Form->input('start',array('type'=>'text'));
    }
    else
    {
        echo $this->Form->input('event_type_id',array('type'=>'hidden'));
        echo $this->Form->input('gevent_id',array('type'=>'hidden', 'value'=>$geventId));
    }
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Html->link(__('List Events'), array('action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('New Event Type'), array('controller' => 'eventTypes', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
  </ul>
</div>
