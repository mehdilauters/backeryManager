<div class="results index">
<div>
  <?php
// 	$group = array('time' => '', 'shop'=>'', 'productType'=>'');
	$conditions = array('shop'=>'', 'productType'=>'');
  ?>
  
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
		<?php echo $this->Form->input('group.productType', array(	'label' => 'Type de produit (second graph)',
									'type'=>'checkbox')); ?>
		

	</fieldset>
	<fieldset class="alert alert-info">
	    <legend >filtrer par</legend>
	    <div>Le filtrage permet de limiter les données séléctionnées. Concentrez vous sur un magasin particulier, un type de produit</div>
	      <?php echo $this->Form->input('conditions.shop', array('label'=>'Magasin', 'options'=>(array(''=>'') + $results['shops']))); ?>
	      <?php echo $this->Form->input('conditions.productType', array('label'=>'Type de produit (second graph)', 'options'=>(array(''=>'') + $results['productTypes']))); ?>
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
												      Configure::read('Approximation.order') => 'Maximum',
												      )))); ?>
    </fieldset>

<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php   //echo $this->element('Results/stats', array('results'=>$results, 'resultsEntries'=>$resultsEntries, 'shops'=>$shops, 'productTypes'=>$productTypes)); ?>
<?php   echo $this->element('Results/stats/results', array('results'=>$results)); ?>
<a name="resultsEntries" />
<?php   echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$results, 'config'=>array('shopComparative'=>true))); ?>
</div> 