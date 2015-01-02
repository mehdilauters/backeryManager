<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php if(isset($company['Company'])) { echo $company['Company']['title']; } echo ' | '. $title_for_layout ?>" />
  <?php echo $this->element('tracker'); ?>
  <?php echo $this->Html->charset(); ?>
  <title>
    Boulangerie <?php if(isset($company['Company'])) { echo $company['Company']['title']; } ?> |
    <?php echo $title_for_layout; ?>
  </title>
  <?php
    $id = '';
  if(isset($company['Company'])) { $id = '_'.$company['Company']['id']; }
    echo $this->Html->meta('favicon.ico', '/favicon'.$id.'.ico', array('type' => 'icon'));
	echo $this->html->meta('rss', '/produits.rss', array('title' => "Produits du jour"));
	echo $this->html->meta('rss', '/news.rss', array('title' => "news"));

    //echo $this->Html->css('cake.generic');


	echo $this->Html->script(
    array(
		'jquery-1.9.1.min',
		'jquery-ui-1.10.1.custom.min',
        'fullcalendar.min',
//         'jquery.qtip-1.0.0-rc3.min',
    	'jquery.fancybox.pack',
   		'TableFilter/tablefilter.js',
		'bootstrap.min',
		'intro.js-0.9.0/minified/intro.min.js'
     ),
     array('inline' => 'false')
  );

echo $this->Html->css('bootstrap.min');
  echo $this->Html->css('jquery-ui-timepicker-addon');
echo $this->Html->script('jquery-ui-timepicker-addon');

echo $this->Html->css(
			array(
			    'fullcalendar',
				'introjs.min',
			),
           array('inline' => false)
  );
?>
<!-- <script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script> -->
<?php
    echo $this->Html->script('jquery.ui.datepicker-fr.js');
    echo $this->Html->css('fancy/jquery.fancybox');
    // TODO change to real stylesheet
    echo $this->Html->css('humanity/jquery-ui-1.10.3.custom.min.css');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');


  ?>
  <script>
	var webroot = "<?php echo $this->Html->url('/'); ?>";
	var introSteps = [];
  </script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.canvasOverlay.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/plotTable.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />
<?php    echo $this->Html->css('general'); ?>
</head>
<body>
  <div class="container">
  	<header>
	  	<div class="row">
			  <div class="col-md-8">
			  </div>
			  <div class="col-md-4">
					<div id="logo">
						<h1>
							<a href="<?php echo $this->webroot ?>">Boulangerie</a>
						</h1>
						<?php if(isset($company['Company'])) { echo $company['Company']['title']; } ?>
					</div>
				</div>
	  	</div>
  	</header>

  	<!--  contents -->

  	<div class="row">
		<div class="col-md-9"> <!--  colonne de gauche  -->
			<div id="cookiesError" class="alert alert-danger" style="display:none; ">Attention, les cookies sont désactivés sur votre ordinateur. Cela peut entrainer un mauvais fonctionnement du site.</div>
			<noscript><div id="cookiesError" class="alert alert-danger" >Attention, javascript est désactivé sur votre ordinateur. Cela peut entrainer un mauvais fonctionnement du site.</div></noscript>
			<h2><a href="<?php echo $this->webroot.$this->params['controller'] ?>" ><?php echo $title_for_layout ?></a></h2>
			<div class="rope" ></div>
			<?php if(Configure::read('Settings.demo.active')) { ?>
                              <div class="alert alert-danger" ><div class="title" >Attention, ceci est une version de démonstration</div>Les adresses, numéros de téléphones, et noms d'utilisateurs sont volontairement faux. Les données de ventes et de gestion sont des simulations, et peuvent donc amener à des incohérences.</div>
                          <?php } ?>
			<?php
				echo $this->Session->flash();
				echo $this->Session->flash('auth');
				if(count($receivedEmails) != 0) :
				?>
				<h3>Emails</h3>
				<ul id="emailsPreview">
                                  <?php foreach($receivedEmails as $email): ?>
                                    <li><?php echo $this->element('Emails/Preview', array('email'=>$email)); ?></li>
                                  <?php endforeach; ?>
                                </ul>
                                <?php
                                endif;
				if(count($news) != 0) :
    			?>
    			<h3>Les news</h3>
			    <ul id="newsList">
			    	<?php foreach($news as $new): ?>
				<li><?php echo $this->element('News/Preview', array('news'=>$new)); ?> </li>
				<?php endforeach;?>
		    </ul>
		    <?php endif; ?>
		    <div id="gotoTop" onclick="return gotoTop()" class="gotoScroll alert alert-info" ><a href="#" onclick="return gotoTop()" >Haut</a></div>
		    <?php echo $this->fetch('content'); ?>
		    <div id="gotoBottom" onclick="return gotoBottom()" class="gotoScroll alert alert-info" ><a href="#" onclick="return gotoBottom()" >Bas</a></div>
		</div>
		<div class="col-md-3" id="mainMenu" >  <!--  colonne de droite  -->
		  	<?php echo $this->element('Menu/menu', array('menu'=>$menu)) ?>
		</div>
  	</div>



  	<div id="footer" >
	<div>Credits : <a href="http://www.lauters.fr" >Mehdi Lauters</a> 2014</div>
	<div>BackeryManager released under GPL licence  <a href="https://github.com/mehdilauters/backeryManager" >https://github.com/mehdilauters/backeryManager</a></div>
