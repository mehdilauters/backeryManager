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
 * @package       Cake.View.Layouts.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<?php $path = APP.'webroot/css/generalEmail.css';
	$css = '<style>'.file_get_contents($path).'</style>';
	echo str_replace('WEBROOT', $this->Html->url('/', true), $css);
 ?>


	<title><?php echo $title_for_layout;?></title>
</head>
<body>
  <div id="container" >
    <header>
<!--	  <div id="logo">
		  <h1>
			  <a href="<?php echo $this->webroot ?>">Boulangerie</a>
		  </h1>
		  <?php if(isset($company['Company'])) { echo $company['Company']['title']; } ?>
	  </div>-->
    </header>
	  <?php echo $this->fetch('content');?>

	  <div id="footer" >
		<span>Editée le <?php echo date('d/m/Y H:i'); ?></span><br/>
		<a href="<?php echo $this->Html->url('/', true); ?>" ><?php echo $this->Html->url('/', true); ?></a>
	  </div>
  </div>
</body>
</html>