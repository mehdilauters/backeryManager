<div id="shopPreview_<?php echo $shop['Shop']['id'] ?>" class="shopPreview">
<div><h3>
  <a href="<?php echo $this->webroot.'shops/view/'.$shop['Shop']['id']; ?>" alt="<?php echo($shop['Shop']['name']); ?>" ><?php echo($shop['Shop']['name']); ?></a></h3>
</div>
 <?php echo $this->element('Medias/Medias/Preview', array('media'=>$shop, 'config'=>array(
        'name' => false,
        'description' => false
        ))) ?>
<a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" >
<img class="map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($shop['Shop']['address']); ?>&zoom=15&size=200x132&sensor=false&maptype=roadmap&markers=color:blue%7C<?php echo urlencode($shop['Shop']['address']); ?>" alt="location"/>
</a>
<div class="clear" ></div>
<div class="slate slateShopDescription" ><p><?php echo($shop['Shop']['description']); ?><p></div>
 <div class="slate slateShop" >
  <div><?php echo $shop['Shop']['address']; ?></div>
  <div><?php 

echo $shop['Shop']['phone']; ?></div>
    <?php
    if( $isCalendarAvailable )
    {
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
    }
    ?>
 </div>
<div class="clear" ></div>
<?php //debug($shop); ?>
</div>