<div>
  	<?php echo $this->Html->link(
      $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
      'http://www.cakephp.org/',
      array('target' => '_blank', 'escape' => false)
    ); ?>
</div>
  	</div>
  </div>
  <?php //echo $this->element('sql_dump');
  echo $this->Html->script('main');
echo $this->Html->script(
    array(
			'tinymce/tinymce.min.js',
     ),
     array('inline' => 'false')
  );
  ?>
<script type="text/javascript">
tinymce.init({
    selector: "textarea.textEditor"
 });
 var intro;
 function startIntro(){
	var steps = [{intro: "Aucune aide disponible pour cette page"}];
	if( introSteps.length != 0 )
	{
	  steps = introSteps;
	}
        intro = introJs();
          intro.setOptions({
            steps: steps
          });

          intro.start();
      }

function gotoTop()
{
  $('html, body').animate({ scrollTop: 0 }, "slow");
  return false;
}

function gotoBottom()
{
  $('html, body').animate({ scrollTop: $('body').height() }, "slow");
  return false;
}

function hideScrollControls()
{
  $('.gotoScroll').hide('fast')
}

var timeout;
 $( document ).ready( function (){
        $('.table > tbody').each(function (){
        header = $(this).closest('.table').find('thead');

        var i = 1;
        var tbody = $(this);
        tbody.css('max-height',$(window).height()-header.height());
        tbody.find('tr:first td').each(function() {
         th = header.find('th:nth-child('+i+')');
         $(this).width(th.width());
         i++;
        });

        });
        <?php if($isMobile): ?>
          var bodyHeigh = $('body').height();
          var win = $(window).height();

          if (bodyHeigh > win ) {
              $(window).bind("scroll", function (event)
              {
                clearTimeout(timeout);
                  if($(window).scrollTop()<100)
                  {
                    if($(window).scrollTop()> bodyHeigh - win -100 )
                    {
                        $('#gotoBottom').hide('fast');
                    }
                    else
                    {
                      $('#gotoBottom').show('fast');
                    }
                    $('#gotoTop').hide('fast');
                  }
                  else
                  {
                    if($(window).scrollTop()> bodyHeigh - win -100 )
                    {
                        $('#gotoBottom').hide('fast');
                    }
                    else
                    {
                      $('#gotoBottom').show('fast');
                    }

                    $('#gotoTop').show('fast');
                  }

                  timeout = setTimeout(hideScrollControls, 2000);
              });
          }
	<?php endif;
          if($introAutostart): ?>
	  if( introSteps.length != 0 )
	  {
		  startIntro();
	  }
	<?php endif; ?>
 });


</script>

</body>
</html>