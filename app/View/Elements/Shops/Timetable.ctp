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
</div>