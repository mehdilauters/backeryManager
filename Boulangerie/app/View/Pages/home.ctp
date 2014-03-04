<script>


		
$(document).ready(function() {

});


</script>
<?php
if($tokens['isAdmin'])
{
	echo $this->element('Results/stats/results', array('results'=>$results, 'config'=>array('interactive'=>false)));
}

 ?>
 
<?php //debug($eventType); 
if(count($eventType['Event']) != 0)
{
  ?>
  <ul id="newsList" >
  <?php
  foreach($eventType['Event'] as $event)
  {
  ?>
  <li>
  <?php
	  // debug($event);
	  $isToday = $this->Dates->isToday($event);
	  if($isToday)
	  {
		  ?>
		  <h3><?php echo $event['Gevent']['title'] ?></h3>
		  <p><?php echo $event['Gevent']['description'] ?></p>
		  <?php
		  // debug($event);
	  }
		  ?>
  </li>
  <?php
  }
  ?>
</ul>
<?php
}


?>
<?php $slideshowInserted = false; ?>
  <ul id="shopPreviewList">
    <?php foreach($shops as $shop) { ?>
    <li class="shop">
     <?php   echo $this->element('Shops/Preview', array('shop'=>$shop)); ?>
    </li>
    <?php
if(!$slideshowInserted)
{ 
  $slideshowInserted = true;
  ?>
   <li>
      <center>
	  	  <?php 
		if($daysProduct)
		{
			$text = 'Produits du Jour';
		}
		else
		{
			$text = 'Nos Produits';
		}
	  ?>
	  <h3><?php echo $text ?></h3>
      <div class="slideshow" >
	<ul class="slides" id="homeSlideshow">
	  <?php foreach($products as $product) {?>
	    <li class="slide"><a href="<?php echo $this->webroot.'products/view/'.$product['Product']['id'] ?>" ><img src="<?php echo $this->webroot.'img/photos/normal/'.$product['Media']['path'] ?>" alt="<?php echo $product['Product']['name'] ?>" width="400" height="300" /></a>
	    <div class="slide-title slate"><a href="<?php echo $this->webroot.'products/view/'.$product['Product']['id'] ?>" ><?php echo $product['Product']['name'] ?></a></div>
	    </li>
	  <?php } ?>
	</ul>
    </div>
    </center>
   </li>
<?php
}

 } ?>
  </ul>