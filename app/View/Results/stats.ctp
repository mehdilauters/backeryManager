<div class="results index">
<div>
  <?php
// 	$group = array('time' => '', 'shop'=>'', 'productType'=>'');
	$conditions = array('shop'=>'', 'productType'=>'');
  ?>
  
<?php echo $this->Form->create('Results'); ?>
	  <fieldset id="groupFieldset" class="alert alert-info">
		<legend>Grouper par</legend>
		<div>Grouper les données permet d'afficher les resultat sous des formes différentes (jour par jour/mois par mois, magasin par magasin/somme des magasins...) Le groupement est cumulatif</div>
		<?php echo $this->Form->input('group.time', array(		'label' => 'Date',
									      'options'=>(array( '' => '',
											  'weekday' => 'Jour de la semaine',
											  'day' => 'Jour',
											  'week' => 'Semaine',
											  'month' => 'Mois',
											  'year' => 'Année',
											)))); ?>
		<?php echo $this->Form->input('group.shop', array(	'label' => 'Magasin',
									'type'=>'checkbox')); ?>
		<?php echo $this->Form->input('group.productType', array(	'label' => 'Type de produit (second graph)',
									'type'=>'checkbox')); ?>
		

	</fieldset>
	<fieldset id="filterFieldset" class="alert alert-info">
	    <legend >filtrer par</legend>
	    <div>Le filtrage permet de limiter les données séléctionnées. Concentrez vous sur un magasin particulier, un type de produit</div>
	      <?php echo $this->Form->input('conditions.shop', array('label'=>'Magasin', 'options'=>(array(''=>'') + $results['shops']))); ?>
	      <?php echo $this->Form->input('conditions.productType', array('label'=>'Type de produit (second graph)', 'options'=>(array(''=>'') + $results['productTypes']))); ?>
	    <?php echo $this->Form->input('conditions.dateStart', array('label'=>'Début', 'class'=>'datepicker')); ?>
	    <?php echo $this->Form->input('conditions.dateEnd', array('label'=>'Fin', 'class'=>'datepicker')); ?>
	
	</fieldset>

<fieldset id="approxFieldset" class="alert alert-info">
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


<fieldset id="compareFieldset" class="alert alert-info">
      <label>Comparaison</label>
		<div>Comparer sur plusieurs années?</div>
		<?php echo $this->Form->input('compare.date', array('label'=>'Comparer', 'type'=>'checkbox')); ?>
    </fieldset>

<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php   //echo $this->element('Results/stats', array('results'=>$results, 'resultsEntries'=>$resultsEntries, 'shops'=>$shops, 'productTypes'=>$productTypes)); ?>
<?php   echo $this->element('Results/stats/results', array('results'=>$results)); ?>
<a name="resultsEntries" ></a>
<?php   echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$results, 'config'=>array('shopComparative'=>true))); ?>
</div> 

<script>
  introSteps = [
              { 
                intro: 'Ici, vous pouvez visualiser, comparer, analyser le <b>chiffre d\'affaire</b> de votre entreprise,<br/>en affichant les données par jour, par mois, par an..., magasin par magasin, catégorie de produits par catégorie, ou au contraire de manière plus synthetiques comme par example, tous magasins et/ou toutes les catégories produits confondus'
              },
              {
                element: '#groupFieldset',
                intro: "Sélectionnez ici la manière dont vous voulez grouper les données",
		position: 'right'
              },
              {
                element: '#filterFieldset',
                intro: "Dans cette zone, vous pouvez filtrez les résultats: les données ne coresspondant pas aux critères ne seront pas prises en compte",
		position: 'right'
              },
              {
                element: '#approxFieldset',
                intro: "Approximer les données permet de lisser les courbes afin d'en faire ressortir une tendance sur le long terme.<br/><b>Attention</b> : il s'agit d'une interpretation, d'une aide à l'analyse, et non une analyse fiable et complète.<br/>Seul l'artisan pourra confirmer ou non ces tendances, aux vues des données brutes",
		position: 'right'
              },
              {
                element: '#compareFieldset',
                intro: "En cochant cette case, les resultats seront affichés, une courbe par année. Cela permet de comparer plus facilement les chiffres d'affaire des années précédentes",
		position: 'right'
              },
              {
                element: '#resultsChart',
                intro: "En fonction des critères précédement saisis, un graph est calculé afin de permettre une visualisation simple des ventes",
		position: 'right'
              },
              {
                element: '#controlChart_resultsChart',
                intro: "Sur ce graph, il est possible d'afficher/masquer une courbe en la sélectionnant ici",
		position: 'right'
              },
              {
                element: '#resultsStatValues',
                intro: "Les données servant au calcul du graphe sont disponibles également dans le tableau ci-contre.<br/> Vous pouvez également filtrer ces données, le graphique sera automatiquement mis-à-jour",
		position: 'top'
              },
			];
</script>