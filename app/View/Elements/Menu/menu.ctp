<?php

foreach($menu as $title => $subMenu)
{ ?>
  <ul id="<?php echo strtolower($title) ?>">
    <li>
      <?php echo $title ?>
      <ul>
<?php
  foreach($subMenu as $item => $data)
  { ?>
  
    <li id="<?php if(isset($data['id'])) echo $data['id'] ?>" class="<?php if($data['active']) echo 'active' ?>" ><a href="<?php echo str_replace('WEBROOT/',$this->webroot, $data['url']) ?>"  ><?php echo $item ?></a></li>
  
  <?php }
?>
	<li id="helpLink"><a href="#" onclick="startIntro(); return false;" >Aide</a></li>
    </ul>
  </li>
<?php
}

?>
</ul>
<ul id="menuAdmin">
    <?php if($tokens['isAdmin']) : ?>
      <li id="menuAdmin" > Administration
	<ul>
	  <li><a href="<?php echo $this->webroot ?>sales/dashboard">Tableau de bord</a></li>
	  <li id="sales" ><a href="<?php echo $this->webroot ?>sales">Production</a></li>
	  <li id="dailyResultsLink" ><a href="<?php echo $this->webroot ?>results/add">saisie journaliere compta</a></li>
	  <li><a href="<?php echo $this->webroot ?>results/"><?php echo $this->Html->image('icons/system-search.png', array('alt' => '')); ?> visualiastion compta</a></li>
	  <li id="resultsStats" ><a href="<?php echo $this->webroot ?>results/stats"><?php echo $this->Html->image('icons/office-chart-area-percentage.png', array('alt' => '')); ?> stats compta</a></li>
	  <li><a href="<?php echo $this->webroot ?>photos/add"><?php echo $this->Html->image('icons/camera-photo.png', array('alt' => '')); ?> ajouter une photo</a></li>
	  <li><a href="<?php echo $this->webroot ?>productTypes/add">ajouter un type de produit</a></li>
	  <li><a href="<?php echo $this->webroot ?>products/add">ajouter un produit</a></li>
	  <!--<li><a href="<?php echo $this->webroot ?>full_calendar/events/add"><?php echo $this->Html->image('icons/resource-calendar-insert.png', array('alt' => '')); ?> ajouter un evenement</a></li>-->
	  <li  id="ordersLink" ><a href="<?php echo $this->webroot ?>orders/">Commandes</a></li>
	  <li><a href="<?php echo $this->webroot ?>news/add"><?php echo $this->Html->image('icons/news-subscribe.png', array('alt' => '')); ?> Ajouter une news</a></li>
	  <li><a href="<?php echo $this->webroot ?>users"><?php echo $this->Html->image('icons/user-properties.png', array('alt' => '')); ?> Comptes utilisateurs</a></li>
	  <li><a id="accountsLink" href="<?php echo $this->webroot ?>account_management/">Comptes</a></li>
	  <?php if($tokens['isRoot']) : ?>
	    <li><a href="<?php echo $this->webroot ?>companies/add">Entreprise</a></li>
	    <?php else: ?>
	      <li><a href="<?php echo $this->webroot ?>companies/edit/<?php echo $company['Company']['id'] ?>">Entreprise</a></li>
	    <?php endif ?>
	</ul>
      </li>
    <?php elseif($tokens['member']): ?>
      <li id="menuMember" > Espace membre
	<ul>
	  <li><a href="<?php echo $this->webroot ?>orders">Mes factures</a></li>
	</ul>
    <?php endif ?>
  </ul>
