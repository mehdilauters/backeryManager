<?php 
	require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');
	spl_autoload_register('DOMPDF_autoload');
	$dompdf = new DOMPDF();
	$dompdf->set_paper = 'A4';
	
	$path = APP.'webroot/css/generalPdf.css';
	$css = '<style>'.file_get_contents($path).'</style>';
	
	$dompdf->load_html($css.$content_for_layout, Configure::read('App.encoding'));
	$dompdf->render();
	echo $dompdf->output($title_for_layout);
?>