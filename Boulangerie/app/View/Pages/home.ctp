<?php $slideshowInserted = false; ?>
  <ul id="shopPreviewList">
    <?php foreach($shops as $shop) { ?>
    <li class="shop">
     <?php   echo $this->element('Shops/Preview', array('shop'=>$shop, 'isCalendarAvailable' => $isCalendarAvailable)); ?>
      <hr/>
    </li>
    <?php
if(!$slideshowInserted)
{ 
  $slideshowInserted = true;
  ?>
   <li>
      <center>
      <div class="slideshow" >
	<ul class="slides" id="homeSlideshow">
	  <?php foreach($photos as $photo) {?>

	    <li class="slide"><img src="<?php echo $this->webroot.'img/photos/normal/'.$photo['Photo']['path'] ?>" alt="<?php echo $photo['Photo']['name'] ?>" width="400" height="300" />
	    <div class="slide-title slate"><?php echo $photo['Photo']['name'] ?></div>
	    </li>
	  <?php } ?>
	</ul>
    </div>
    </center>
      <hr/>	
   </li>
<?php
}

 } ?>
  </ul>