<ul>
<?php
foreach($actions as $action => $name)
{ ?>
<li><a href="<?php echo $this->webroot.'config/'.$action ?>" /> <?php echo $name ?></a></li>
<?php
}
?>
<li><a href="https://www.google.com/calendar/render?tab=mc" >Google Calendar</a></li>
</ul>
<h3>TODO</h3>
<ul>
  <li>sales/add, results/add => recherche par jour (lundi, mardi...) et date (01/01, 02/01...)</li>
  <li>sales/stat => check courbes</li>
  <li>sales, results total/semaine</li>
</ul>