<div class="shops form">
<?php echo $this->Form->create('Shop'); ?>
  <fieldset>
    <legend><?php echo __('Edit Shop'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('media_id', array('label'=>'Photo', 'class'=>'formPhotoPreview')); ?>
    <img src="" id="ShopMediaId_preview" />
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('phone');
    echo $this->Form->input('description', array('class'=>'textEditor'));
    echo $this->Form->input('address');
    echo $this->Form->input('enabled');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Shop.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Shop.id'))); ?></li>
    <li><?php echo $this->Html->link(__('List Shops'), '/'); ?></li>
    <li><?php echo $this->Html->link(__('List Photos'), array('controller' => 'photos', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Photo'), array('controller' => 'photos', 'action' => 'add')); ?> </li>
  </ul>
</div>