<?php
/*
 * View/Events/add.ctp
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

?>
<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
 		<legend><?php __('Add Event'); ?></legend>
	<?php
		echo $this->Form->input('event_type_id');
		echo $this->Form->input('title');
		echo $this->Form->input('details');
		echo $this->Form->input('recursive', array('options'=>array(''=>'','day'=>'jour','week'=>'semaine','month'=>'mois', 'year'=>'année')));
		echo $this->Form->input('recursive_start', array('type'=>'text', 'class'=>'datepicker'));
		echo $this->Form->input('recursive_end', array('type'=>'text', 'class'=>'datepicker'));
		echo $this->Form->input('start', array('type'=>'text', 'class'=>'datetimepicker'));
		echo $this->Form->input('end', array('type'=>'text', 'class'=>'datetimepicker'));
		echo $this->Form->input('all_day');
		echo $this->Form->input('status', array('options' => array(
					'Scheduled' => 'Scheduled','Confirmed' => 'Confirmed','In Progress' => 'In Progress',
					'Rescheduled' => 'Rescheduled','Completed' => 'Completed'
				)
			)
		);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Manage Events', true), array('plugin' => 'full_calendar', 'action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Manage Event Types', true), array('plugin' => 'full_calendar', 'controller' => 'event_types', 'action' => 'index')); ?> </li>
		<li><li><?php echo $this->Html->link(__('View Calendar', true), array('plugin' => 'full_calendar', 'controller' => 'full_calendar')); ?></li>
	</ul>
</div>
<script type="text/javascript">

function recursiveChange()
{
    if( $(this).val() == '' )
    {
      $('#EventRecursiveStart').prop('disabled', true);
      $('#EventRecursiveEnd').prop('disabled', true);
    }
    else
    {
      $('#EventRecursiveStart').prop('disabled', false);
      $('#EventRecursiveEnd').prop('disabled', false);
    }
}

  $(document).ready(function(){
    $("#EventRecursive").change(recursiveChange);
    recursiveChange();
  });

</script>