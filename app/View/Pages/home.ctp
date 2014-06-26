<script>


		
$(document).ready(function() {

});


</script>
<?php
if($tokens['isAdmin'])
{	?>
	<div id="globalResults" >
		<?php echo $this->element('Results/stats/results', array('results'=>$results, 'config'=>array('interactive'=>false))); ?>
	</div>
	<?php
}

 ?>
 
<?php //debug($eventType); 
if(count($eventType['Event']) != 0)
{
  ?>
  <ul id="newsList" >
  <?php
  foreach($eventType['Event'] as $event)
  {
  ?>
  <li>
  <?php
	  // debug($event);
	  $isToday = $this->Dates->isToday($event);
	  if($isToday)
	  {
		  ?>
		  <h3><?php echo $event['Gevent']['title'] ?></h3>
		  <p><?php echo $event['Gevent']['description'] ?></p>
		  <?php
		  // debug($event);
	  }
		  ?>
  </li>
  <?php
  }
  ?>
</ul>
<?php
}


?>
<?php $slideshowInserted = false; ?>
  <ul id="shopPreviewList">
    <?php foreach($shops as $shop) { ?>
    <li class="shop">
     <?php   echo $this->element('Shops/Preview', array('shop'=>$shop)); ?>
    </li>
    <?php
if(!$slideshowInserted)
{ 
  $slideshowInserted = true;
  ?>
   <li>
      <center>
	  	  <?php 
		if($daysProduct)
		{
			$text = 'Produits du Jour';
		}
		else
		{
			$text = 'Nos Produits';
		}
	  ?>
	  <h3><?php echo $text ?></h3>
      <div id="slideshow" class="slideshow" >
	<ul class="slides" id="homeSlideshow">
	  <?php foreach($products as $product) { ?>
	    <li class="slide"><a href="<?php echo $this->webroot.'products/view/'.$product['Product']['id'] ?>" >
			<img src="<?php echo $this->webroot.'photos/download/'.$product['Media']['id'].'/0'.$this->MyHtml->getLinkTitle($product['Media']['name']) ?>" alt="<?php echo $product['Media']['name'] ?>" width="400" height="300" />
			</a>
	    <div class="slide-title slate"><a href="<?php echo $this->webroot.'products/view/'.$product['Product']['id'] ?>" ><?php echo $product['Product']['name'] ?></a></div>
	    </li>
	  <?php } ?>
	</ul>
    </div>
    </center>
   </li>
<?php
}

 } ?>
  </ul>
<script>

      introSteps = [
              { 
                intro: 'Bienvenue dans la version de démonstration de <b>BakeryManager</b><br/>Vous êtes maintenant dans la peau d\'un boulanger ayant récemment ouvert un dépot de pain et visitant la page principale de son outil <br/> <a href="mailto:mehdilauters@gmail.com" >Mehdi Lauters</a>'
              },
              {
                element: '#mainMenu',
                intro: "Sur la droite se touve la barre de menu",
				position: 'left'
              },
/*              {
                element: document.querySelectorAll('#step2')[0],
                intro: "Ok, wasn't that fun?",
                position: 'right'
              },*/
              {
                element: '#menu',
                intro: 'Ce menu est accessible à tous les visiteurs',
                position: 'left'
              },
              {
                element: '#menuAdmin',
                intro: "Ce menu est quant à lui accessible seulement aux gérants de la boulangerie",
                position: 'left'
              },
              {
                element: '#helpLink',
                intro: "A tout moment, retrouvez cette aide en cliquant ici",
                position: 'left'
              },
              {
                element: '#globalResults',
                intro: 'Ici sont présentés les courbes des résultats comptable, par magasin',
				position: 'right'
              }
			  ,
              {
				element: '#controlChart_resultsChart',
                intro: 'Cochez / décochez les cases pour afficher / masquer les courbes',
				position: 'right'
              },
              {
				element: '#shopPreview_1',
                intro: 'Ensuite sont disponibles un apercu des magasins',
				position: 'right'
              },
              {
				element: '#shopStatus_1',
                intro: 'Dont son statut (ouvert/fermé)',
				position: 'right'
              },
			  {
				element: '#timetable_1',
                intro: 'Et les horaires complètes',
				position: 'right'
              },
			  {
				element: 'slideshow',
                intro: 'Sont présentés ici les produits du jours',
				position: 'right'
              },
			  {
				element: '#logout',
                intro: 'Pour passer en vue "Client", déconnectez vous, vous pourrez ensuite vous reconnecter si besoin',
				position: 'left'
              },
			  
			  {
				element: '#dailyProductionLink',
                intro: 'Vous pourrez ensuite vous rendre sur la page de saisie journalière des ventes',
				position: 'left'
              },
			  
            ];

</script>