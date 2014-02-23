<div id="shopPreview_<?php echo $shop['Shop']['id'] ?>" class="shopPreview">

<div class="title slate" ><h3>
  <a href="<?php echo $this->webroot.'magasins/details/'.$shop['Shop']['id']; ?>" alt="<?php echo($shop['Shop']['name']); ?>" ><?php echo($shop['Shop']['name']); ?></a></h3>
</div>
<a href="<?php echo $this->webroot.'magasins/details/'.$shop['Shop']['id']; ?>" alt="<?php echo($shop['Shop']['name']); ?>" >
  <img class="imageShopView" src="<?php echo $this->webroot.'img/photos/normal/'.$shop['Media']['path'] ?>" alt="<?php echo $shop['Media']['name'] ?>" />
</a>
<div class="clear" ></div>
<div class="slate slateShopDescription" id="shopDescription_<?php echo $shop['Shop']['id'] ?>" >
<div class="slateShop" >
  <div><?php echo $shop['Shop']['address']; ?>
<div>
<a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" >
<!--<img class="map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($shop['Shop']['address']); ?>&zoom=15&size=300x300&sensor=false&maptype=roadmap&markers=color:blue%7C<?php echo urlencode($shop['Shop']['address']); ?>" alt="location"/>-->
</a>
</div>
    </div>
  <div class="phone" ><?php 

echo $shop['Shop']['phone']; ?></div>
<div class="isOpened">
    <?php
      $nextOpened = 0;
      $nextClose = 0;
      $days = $this->Dates->getTimeTable($shop['EventType']);
      foreach($days as $dayId => $timeTable)
      {
	if($dayId >= mktime(0, 0, 0, date("m")  , date("d"), date("Y")))
	{
	    krsort($timeTable); // process morning before afternoon
	    foreach($timeTable as $key => $time)
	    {
	      if($nextOpened == 0 && strtotime($time['start']) > time() )
	      {
		if(strtotime($time['start']) > $nextOpened)
		{
		  $nextOpened = strtotime($time['start']);
		}
	      }
	      if($nextClose == 0 && strtotime($time['end']) > time() )
	      {
		if(strtotime($time['end']) > $nextClose)
		{
		  $nextClose = strtotime($time['end']);
		}
	      }
	    }
	  $day = $this->Dates->getJourFr(date('N',$dayId ));
	}
      }
      if($shop['Shop']['isOpened'])
      {?>
	<span class="shopOpened">La boulangerie est ouverte jusqu'à <?php echo date('G:i',$nextClose) ?></span>
      <?php }
      else
      {?>
	<span class="shopClosed">La boulangerie est fermée jusqu'à <?php echo date('G:i d/m/Y',$nextOpened) ?></span>
      <?php } 
    ?>
  </div>
 </div>
</div>
 
<div class="clear" ></div>
<?php //debug($shop); ?>
</div>
