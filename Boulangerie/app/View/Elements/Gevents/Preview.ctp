<div>
<span><?php echo $gevent['Gevent']['title'] ?></span>
<ul>
<?php 
foreach ($gevent['Gevent']['GeventDate'] as $date) {
?>
	<li><?php echo $date['start'] ?>=><?php echo $date['end'] ?></li>
<?php 
}

?>
</ul>
</div>