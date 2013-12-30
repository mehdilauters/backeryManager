<div class="productTypePreview" >
<h3><?php echo h($productType['ProductType']['name']); ?></h3>
<?php echo $this->element('Medias/Medias/Preview', array('media'=>$productType, 'config'=>array('name'=>false, 'description'=>false)));?>
    <div class="slate description" ><?php echo h($productType['ProductType']['description']); ?></div>
    <div class="actions clear">
<!--       <?php echo $this->Html->link(__('View'), array('action' => 'view', $productType['ProductType']['id'])); ?> -->
      <?php if($tokens['isAdmin']) : ?>
        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $productType['ProductType']['id'])); ?>
        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $productType['ProductType']['id']), null, __('Are you sure you want to delete # %s?', $productType['ProductType']['id'])); ?>
      <?php endif ?> 
    </div>
</div>