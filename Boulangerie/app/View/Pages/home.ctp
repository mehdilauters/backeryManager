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
   <li class="slideshow">
      <center>
      <ul class="slides" id="homeSlideshow">
	<?php foreach($photos as $photo) {?>

	  <li class="slide"><img src="<?php echo $this->webroot.'img/photos/normal/'.$photo['Photo']['path'] ?>" alt="<?php echo $photo['Photo']['name'] ?>" width="400" height="300" /></li>
	<?php } ?>
      </ul>
     </center>
   <li>
<?php
}

 } ?>
  </ul>