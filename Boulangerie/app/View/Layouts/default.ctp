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
  <?php echo $this->Html->charset(); ?>
  <title>
    Boulangerie Faury | 
    <?php echo $title_for_layout; ?>
  </title>
  <?php
    echo $this->Html->meta('icon');

    //echo $this->Html->css('cake.generic');
    echo $this->Html->css('general');

echo $this->Html->script(
    array(   'jquery-1.9.1.min',
        'jquery-ui-1.10.1.custom.min',
         'fullcalendar.min',
         'jquery.qtip-1.0.0-rc3.min',
    'jquery.fancybox.pack',
    'TableFilter/tablefilter.js'
        ),
     array('inline' => 'false')
  );
echo $this->Html->css(
          'fullcalendar',
           null,
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
    echo $this->Html->script('main');
  ?>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/plotTable.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />
</head>
<body>
  <div id="container">
    <div id="header">
    </div>
    <div id="contentBox" >
      <div id="contentHeader" >
  <h2>&nbsp;<?php echo $title_for_layout ?></h2>
    </div>
    <div class="pannel" id="mainPannel" >
  <h2>Boulangerie Faury</h2>
  Christiane &amp; Thierry FAURY
    </div>
<?php echo $this->element('Menu/menu', array('menu'=>$menu)) ?>
      <div id="content">
      &nbsp;
  <?php echo $this->Session->flash();
		echo $this->Session->flash('auth');  ?>

  <?php echo $this->fetch('content'); ?>
      </div>
  <div class="clear" ></div>
      <div id="footer">
  <a href="<?php echo $this->webroot ?>config/setAdmin/1" >Admin</a>
  <?php echo $this->Html->link(
      $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
      'http://www.cakephp.org/',
      array('target' => '_blank', 'escape' => false)
    );
  ?>
      </div>
  </div>
  </div>
  <?php echo $this->element('sql_dump'); ?>
</body>
</html>
