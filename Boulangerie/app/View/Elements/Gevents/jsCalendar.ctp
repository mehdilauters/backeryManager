<script>
  var eventsSource =
  [
  {
  events : [
  <?php
  foreach ($data['Event'] as $event)
  {
  ?>
           <?php echo $this->element('Gevents/jsObject', array('gevent'=>$event));  ?>
  <?php } ?>
  ],
  //    color: 'blue',   // an option!
  //  textColor: 'black', // an option!
    }
    ]
  </script>