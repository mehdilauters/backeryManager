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
  
    <li class="<?php if($data['active']) echo 'active' ?>" ><a href="<?php echo str_replace('WEBROOT/',$this->webroot, $data['url']) ?>"  ><?php echo $item ?></a></li>
  
  <?php }
?>
    </ul>
  </li>
<?php
}

?>
</ul>
<ul id="">
    <?php if($tokens['isAdmin']) : ?>
      <li id="menuAdmin" > Administration
	<ul>
	  <li><a href="<?php echo $this->webroot ?>sales/add">Saisie journaliere</a></li>
	  <li><a href="<?php echo $this->webroot ?>breakages/add">Resultats compta</a></li>v
	  <li><a href="<?php echo $this->webroot ?>photos/add">ajouter une photo</a></li>
	  <li><a href="<?php echo $this->webroot ?>productTypes/add">ajouter un type de produit</a></li>
	  <li><a href="<?php echo $this->webroot ?>products/add">ajouter un produit</a></li>
	  <li><a href="<?php echo $this->webroot ?>events/add">ajouter un evenement d'administration</a></li>
	</ul>
      </li>
    <?php endif ?>
  </ul>
