<div class="">
<?php
$days = $this->Dates->getTimeTable($eventType, $eventTypeClosed);
//debug($days);
if(count($days) != 0):
?>
<center>
<table class="timetable" >
  <tr>
    <th>Date</th>
    <th>Matin</th>
    <th>Soir</th>
  </tr>
<?php
$i = 0;
foreach ($days as $dayId => $day)
{ 
  $dayClass = '';
  $morningClass = '';
  $afternoonClass = '';

  if(date('z') == date('z',$dayId))
  {
    $dayClass = 'today';
    if( date('G') < Configure::read('Settings.midday'))
    {
        $morningClass = 'now';
    }
    else
    {
	$afternoonClass = 'now';
    }
  }

?>
 <tr class="<?php echo $dayClass ?>" >
    <td class="day" ><span class="dayName" ><?php echo $this->Dates->getJourFr(date('N',$dayId )) ?></span> <span class="dayNumber" ><?php echo date('j',$dayId); ?></span></td>

	<td class="<?php echo $morningClass ?>" ><?php if(isset($day['morning'])) { echo date('G:i',strtotime($day['morning']['start'])).' - '.date('G:i',strtotime($day['morning']['end'])); } else { echo '-'; } ?></td>
	<td class="<?php echo $afternoonClass ?>" ><?php if(isset($day['afternoon'])) { echo date('G:i',strtotime($day['afternoon']['start'])).' - '.date('G:i',strtotime($day['afternoon']['end'])); } else { echo '-'; } ?></td>
  </tr>
<?php
  $i++;
}
?>
</table>
</center>
<?php endif; ?>
<?php 
if( count($eventTypeClosed['Event']) != 0): ?>
<div class="alert alert-danger" >
  <ul>
    <?php  foreach($eventTypeClosed['Event'] as $event): 
	$dateStart = new DateTime($event['start']);
	$dateEnd = new DateTime($event['end']);
	?>
     <li>
      <h4>Fermeture : <?php echo $event['title'] ?></h4>
      <div>
	du <b><?php echo $dateStart->format('d/m/Y G:i'); ?></b> au <b><?php echo $dateEnd->format('d/m/Y G:i'); ?></b>
      <p>
	<?php echo $event['details'] ?>
      </p>
<?php if($tokens['isAdmin']) : ?>
  <?php echo $this->Html->link(__('Delete', true), array('plugin' => 'full_calendar', 'controller' => 'events', 'action' => 'delete', $event['id']), null, sprintf(__('Are you sure you want to delete this %s event?', true), $event['title'])); ?>
<?php endif; ?>
      </div>
    </li>
    </ul>
  <?php endforeach; ?>
</div>
<?php endif; ?>
</div>