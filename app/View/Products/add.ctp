<div class="products form">
<?php echo $this->Form->create('Product'); ?>
  <fieldset>
    <legend><?php echo __('Add Product'); ?></legend>
  <?php
    echo $this->Form->input('product_types_id', array('label'=>'Catégorie')); ?>
    <div class="alert alert-info">
      Choisissez une photo pour ce produit
      <?php echo $this->Form->input('media_id', array('label'=>'Photo', 'class'=>'formPhotoPreview')); ?>
      <img src="" id="ProductMediaId_preview" />
    </div>
<?php    echo $this->Form->input('name', array('label'=>'Nom'));
    echo $this->Form->input('description', array('class'=>'textEditor'));
    echo $this->Form->input('price', array('label'=>'Prix')); ?>
    <div class="alert alert-info">
     Cocher la ca case si le produit se vend à l'unité, décocher si au poids
<?php echo $this->Form->input('unity', array('label'=>'a l\'unité')); ?>
    </div>
    <div class="alert alert-info">
      Cocher la case pour afficher ce produit sur le site, décocher pour le masquer aux clients
<?php echo $this->Form->input('customer_display', array('label'=>'Affiché aux clients')); ?>
    </div>
<div class="alert alert-info">
  Affiche le produit dans la liste des produits dont la production/vente doit être suivie
<?php echo $this->Form->input('production_display', array('label'=>'affiché dans la saisie des ventes')); ?>
</div>
<div class="alert alert-info">
  Lorsque ce bouton est coché, le produit est marqué disponible seulement si il a été produit dans la journée
<?php    echo $this->Form->input('depends_on_production', array('label'=>'Disponible en fonction de la saisie des ventes'));?>
  </div>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('Nouvelle catégorie de produit'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('Nouvelle Photo'), array('controller' => 'photos', 'action' => 'add')); ?> </li>
  </ul>
</div>
