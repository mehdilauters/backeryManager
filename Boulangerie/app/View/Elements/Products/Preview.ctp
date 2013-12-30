<?php
 //debug($product);
$data = $product;
if(isset($product['Product']))
{
  $data = $product['Product'];
}
?>
<div class="productPreview">
  <?php echo $this->element('Medias/Medias/Preview', array('media'=>$data, 'config'=>array('name'=>false, 'description'=>false))); ?>
  <div class="details slate">
    <div>
      <?php echo $data['name'] ?>
    </div>
    <div>
	<?php echo $data['price']; ?>€ <?php if(!$data['unity']) echo 'Kg' ?>
	<?php 
	    if( $isCalendarAvailable ) {
	    if( $this->Dates->isToday($data)) {  ?>
	  <div class="productAvailable" >Disponible aujourd'hui</div>
	<?php } else { ?>
	  <div class="productNotAvailable" >Non disponible aujourd'hui</div>
	<?php } 
	    }
	?>
	<?php foreach($data['Events'] as $event) : ?>
	    <?php foreach($event['Gevent']['GeventDate'] as $eventDate) : ?>
	      <?php //debug($eventDate) ?>
	    <?php endforeach; ?>
	<?php endforeach; ?>
    </div>
        <?php if($tokens['isAdmin']) : ?>
	    <div class="actions">
          <?php echo $this->Html->link(__('Edit'), array('controller' => 'events', 'action' => 'edit', $event['id'])); ?>
          <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'events', 'action' => 'delete', $event['id']), null, __('Are you sure you want to delete # %s?', $event['id'])); ?>
	  </div>
        <?php endif ?>
  </div>
 <div class="clear" ></div>
</div>