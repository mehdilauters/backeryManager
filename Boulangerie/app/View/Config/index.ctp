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
	<li>sales/stat, results/stat => recherche par jour (lundi, mardi...) et date (01/01, 02/01...)</li>
	<li>login</li>
	<li> Product & ProductType => boolean Afficher (client)</li>
	<li> connexion (cookie)</li>
	<li></li>
</ul>