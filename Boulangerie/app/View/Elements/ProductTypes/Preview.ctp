<?php
	$media = $productType;
	if(isset($productType['ProductType']['Media']))
	{
		$media = $productType['ProductType'];
	}
?>
<div class="productTypePreview <?php if($tokens['isAdmin']  && !$productType['ProductType']['customer_display'] ) echo 'customerHidden'; ?>" >
<h3><a href="<?php echo $this->webroot.'productTypes/view/'.$productType['ProductType']['id'] ?>" ><?php echo h($productType['ProductType']['name']); ?></a></h3>
<div id="productTypeContent_<?php echo ($productType['ProductType']['id']); ?>" class="productTypeContent" >
<?php echo $this->element('Medias/Medias/Preview', array('media'=>$media, 'config'=>array('name'=>false, 'description'=>false)));?>
    <div class="slate description" ><?php echo h($productType['ProductType']['description']); ?>
      <?php if($tokens['isAdmin']  && !$productType['ProductType']['customer_display'] ) { ?>
	  <div>Caché aux clients</div>
      <?php } ?>
    </div>
</div>
    <div class="actions clear">
<!--       <?php echo $this->Html->link(__('View'), array('action' => 'view', $productType['ProductType']['id'])); ?> -->
      <?php if($tokens['isAdmin']) : ?>
        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $productType['ProductType']['id'])); ?>
        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $productType['ProductType']['id']), null, __('Are you sure you want to delete # %s?', $productType['ProductType']['id'])); ?>
      <?php endif ?> 
    </div>
</div>