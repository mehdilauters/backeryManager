<div id="shopPreview_<?php echo $shop['Shop']['id'] ?>" class="shopPreview">

<div class="title slate" ><h3>
  <a href="<?php echo $this->webroot.'magasins/details/'.$shop['Shop']['id'].$this->MyHtml->getLinkTitle($shop['Shop']['name']); ?>" alt="<?php echo($shop['Shop']['name']); ?>" ><?php echo($shop['Shop']['name']); ?></a></h3>
<div class="isOpened">
    <?php
      $nextDates = $this->Dates->getNextOpenClose($shop['EventType']);
      if($shop['Shop']['isOpened'])
      {?>
	<span class="shopOpened label label-success">ouvert jusqu'à <?php echo date('G:i',$nextDates['nextClose']) ?></span>
      <?php }
      else
      {?>
	<span class="shopClosed label label-danger">fermé <?php if($nextDates['nextOpened'] != 0){ ?> jusqu'au <?php echo date('d/m/Y',$nextDates['nextOpened']).' à '.date('G:i',$nextDates['nextOpened']); }?></span>
      <?php } 
    ?>
</div>
</div>
<a href="<?php echo $this->webroot.'magasins/details/'.$shop['Shop']['id'].$this->MyHtml->getLinkTitle($shop['Shop']['name']); ?>" alt="<?php echo($shop['Shop']['name']); ?>" >
  <img class="imageShopView" src="<?php echo $this->webroot.'photos/download/'.$shop['Media']['id'].'/0'.$this->MyHtml->getLinkTitle($shop['Media']['name']); ?>" alt="<?php echo $shop['Media']['name'] ?>" />
</a>
<div class="clear" ></div>
<div class="slate slateShopDescription" id="shopDescription_<?php echo $shop['Shop']['id'] ?>" >
<div class="slateShop" >
  <div>
<a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" target="_blank" ><?php echo $shop['Shop']['address']; ?></a>
<div>
<a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" >
<!--<img class="map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($shop['Shop']['address']); ?>&zoom=15&size=300x300&sensor=false&maptype=roadmap&markers=color:blue%7C<?php echo urlencode($shop['Shop']['address']); ?>" alt="location"/>-->
</a>
</div>
    </div>
  <div class="phone" ><?php 

echo $this->MyHtml->getPhoneNumberText($shop['Shop']['phone']); ?></div>

    <?php echo $this->element('Shops/Timetable', array('eventType'=>$shop['EventType']));  ?>
    <?php if($tokens['isAdmin']) : ?>
      <ul>
	<li><?php echo $this->Html->link(__('changer Horaires', true), array('plugin' => 'full_calendar','controller'=>'eventTypes', 'action' => 'view', $shop['EventType']['id'])); ?></li>
	<li><?php echo $this->Html->link(__('Modifier', true), array('controller'=>'shops', 'action' => 'edit', $shop['Shop']['id'])); ?></li>
      </ul>
  <?php endif ?>
  </div>
 </div>

 
<div class="clear" ></div>
<?php //debug($shop); ?>
</div>
