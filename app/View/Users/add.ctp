<div class="users form">
<?php echo $this->Form->create('User', array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('media_id', array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Photo'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('email', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control uniqueUserWatch',
					  'required'=>true,
					  ));
		echo $this->Form->input('password', array('value'=>'','label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Mot de passe'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control',
                                          'required'=>true,
					  ));
		echo $this->Form->input('name', array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Nom'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control uniqueUserWatch'
					  ));
		echo $this->Form->input('address', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Adresse'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('phone', array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Téléphone'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('rib_on_orders', array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Rib sur les factures'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
    echo $this->Form->input('enabled', array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Enabled'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('discount', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Pourcentage de réduction'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
                echo $this->Form->input('User.regular', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Client régulier'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          'checked' => 'checked',
                                          ));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>
