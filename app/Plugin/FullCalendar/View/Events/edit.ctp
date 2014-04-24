<?php
/*
 * View/Events/edit.ctp
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
 		<legend><?php __('Edit Event'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('event_type_id');
		echo $this->Form->input('title');
		echo $this->Form->input('details', array('class'=>'textEditor'));
		echo $this->Form->input('recursive', array('options'=>array(''=>'','day'=>'jour','week'=>'semaine','month'=>'mois', 'year'=>'année')));
		
		$date = new DateTime($this->request->data['Event']['recursive_start']);
		if($date->format('H:i') == '00:00')
		{
			$date = $date->format('d/m/Y');
		}
		else
		{
			$date = $date->format('d/m/Y H:i');
		}
		
		echo $this->Form->input('recursive_start', array('type'=>'text', 'class'=>'datepicker', 'value'=>$date ));

		$date = new DateTime($this->request->data['Event']['recursive_end']);
		if($date->format('H:i') == '00:00')
		{
			$date = $date->format('d/m/Y');
		}
		else
		{
			$date = $date->format('d/m/Y H:i');
		}

		echo $this->Form->input('recursive_end', array('type'=>'text', 'class'=>'datepicker', 'value'=>$date));




		$date = new DateTime($this->request->data['Event']['start']);
		if($date->format('H:i') == '00:00')
		{
			$date = $date->format('d/m/Y');
		}
		else
		{
			$date = $date->format('d/m/Y H:i');
		}
		echo $this->Form->input('start', array('type'=>'text', 'class'=>'datetimepicker', 'value'=>$date));



		$date = new DateTime($this->request->data['Event']['end']);
		if($date->format('H:i') == '00:00')
		{
			$date = $date->format('d/m/Y');
		}
		else
		{
			$date = $date->format('d/m/Y H:i');
		}
		echo $this->Form->input('end', array('type'=>'text', 'class'=>'datetimepicker', 'value'=>$date));
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
		<li><?php echo $this->Html->link(__('View Event', true), array('plugin' => 'full_calendar', 'action' => 'view', $this->Form->value('Event.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Manage Events', true), array('plugin' => 'full_calendar', 'action' => 'index'));?></li>
		<li><li><?php echo $this->Html->link(__('View Calendar', true), array('plugin' => 'full_calendar', 'controller' => 'full_calendar')); ?></li>
	</ul>
</div>
