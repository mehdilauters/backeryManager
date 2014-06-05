<?php echo $this->Form->create('Results'); ?>
	  <fieldset class="alert alert-info">
		<legend>Grouper par</legend>
		<div>Grouper les données permet d'afficher les resultat sous des formes différentes (jour par jour/mois par mois, magasin par magasin/somme des magasins...) Le groupement est cumulatif</div>
		<?php echo $this->Form->input('group.time', array(		'label' => 'Date',
									      'options'=>(array( '' => '',
											  'weekday' => 'Jour de la semaine',
											  'day' => 'Jour',
											  'semaine' => 'Semaine',
											  'month' => 'Mois',
											  'year' => 'Année',
											)))); ?>
		<?php echo $this->Form->input('group.shop', array(	'label' => 'Magasin',
									'type'=>'checkbox')); ?>
		<?php echo $this->Form->input('group.product', array(	'label' => 'produit',
									'type'=>'checkbox')); ?>
		

	</fieldset>
	<fieldset class="alert alert-info">
	    <legend >filtrer par</legend>
	    <div>Le filtrage permet de limiter les données séléctionnées. Concentrez vous sur un magasin particulier, un type de produit</div>
	      <?php echo $this->Form->input('conditions.shop', array('label'=>'Magasin', 'options'=>(array(''=>'') + $sales['shops']))); ?>
	      <?php echo $this->Form->input('conditions.product', array('label'=>'Produit', 'options'=>(array(''=>'') + $sales['productsList']))); ?>
	    <?php echo $this->Form->input('conditions.dateStart', array('label'=>'Début', 'class'=>'datepicker')); ?>
	    <?php echo $this->Form->input('conditions.dateEnd', array('label'=>'Fin', 'class'=>'datepicker')); ?>
	
	</fieldset>

<fieldset class="alert alert-info">
      <label>Approximation</label>
		<div>Afin de lisser les courbes, calculer une moyenne, donner une tendance sur le long terme, utilisez l'approximation. <div class="alert alert-danger">Attention! Une approximation ne reflète parfois pas la réalité! Utilisez les cases a cocher pour comparer le résultat avec les vraies données</div></div>
		<?php echo $this->Form->input('approximation.order', array('label'=>'Approximation', 'options'=>(array(
												      '1'=>'Constante (moyenne)',
												      '2'=>'Linéaire (droite)',
												      '3'=>'Parabolique',
												      '4'=>'Quadratique',
												      Configure::read('Settings.Approximation.order') => 'Maximum',
												      )))); ?>
    </fieldset>

<?php echo $this->Form->end(__('Submit')); ?>
<?php
echo $this->element('Sales/stats', array('sales'=>$sales)); 

?>