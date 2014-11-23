<div class="companies form">
<?php echo $this->Form->create('Company', array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Edit Company'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('rib',array('options'=>$media, 
					'label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('name', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('address', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('phone', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('capital', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('siret', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('title', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
                if($tokens['isAdmin'])
                {
                  echo $this->Form->input('domain_name', array('label'=>array('class'=>'col-sm-3 control-label'),
                                            'between' => '<div class="col-sm-5" >',
                                            'after' => '</div>',
                                          'div'=>'form-group',
                                            'class'=>'form-control'
                                            ));
                }
                $result = preg_match('/^(\w+)@(\w+)\..*$/',$this->request->data['Company']['email'], $matches);
                // internal email
                if($matches[2] != $this->request->data['Company']['domain_name']  || $tokens['isAdmin'])
                {
                  echo $this->Form->input('email', array('label'=>array('class'=>'col-sm-3 control-label'),
                                            'between' => '<div class="col-sm-5" >',
                                            'after' => '</div>',
                                          'div'=>'form-group',
                                            'class'=>'form-control'
                                            ));
                }
		echo $this->Form->input('order_legals_mentions', array('class'=>'textEditor form-control', 'label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  ));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Company.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Company.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Companies'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>