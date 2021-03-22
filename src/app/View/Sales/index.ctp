<div class="sales index">
  <h2><?php echo __('Sommaire'); ?></h2>
  <ul>
    <li id="dailyProductionLink" >
	<div id="" class="alert alert-info">
	  <p>Saisie journalières des données de production</p>
	  <a href="<?php echo $this->webroot ?>sales/add" ><?php echo $this->Html->image('icons/go-jump-today.png', array('alt' => '')); ?>Saisie</a>
	</div>
    </li>
    <li>
      <div id="" class="alert alert-info">
	<p>Visualisation des données, jour par jour</p>
	<a href="<?php echo $this->webroot ?>sales/view" ><?php echo $this->Html->image('icons/system-search.png', array('alt' => '')); ?>Visualisation</a>
      </div>
    </li>
    <li>
      <div id="" class="alert alert-info">
	<p>Visualisation des statistiques (par mois/jour/année, groupés par magasins/produits)/<p>
	<a href="<?php echo $this->webroot ?>sales/stats" ><?php echo $this->Html->image('icons/view-statistics.png', array('alt' => '')); ?>Statistiques</a>
      </div>
    </li>
  </ul>
</div>
