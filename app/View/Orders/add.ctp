<div class="orders form">
<?php echo $this->Form->create('Order', array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Add Order'); ?></legend>
	<?php
		echo $this->Form->input('shop_id',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Magasin'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('user_id', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Compte Client'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control',
					  'required'=>false,
					  ));
?>
<fieldset id="userAddFieldset" class="alert alert-info" > <legend>Compte inexistant</legend>
<?php
              echo $this->Form->input('User.name', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Nom'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control uniqueUserWatch',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.email', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Email'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control uniqueUserWatch',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.address', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Adresse'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.phone', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Téléphonel'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.discount', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Discount'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          ));
?>
</fieldset>
<?php
		//echo $this->Form->input('status',array('options'=>array('reserved'=>'réservée','available'=>'disponible','waiting'=>'en attente', 'paid'=>'payée')));
		echo $this->Form->input('delivery_date', array('type'=>'text',
					    'label'=>array(
							  'class'=>'col-sm-3 control-label', 'text'=>'Date de livraison'
						  ),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control datetimepicker'
					  ));
		//echo $this->Form->input('discount');
		echo $this->Form->input('comment',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Commentaires'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control textEditor'
					  ));
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
function updateDom()
{
  userId = $('#OrderUserId').val();
  if( userId.length == 0 )
    {
      $('#userAddFieldset').show();
    }
    else
    {
      $('#userAddFieldset').hide();
    }

}
$( document ).ready( function (){
  $('#OrderUserId').change(function (){
    updateDom();
    });

    updateDom();
});
</script>

