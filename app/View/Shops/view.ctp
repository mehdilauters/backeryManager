  <script>
  
  function toggleMap()
  {
   $("#mapPreview").toggle('slow');
  }
  
$(document).ready(function() {
    
});
  </script>

<div class="shops view">

<?php   
if($tokens['isAdmin'])
{
	echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$resultsEntries, 'config'=>array('interactive'=>false))); 
}
	?>
<div>
  <img class="imageShopView" src="<?php echo $this->webroot.'photos/download/'.$shop['Media']['id'].'/0'.$this->MyHtml->getLinkTitle($shop['Media']['name']); ?>" alt="<?php echo $shop['Media']['name'] ?>" />
  <div class="slate slateShop slateShopView cursorHand"  onClick="toggleMap();">
<div class="isOpened">
    <?php
      $nextDates = $this->Dates->getNextOpenClose($shop['EventType'],$shop['EventTypeClosed']);
      if($isOpened)
      {?>
	<span class="shopOpened label label-success">ouverte jusqu'à <?php echo date('G:i',$nextDates['nextClose']) ?></span>
      <?php }
      else
      {?>
	<span class="shopClosed label label-danger">fermé <?php if($nextDates['nextOpened'] != 0){ ?> jusqu'au <?php echo date('d/m/Y',$nextDates['nextOpened']).' à '.date('G:i',$nextDates['nextOpened']); }?></span>
      <?php } 
    ?>
</div>
      <div>
	<?php echo $this->MyHtml->getPhoneNumberText($shop['Shop']['phone']); ?>
      </div>
    <div>
	<a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" target="_blank" ><?php echo $shop['Shop']['address']; ?></a>
	<br/>
	<a href="#" onclick="return false;">( carte )</a>
    </div>
  </div>
</div>
<div class="clear" ></div>
<center>
<div class="hideJs" id="mapPreview">
    <a href="http://maps.google.com/maps?q=<?php echo urlencode($shop['Shop']['address']); ?>&z=17" target="_blank" >
        <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($shop['Shop']['address']); ?>&zoom=14&size=400x400&sensor=false&maptype=roadmap&markers=color:blue%7C<?php echo urlencode($shop['Shop']['address']); ?>" alt="location"/>
    </a>
</div>
</center>
<p>
      <?php echo $shop['Shop']['description']; ?>
</p>
  <hr/>

  <?php echo $this->element('Shops/Timetable', array('eventType'=>$shop['EventType'], 'eventTypeClosed'=>$shop['EventTypeClosed']));  ?>

</div>
<div class="actions">
  <ul>
  <?php if($tokens['isAdmin']) : ?>
    <li><?php echo $this->Html->link(__('Horaires', true), array('plugin' => 'full_calendar','controller'=>'eventTypes', 'action' => 'view', $shop['EventType']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Fermetures', true), array('plugin' => 'full_calendar','controller'=>'eventTypes', 'action' => 'view', $shop['EventTypeClosed']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Edit Shop'), array('action' => 'edit', $shop['Shop']['id'])); ?> </li>
    <li><?php echo $this->Form->postLink(__('Delete Shop'), array('action' => 'delete', $shop['Shop']['id']), null, __('Are you sure you want to delete # %s?', $shop['Shop']['id'])); ?> </li>
    <li><?php echo $this->Html->link(__('New Shop'), array('action' => 'add')); ?> </li>
  <?php endif ?>
  </ul>
</div>
