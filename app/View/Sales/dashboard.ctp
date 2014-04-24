<div>
	<h2><?php echo $this->Dates->timeToDateCompleteFR(time()) ?> </h2>
	<h3>Evolution annuelle</h3>
	<?php echo $this->element('Results/stats/results', array('results'=>$results, 'config'=>array('interactive'=>false))); ?>
	<h3>Evolution mensuelle</h3>
	<?php echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$resultsEntries, 'config'=>array('interactive'=>false)));  ?>
	<h3>Evolution</h3>
	<h3>C'etait un <?php echo $this->Dates->getJourFr(date('w')) ?></h3>
	<?php /*
	<h4>Production/Pertes</h4>
	<?php echo $this->element('Sales/stats', array('sales'=>$sales));  ?>
	<h4>Types de produits</h4>
	*/ ?>
	<?php echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$dayStats, 'config'=>array('id'=>1,'interactive'=>false)));  ?>
	<h3>C'etait un <?php echo date('d/m') ?></h3>
	<?php echo $this->element('Sales/stats', array('sales'=>$sales));  ?>
</div> 
