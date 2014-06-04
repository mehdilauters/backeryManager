<div class="productTypes form">
<?php echo $this->Form->create('ProductType'); ?>
	<fieldset>
		<legend><?php echo __('Add Product Type'); ?></legend>
	<?php echo $this->Form->input('id'); ?>
	<div class="alert alert-info">
	    
	  Choisissez une photo pour cette catégorie
	<?php echo $this->Form->input('media_id', array('label'=>'Photo', 'class'=>'formPhotoPreview')); ?>
	  <img src="" id="ProductTypeMediaId_preview" />
	  </div>
		<?php echo $this->Form->input('name', array('label'=>'Nom')); ?>
		<div class="alert alert-info">
		Insérez ici le pourcentage de TVA sur cette catégorie de produits
		<?php echo $this->Form->input('tva', array('label'=>'TVA %')); ?>
		</div>
		<div class="alert alert-info">
		  Décocher la case pour masquer cette catégorie de produit aux clients
		<?php echo $this->Form->input('customer_display', array('label'=>'Affiché aux clients')); ?>
		</div>
<?php
		echo $this->Form->input('description', array('class'=>'textEditor'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Nouvelle Photo'), array('controller' => 'photos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Nouveau Produit'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>