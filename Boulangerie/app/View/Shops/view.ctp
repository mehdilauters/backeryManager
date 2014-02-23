  <?php   echo $this->element('Gevents/jsCalendar', array('data'=>$shop['EventType'])); ?>
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
  <img class="imageShopView" src="<?php echo $this->webroot.'img/photos/normal/'.$shop['Media']['path'] ?>" alt="<?php echo $shop['Media']['name'] ?>" />
  <div class="slate slateShop slateShopView cursorHand"  onClick="toggleMap();">
      <div>
	<?php echo h($shop['Shop']['phone']); ?>
      </div>
    <div>
	<?php echo h($shop['Shop']['address']); ?>
	<br/>
	( carte )
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
      <?php echo h($shop['Shop']['description']); ?>
</p>
  <hr/>
  <?php
  if($isOpened)
  {?>
    <span class="shopOpened">La boulangerie est ouverte</span>
  <?php }
  else
  {?>
    <span class="shopClosed">La boulangerie est fermée</span>
  <?php } 
?>
  <hr/>

  <?php echo $this->element('Shops/Timetable', array('eventType'=>$shop['EventType']));  ?>
  
  
<!--     <div id="calendar" class="slate" >Calendar loading</div> -->
</div>
<div class="actions">
  <ul>
  <?php if($tokens['isAdmin']) : ?>
    <li><?php echo $this->Html->link(__('Edit Shop'), array('action' => 'edit', $shop['Shop']['id'])); ?> </li>
    <li><?php echo $this->Form->postLink(__('Delete Shop'), array('action' => 'delete', $shop['Shop']['id']), null, __('Are you sure you want to delete # %s?', $shop['Shop']['id'])); ?> </li>
    <li><?php echo $this->Html->link(__('New Shop'), array('action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
  <?php endif ?>
  </ul>
</div>
