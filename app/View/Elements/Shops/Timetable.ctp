<div class="">
<?php
$days = $this->Dates->getTimeTable($eventType);
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
{ ?>
 <tr>
    <td class="day" ><span class="dayName" ><?php echo $this->Dates->getJourFr(date('N',$dayId )) ?></span> <span class="dayNumber" ><?php echo date('j',$dayId); ?></span></td>

	<td><?php if(isset($day['morning'])) { echo date('G:i',strtotime($day['morning']['start'])).' - '.date('G:i',strtotime($day['morning']['end'])); } else { echo '-'; } ?></td>
	<td><?php if(isset($day['afternoon'])) { echo date('G:i',strtotime($day['afternoon']['start'])).' - '.date('G:i',strtotime($day['afternoon']['end'])); } else { echo '-'; } ?></td>
  </tr>
<?php
  $i++;
}
?>
</table>
</center>
<?php endif; ?>
</div>