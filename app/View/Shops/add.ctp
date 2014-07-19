<div class="shops form">
<?php echo $this->Form->create('Shop'); ?>
  <fieldset>
    <legend><?php echo __('Add Shop'); ?></legend>
  <?php
      echo $this->Form->input('media_id', array('label'=>'Photo', 'class'=>'formPhotoPreview')); ?>
      <img src="" id="ShopMediaId_preview" />
<?php
    echo $this->Form->input('name');
    echo $this->Form->input('phone');
    echo $this->Form->input('description', array('class'=>'textEditor'));
    echo $this->Form->input('address');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Html->link(__('List Shops'), array('action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
  </ul>
</div>
