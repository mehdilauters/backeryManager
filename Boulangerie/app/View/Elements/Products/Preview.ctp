<?php
 //debug($product);
$data = $product;
if(isset($product['Product']))
{
  $data = $product['Product'];
}

$class = '';
  if( $data['produced_today'] != 0  )
  {
    $class = 'today';
  }

?>
<div class="productPreview <?php if($tokens['isAdmin'] && !$data['customer_display']) echo 'customerHidden'; echo $class; ?>">
  <?php echo $this->element('Medias/Medias/Preview', array('media'=>$product, 'config'=>array('name'=>false, 'description'=>false))); ?>
  <div class="details slate">
    <div>
      <a href="<?php echo $this->webroot.'produits/details/'.$data['id'].$this->MyHtml->getLinkTitle($data['name']) ?>" >
	<?php echo $data['name'] ?>
      </a>
    </div>
    <div>
	<?php echo $data['price']; ?>€ <?php if(!$data['unity']) echo 'Kg' ?>
	<?php 
	    if($data['depends_on_production'])
	      if( $data['produced_today'] != 0 ) {  ?>
	    <div class="productAvailable" >Disponible aujourd'hui</div>
	  <?php } else { ?>
	    <div class="productNotAvailable" >Non disponible aujourd'hui</div>
	  <?php } 
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
	  <?php }
	  if(!$data['production_display'] ) { ?>
	  <div>Caché a la production</div>
	  <?php }
	   if($data['depends_on_production'] ) { ?>
	    <div>Disponible selon production</div>
	  <?php } else { ?>
	    <div>Disponible en permanance</div>
	  <?php } ?>
	    <div class="actions">
          <?php echo $this->Html->link(__('Edit'), array('controller' => 'products', 'action' => 'edit', $data['id'])); ?>
          <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'products', 'action' => 'delete', $data['id']), null, __('Are you sure you want to delete # %s?', $data['id'])); ?>
	  </div>
        <?php endif ?>
  </div>
 <div class="clear" ></div>
</div>