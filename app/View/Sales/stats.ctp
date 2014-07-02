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
		<?php echo $this->Form->input('group.product', array(	'label' => 'produit',
									'type'=>'checkbox')); ?>
		

	</fieldset>
	<fieldset id="filterFieldset" class="alert alert-info">
	    <legend >filtrer par</legend>
	    <div>Le filtrage permet de limiter les données séléctionnées. Concentrez vous sur un magasin particulier, un type de produit</div>
	      <?php echo $this->Form->input('conditions.shop', array('label'=>'Magasin', 'options'=>(array(''=>'') + $sales['shops']))); ?>
	      <?php echo $this->Form->input('conditions.product', array('label'=>'Produit', 'options'=>(array(''=>'') + $sales['productsList']))); ?>
	    <?php echo $this->Form->input('conditions.dateStart', array('label'=>'Début', 'class'=>'datepicker')); ?>
	    <?php echo $this->Form->input('conditions.dateEnd', array('label'=>'Fin', 'class'=>'datepicker')); ?>
	
	</fieldset>

<fieldset id="approxFieldset" class="alert alert-info">
      <label>Approximation</label>
		<div>Afin de lisser les courbes, calculer une moyenne, donner une tendance sur le long terme, utilisez l'approximation. <div class="alert alert-danger">Attention! Une approximation ne reflète parfois pas la réalité! Utilisez les cases a cocher pour comparer le résultat avec les vraies données</div>
			<div class="alert alert-danger">Attention! Sur des périodes de dates courtes, l'approximation peut donner une tendance inexacte voir totalement fausse</div>
		</div>
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
<script>
  introSteps = [
              { 
                intro: 'Ici, vous pouvez visualiser, comparer, analyser les ventes de votre entreprise,<br/>en affichant les données par jour, par mois, par an..., magasin par magasin, produit par produit, ou au contraire de manière plus synthetiques comme par example, tous magasins et/ou tout produits confondus'
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
                element: '#histogramChart',
                intro: "En fonction des critères précédement saisis, un graph est calculé afin de permettre une visualisation simple des ventes",
		position: 'right'
              },
              {
                element: '#controlChart_histogramChart',
                intro: "Sur ce graph, il est possible d'afficher/masquer une courbe en la sélectionnant ici",
		position: 'right'
              },
              {
                element: '#control_histogramChart_0',
                intro: "Pour comparer la courbe représentant la tendance de la production avec les données réelles, vous pouvez simplement cocher cette case",
		position: 'top'
              },
              {
                element: '#statValues',
                intro: "Les données servant au calcul du graphe sont disponibles également dans le tableau ci-contre.<br/> Vous pouvez également filtrer ces données, le graphique sera automatiquement mis-à-jour",
		position: 'top'
              },
			];
</script>
