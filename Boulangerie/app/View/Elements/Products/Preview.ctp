<?php
 //debug($product);
$data = $product;
if(isset($product['Product']))
{
  $data = $product['Product'];
}

$class = '';
if( $isCalendarAvailable ) {
  $isToday = $this->Dates->isToday($product);
  if($isToday)
  {
    $class = 'today';
  }
}

?>
<div class="productPreview <?php if($tokens['isAdmin'] && !$data['customer_display']) echo 'customerHidden'; echo $class; ?>">
  <?php echo $this->element('Medias/Medias/Preview', array('media'=>$data, 'config'=>array('name'=>false, 'description'=>false))); ?>
  <div class="details slate">
    <div>
      <a href="<?php echo $this->webroot.'produits/details/'.$data['id'] ?>" >
	<?php echo $data['name'] ?>
      </a>
    </div>
    <div>
	<?php echo $data['price']; ?>€ <?php if(!$data['unity']) echo 'Kg' ?>
	<?php 
	    if( $isCalendarAvailable ) {
	    if( $isToday ) {  ?>
	  <div class="productAvailable" >Disponible aujourd'hui</div>
	<?php } else { ?>
	  <div class="productNotAvailable" >Non disponible aujourd'hui</div>
	<?php } 
	    }
	?>
	<?php
// 	    foreach($data['Events'] as $event) : 
// 		foreach($event['Gevent']['GeventDate'] as $eventDate) : 
// 		  //debug($eventDate) 
// 		endforeach;
// 	      endforeach; 
	    ?>
    </div>
        <?php if($tokens['isAdmin']) : ?>
	  <?php if(!$data['customer_display'] ) { ?>
	  <div>Caché aux clients</div>
	  <?php } ?>
	    <div class="actions">
          <?php echo $this->Html->link(__('Edit'), array('controller' => 'products', 'action' => 'edit', $data['id'])); ?>
	  <?php echo $this->Html->link(__('Evenement'), array('controller' => 'events', 'action' => 'add', 'idProduct'=>$data['id'])); ?>
          <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'products', 'action' => 'delete', $data['id']), null, __('Are you sure you want to delete # %s?', $data['id'])); ?>
	  </div>
        <?php endif ?>
  </div>
 <div class="clear" ></div>
</div>