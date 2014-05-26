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
  <?php echo $this->element('tracker'); ?>
  <?php echo $this->Html->charset(); ?>
  <title>
    Boulangerie Faury | 
    <?php echo $title_for_layout; ?>
  </title>
  <?php
    echo $this->Html->meta('icon');
	echo $this->html->meta('rss', '/produits.rss', array('title' => "Produits du jour"));
	echo $this->html->meta('rss', '/news.rss', array('title' => "news"));

    //echo $this->Html->css('cake.generic');
    

	echo $this->Html->script(
    array(
		'jquery-1.9.1.min',
		'jquery-ui-1.10.1.custom.min',
        'fullcalendar.min',
        'jquery.qtip-1.0.0-rc3.min',
    	'jquery.fancybox.pack',
   		'TableFilter/tablefilter.js',
'bootstrap.min',
     ),
     array('inline' => 'false')
  );

echo $this->Html->css('bootstrap.min');
  echo $this->Html->css('jquery-ui-timepicker-addon');
echo $this->Html->script('jquery-ui-timepicker-addon');

echo $this->Html->css(
          'fullcalendar',
           null,
           array('inline' => false)
  );
?>
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<?php
    echo $this->Html->script('jquery.ui.datepicker-fr.js');
    echo $this->Html->css('fancy/jquery.fancybox');
    // TODO change to real stylesheet
    echo $this->Html->css('humanity/jquery-ui-1.10.3.custom.min.css');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
	
	
  ?>
  <script>var webroot = "<?php echo $this->Html->url('/'); ?>";</script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
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
							<a href="<?php echo $this->webroot ?>">Boulangerie Faury</a>
						</h1>
						Christiane &amp; Thierry FAURY
					</div>
				</div>
	  	</div>
  	</header>
  	
  	<!--  contents -->
  	
  	<div class="row">
		<div class="col-md-9"> <!--  colonne de gauche  -->
			<h2><?php echo $title_for_layout ?></h2>
			
			<?php 
				echo $this->Session->flash();
				//echo $this->Session->flash('flash/ok');
				//echo $this->Session->flash('flash/warning');
				//echo $this->Session->flash('flash/fail');
				echo $this->Session->flash('auth',  'flash/ok');
				if(count($news) != 0) :
    			?>
    			
    			<h3>Les news</h3>
			    <ul id="newsList">
			    	<?php foreach($news as $new): ?>
				<li><?php echo $this->element('News/Preview', array('news'=>$new)); ?> </li>
				<?php endforeach;?>
		    </ul>
		    <?php endif; ?>
		    <?php echo $this->fetch('content'); ?>
		</div>
		<div class="col-md-3">  <!--  colonne de droite  -->
		  	<?php echo $this->element('Menu/menu', array('menu'=>$menu)) ?>
		</div>
  	</div>
 
    
    
  	<footer>
	<div>Credits : <href="http://www.lauters.fr" />Mehdi Lauters 2014</div>
	<div>BackeryManager released under GPL licence  <href="https://github.com/mehdilauters/backeryManager" />https://github.com/mehdilauters/backeryManager</div>
<div>
  	<?php echo $this->Html->link(
      $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
      'http://www.cakephp.org/',
      array('target' => '_blank', 'escape' => false)
    ); ?>
</div>
  	</footer>
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
</script>
	
</body>
</html>