<div class="photos form">
<?php echo $this->Form->create('Photo',array('enctype' => 'multipart/form-data', 'class'=>'form-horizontal')); ?>
  <fieldset>
    <legend><?php echo __('Add Photo'); ?></legend>
  <?php
    echo $this->Form->input('name', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
    echo $this->Form->input('description', array('class'=>'textEditor form-control', 'type'=>'textarea', 'label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  ));
    echo $this->Form->input('upload', array('label'=>'fichier', 'type'=>'file', 'label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
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
