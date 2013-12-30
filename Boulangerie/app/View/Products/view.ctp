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
?>
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
  <?php   echo $this->element('Gevents/jsCalendar', array('data'=>$product)); ?>
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
    defaultView: 'basicWeek',
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
<div class="products view">
<h2><?php  echo __('Product'); ?></h2>
  <dl>
    <dt><?php echo __('Id'); ?></dt>
    <dd>
      <?php echo h($product['Product']['id']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Product Types'); ?></dt>
    <dd>
      <?php echo $this->Html->link($product['ProductType']['name'], array('controller' => 'product_types', 'action' => 'view', $product['ProductType']['id'])); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Media'); ?></dt>
    <dd>
      <?php echo $this->element('Medias/Medias/Preview', array('media'=>$product)) ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Name'); ?></dt>
    <dd>
      <?php echo h($product['Product']['name']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Description'); ?></dt>
    <dd>
      <?php echo h($product['Product']['description']); ?>
      &nbsp;
    </dd>
    <dt><?php echo __('Created'); ?></dt>
    <dd>
      <?php echo h($product['Product']['created']); ?>
      &nbsp;
    </dd>
  </dl>
<?php if( $isCalendarAvailable ) { ?>
  <?php if($produced): ?>
  <span class="productAvailable"> Venez en acheter aujourd'hui!! </span>
  <?php else: ?>
  <span class="productNotAvailable"> Nous n'en vendons pas aujourd'hui. Veuillez repasser XXXX </span>
  <?php endif; ?>
  <div id="calendar" />
<?php } ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <?php if($tokens['isAdmin']) : ?>
      <li><?php echo $this->Html->link(__('Edit Product'), array('action' => 'edit', $product['Product']['id'])); ?> </li>
      <li><?php echo $this->Form->postLink(__('Delete Product'), array('action' => 'delete', $product['Product']['id']), null, __('Are you sure you want to delete # %s?', $product['Product']['id'])); ?> </li>
      <li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add', 'idProduct'=>$product['Product']['id'])); ?> </li>
    <?php endif ?>
    <li><?php echo $this->Html->link(__('List Products'), array('action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('List Events'), array('controller' => 'events', 'action' => 'index')); ?> </li>
  </ul>
</div>
<div class="related">
  <h3><?php echo __('Related Events'); ?></h3>
  <?php if (!empty($product['Event'])): ?>
  <table cellpadding = "0" cellspacing = "0">
  <tr>
    <th><?php echo __('Id'); ?></th>
    <th><?php echo __('Gevent Id'); ?></th>
    <th><?php echo __('Media Id'); ?></th>
    <th><?php echo __('Product Id'); ?></th>
    <th class="actions"><?php echo __('Actions'); ?></th>
  </tr>
  <?php
    $i = 0;
    foreach ($product['Event'] as $event): ?>
    <tr>
      <td><?php echo $event['id']; ?></td>
      <td>
         <table>
          <tr>
            <th>Name</th>
            <th>description</th>
            <th>Dates</th>
          </tr>
          <tr>
            <td><?php echo $event['Gevent']['title'] ?></td>
            <td><?php echo $event['Gevent']['description'] ?></td>
            <td>
              <ul>  
                <?php foreach( $event['Gevent']['GeventDate'] as $geventDate):  ?>
                   <li><?php echo $geventDate['start'];?> ==> <?php echo $geventDate['end'];?> </li>
                <?php endforeach; ?>
              </ul>
            </td>
          </tr>
         </table>
      </td>
      <td><?php echo $event['media_id']; ?></td>
      <td><?php echo $event['product_id']; ?></td>
      <td class="actions">
        <?php echo $this->Html->link(__('View'), array('controller' => 'events', 'action' => 'view', $event['id'])); ?>
        <?php if($tokens['isAdmin']) : ?>
          <?php echo $this->Html->link(__('Edit'), array('controller' => 'events', 'action' => 'edit', $event['id'])); ?>
          <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'events', 'action' => 'delete', $event['id']), null, __('Are you sure you want to delete # %s?', $event['id'])); ?>
        <?php endif ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php endif; ?>

  <div class="actions">
    <ul>
      <li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
    </ul>
  </div>
</div>
