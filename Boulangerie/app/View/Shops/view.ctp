  <?php   echo $this->element('Gevents/jsCalendar', array('data'=>$shop['EventType'])); ?>
  <script>
$(document).ready(function() {
    // page is now ready, initialize the calendar...
    $('#calendar').fullCalendar({
    minTime: 6,
    maxTime: 21,
    eventSources : eventsSource,
    header: {
        left:   'title',
        center: '',
        right:  'today prev,next'
    },
    defaultView: 'agendaWeek',
    firstHour: 6,
    weekMode: 'variable',
    aspectRatio: 1,
monthNames:['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
monthNamesShort:['janv.','févr.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'],
dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
titleFormat: {
    month: 'MMMM yyyy',
    week: "d[/MM][/yy]{ - d MMMM yyyy}",
day: 'dddd d MMMM yyyy'
},
columnFormat: {
    month: 'ddd',
week: 'ddd d',
day: ''
},
axisFormat: 'H:mm', 
timeFormat: {
    '': 'H:mm', 
agenda: 'H:mm{ - H:mm}'
},
firstDay:1,
buttonText: {
    today: 'aujourd\'hui',
    day: 'jour',
    week:'semaine',
    month:'mois'
}, 
    //events: "/boulangerie/events/gcalendarFeed",
  /*  eventRender: function(event, element) {
          element.qtip({
        content: event.details,
        position: { 
          target: 'mouse',
          adjust: {
            x: 10,
            y: -5
          }
        },
        style: {
          name: 'light',
          tip: 'leftTop'
        }
          }); 
      },*/
    })

});
  </script>

<div class="shops view">

<?php   
if($tokens['isAdmin'])
{
	echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$resultsEntries, 'config'=>array('interactive'=>false))); 
}
	?>
<div class="slate slateShopDescription slateShopDescriptionView" >      
      <?php echo h($shop['Shop']['description']); ?>
    </div>
<div>
  <img class="imageShopView" src="<?php echo $this->webroot.'img/photos/normal/'.$shop['Media']['path'] ?>" alt="<?php echo $shop['Media']['name'] ?>" />
  <div class="slate slateShop slateShopView" >
      <div>
	<?php echo h($shop['Shop']['phone']); ?>
      </div>
    <div>
	<?php echo h($shop['Shop']['address']); ?>
    </div>
  </div>
</div>
<div class="clear" ></div>
<div class="hideJs" >
    <a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" >
        <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($shop['Shop']['address']); ?>&zoom=14&size=400x400&sensor=false&maptype=roadmap&markers=color:blue%7C<?php echo urlencode($shop['Shop']['address']); ?>" alt="location"/>
    </a>
</div>
  <hr/>
  <?php
if($isCalendarAvailable)
{
  if($isOpened)
  {?>
    <span class="shopOpened">La boulangerie est ouverte</span>
  <?php }
  else
  {?>
    <span class="shopClosed">La boulangerie est fermée</span>
  <?php } 
}
?>
  <hr/>
<div class="slate">
<?php
$days = $this->Dates->getTimeTable($shop['EventType']);
//debug($days);
?>
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
</div>
<!--     <div id="calendar" class="slate" >Calendar loading</div> -->
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
  <?php if($tokens['isAdmin']) : ?>
    <li><?php echo $this->Html->link(__('Edit Shop'), array('action' => 'edit', $shop['Shop']['id'])); ?> </li>
    <li><?php echo $this->Form->postLink(__('Delete Shop'), array('action' => 'delete', $shop['Shop']['id']), null, __('Are you sure you want to delete # %s?', $shop['Shop']['id'])); ?> </li>
    <li><?php echo $this->Html->link(__('New Shop'), array('action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
  <?php endif ?>
    <li><?php echo $this->Html->link(__('Nos magasins'), '/'); ?> </li>
    <li><?php echo $this->Html->link(__('Nos produits'), array('controller' => 'productTypes', 'action' => 'index')); ?> </li>
  </ul>
</div>
