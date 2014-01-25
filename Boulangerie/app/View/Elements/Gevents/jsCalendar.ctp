<script>
  var eventsSource =
  [
  {
  events : [
  <?php
  if(isset($data['Event']))
  {
    foreach ($data['Event'] as $event)
    {
    ?>
	    <?php echo $this->element('Gevents/jsObject', array('gevent'=>$event));  ?>
    <?php } 
    } ?>
  ],
  //    color: 'blue',   // an option!
  //  textColor: 'black', // an option!
    }
    ]
  </script>