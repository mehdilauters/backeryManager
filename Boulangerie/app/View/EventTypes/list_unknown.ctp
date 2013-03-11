Clicker sur l'evenement à rajouter
<ul>
<?php
foreach( $notKnownEvents as $gcalendar => $data)
{
?>
 <li> <?php echo $data['name'] ?>
 <ul>
 <?php
   foreach( $data['events'] as $gevent)
   {
   ?>
   <li><a href="<?php echo $this->webroot ?>events/add/<?php echo $gcalendar?>/<?php echo $gevent['Gevent']['id'] ?>"><?php echo $gevent['Gevent']['title']?></a></li>
   <?php } ?>
</ul>
   <li>
   <?php }
?>
</ul>
