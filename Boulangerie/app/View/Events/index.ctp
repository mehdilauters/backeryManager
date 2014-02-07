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
        right:  'today agendaDay,agendaWeek,month prev,next'
    },
    defaultView: 'agendaWeek',
    firstHour: 6,
    weekMode: 'variable',
    aspectRatio: 2,
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
  <a href="https://www.google.com/calendar/render" target="_blank" >Calendrier</a>
  <table cellpadding="0" cellspacing="0">
  <tr>
      <th>id</th>
      <th>media_id</th>
      <th>product_id</th>
      <th>gevent_id</th>
      <th>Title</th>
      <th class="actions"><?php echo __('Actions'); ?></th>
  </tr>
  <?php foreach ($events as $event): ?>
  <tr>
    <td><?php echo h($event['Event']['id']); ?>&nbsp;</td>
    <td><?php echo $this->element('Medias/Medias/Preview', array('media'=>$event));?></td>
    <td>
      <?php echo $this->Html->link($event['Product']['name'], array('controller' => 'products', 'action' => 'view', $event['Product']['id'])); ?>
    </td>
    <td><?php echo $event['Event']['gevent_id']?></td>
    <td><?php echo $event['Gevent']['title']?></td>
    <td class="actions">
      <?php echo $this->Html->link(__('View'), array('action' => 'view', $event['Event']['id'])); ?>
      <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $event['Event']['id'])); ?>
      <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $event['Event']['id']), null, __('Are you sure you want to delete # %s?', $event['Event']['id'])); ?>
    </td>
  </tr>
<?php endforeach; ?>
  </table>
  <p>
  </p>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('New Event'), array('action' => 'add')); ?></li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
  </ul>
</div>
<div id="calendar" > </div>
