<?php
  
  Configure::write('Medias.Photos.path', WWW_ROOT.'img/photos/');  

  Configure::write('Medias.Photos.xPreview', 200);  
  Configure::write('Medias.Photos.yPreview', 150);  

  Configure::write('Medias.Photos.xNormal', 800);  
  Configure::write('Medias.Photos.yNormal', 600);    

    Configure::write('dbBackupPath', '/tmp/');
    Configure::write('excelExportPath', '/tmp/');
	
	Configure::write('databaseVersion', 6);
	
	Configure::write('dbBackupUrl', 'https://boulangerie-faury.fr/boulangerie/');
	
	Configure::write('email', array('from' => 
					array(
						'email'=>'boulangeriefaury@orange.fr',
						'name'=>'Boulangerie Faury'
						),
			'debug' => array(
								'status' => false,
								'email' => array('boulangeriefaury@orange.fr','mehdilauters@gmail.com')
							),
			
			)
			
  );
	

Configure::write('Order.pageBreakItemsMax', 20);

Configure::write('Approximation.order', 3);
Configure::write('Approximation.bcscale', 10);
Configure::write('Approximation.nbProjectionsPoint', 0);


Configure::write('Security.ssl', false);
	
?>