<div class="photos form">
<?php echo $this->Form->create('Photo',array('enctype' => 'multipart/form-data')); ?>
  <fieldset>
    <legend><?php echo __('Add Photo'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('description', array('class'=>'textEditor', 'type'=>'textarea'));
    echo $this->Form->input('upload', array('label'=>'fichier', 'type'=>'file'));
  ?>
  
  
  
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Html->link(__('List Photos'), array('action' => 'index')); ?></li>
  </ul>
</div>
