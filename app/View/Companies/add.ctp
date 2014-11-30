<div class="companies form">
<?php echo $this->Form->create('Company', array('class'=>'form-horizontal')); ?>

	<?php
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
		echo $this->Form->input('domain_name', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('email', array('label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('legals_mentions', array('class'=>'textEditor form-control', 'label'=>array('class'=>'col-sm-3 control-label'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  ));
                echo $this->Form->input('imap_server', array('class'=>'textEditor form-control', 'label'=>array('class'=>'col-sm-3 control-label'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          ));
                echo $this->Form->input('imap_username', array('class'=>'textEditor form-control', 'label'=>array('class'=>'col-sm-3 control-label'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          ));
                echo $this->Form->input('imap_password', array('class'=>'textEditor form-control', 'label'=>array('class'=>'col-sm-3 control-label'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          ));
	?>

<script>

$(document).ready(function() {
    $('#CompanyDomainName').keypress(function(event) {

      if(event.key.match("[A-Za-z0-9\-]"))
      {
	return true;
      }
      return false;
    });
});



</script>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Companies'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>
