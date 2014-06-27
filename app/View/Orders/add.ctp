<div class="orders form">
<?php echo $this->Form->create('Order'); ?>
	<fieldset>
		<legend><?php echo __('Add Order'); ?></legend>
	<?php
		echo $this->Form->input('shop_id', array('label' => 'Magasin'));
		echo $this->Form->input('user_id', array('label' => 'Compte client'));
		//echo $this->Form->input('status',array('options'=>array('reserved'=>'réservée','available'=>'disponible','waiting'=>'en attente', 'paid'=>'payée')));
		echo $this->Form->input('delivery_date', array('type'=>'text', 'class'=>'datetimepicker', 'label' => 'Date de livraison' ));
		//echo $this->Form->input('discount');
		echo $this->Form->input('comment', array('label' => 'Commentaires'));
	?>
	</fieldset>
<?php echo $this->Form->end(array('label'=>__('Submit'), 'id'=>'submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Lister commandes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Nouveau Compte client'), array('controller' => 'users', 'action' => 'add'), array('id'=>'newUser')); ?> </li>
		<!-- <li><?php echo $this->Html->link(__('List Ordered Items'), array('controller' => 'ordered_items', 'action' => 'index')); ?> </li> -->
	</ul>
</div>
<script>
  introSteps = [
              { 
                intro: 'Cette page permet de créer une nouvelle commande.<br/> Par la suite, les produits demandés par le client seront donc rattaché à cette facture'
              },
              {
                element: '#OrderShopId',
                intro: "Selectionnez ici le magasin auquel est rattachée cette commande",
				position: 'top'
              },
              {
                element: '#OrderUserId',
                intro: "Selectionnez ici le compte utilisateur auquel est rattachée cette commande",
				position: 'top'
              },
              {
                element: '#newUser',
                intro: "Si le compte n'existe pas, commencez par le créer en allant sur cette page",
				position: 'top'
              },
              {
                element: '#OrderDeliveryDate',
                intro: "Entrez ensuite la date de facturation/livraison voulue",
				position: 'top'
              },
              {
                element: '#OrderComment',
                intro: "Et un commentaire si necessaire",
				position: 'top'
              },
			  {
                element: '#submit',
                intro: "Validez, et vous serez rediriger vers la liste de toutes les commandes",
				position: 'top'
              },
            ];
</script>

