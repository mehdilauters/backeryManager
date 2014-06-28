<?php
	$media = $productType;
	if(isset($productType['ProductType']['Media']))
	{
		$media = $productType['ProductType'];
	}
?>
<div class="productTypePreview <?php if($tokens['isAdmin']  && !$productType['ProductType']['customer_display'] ) echo 'customerHidden'; ?>" >
	<div class="col-sm-6 col-md-4" >
		<div class="slate" >
			<div class="caption" >
				<h3><a href="<?php echo $this->webroot.'typesProduits/details/'.$productType['ProductType']['id'].$this->MyHtml->getLinkTitle($productType['ProductType']['name']) ?>" ><?php echo h($productType['ProductType']['name']); ?></a></h3>
			</div>
			<div id="productTypeContent_<?php echo ($productType['ProductType']['id']); ?>" class="productTypeContent" >
			<?php echo $this->element('Medias/Medias/Preview', array('media'=>$productType, 'config'=>array('name'=>false, 'description'=>false)));?>
				<div class="description" ><?php echo h($productType['ProductType']['description']); ?>
				  <?php 
				if($tokens['isAdmin'])
				{
					if(!$productType['ProductType']['customer_display'] ) { ?>
				  <div>Caché aux clients</div>
				  <?php }
					?>
				<div>
					Tva : <?php echo $productType['ProductType']['tva'] ?> %
				</div>
				<?php }	  ?>
				</div>
			</div>
			<div class="actions">
			  <?php if($tokens['isAdmin']) : ?>
			    <?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('id'=>'edit_'.$productType['ProductType']['id'],'class'=>'icon','alt' => __('Edition'))), array('controller'=>'productTypes','action' => 'edit', $productType['ProductType']['id']),  array('escape' => false, 'title'=>'editer')); ?>
			    <?php echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('id'=>'delete_'.$productType['ProductType']['id'],'class'=>'icon','alt' => __('supprimer'))), array('controller'=>'productTypes','action' => 'delete', $productType['ProductType']['id']) , array('escape' => false, 'title'=>'supprimer'), __('Are you sure you want to delete # %s?', $productType['ProductType']['id'])); ?>
			  <?php endif ?> 
			</div>
		</div>
	</div>
</div>