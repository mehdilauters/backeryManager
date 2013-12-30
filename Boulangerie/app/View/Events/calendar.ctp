<?php
echo $this->Html->script(
    array(   'jquery-1.9.1.min',
        'jquery-ui-1.10.1.custom.min',
         'fullcalendar.min',
         'jquery.qtip-1.0.0-rc3.min',
        ),
     array('inline' => 'false')
  );
echo $this->Html->css(
          'fullcalendar',
           null,
           array('inline' => false)
  );
  $gevents['Event'] = $events;
?>
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
  <?php   echo $this->element('Gevents/jsCalendar', array('data'=>$gevents)); ?>
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
    },
    defaultView: 'agendaWeek',
    firstHour: 6,
    weekMode: 'variable',
    aspectRatio: 2,
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


<div class="events index">
  <h2><?php echo __('Events'); ?></h2>
  <p>
  </p>
</div>

<div id="calendar" > </div>
