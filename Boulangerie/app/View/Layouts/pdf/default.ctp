<?php 
	require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');
	spl_autoload_register('DOMPDF_autoload');
	$dompdf = new DOMPDF();
	$dompdf->set_paper = 'A4';
	
	$path = APP.'webroot/css/generalPdf.css';
	$css = '<style>'.file_get_contents($path).'</style>';
	
	$dompdf->load_html($css.$content_for_layout, Configure::read('App.encoding'));
// 	$font = Font_Metrics::get_font("helvetica", "bold");
//         $pdf->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
	$dompdf->render();
	echo $dompdf->output($title_for_layout);
?>