<?php
  
  Configure::write('Medias.Photos.path', WWW_ROOT.'img/photos/');  

  Configure::write('Medias.Photos.xPreview', 200);  
  Configure::write('Medias.Photos.yPreview', 150);  

  Configure::write('Medias.Photos.xNormal', 800);  
  Configure::write('Medias.Photos.yNormal', 600);    

//Configure::write('dbBackupPath', 'C:\\Users\\Pc\\Dropbox\\parents\\backup_db\\');
//Configure::write('excelExportPath', 'C:\\Users\\Pc\\Dropbox\\parents\\backup_db\\');
    Configure::write('dbBackupPath', '/tmp/');
    Configure::write('excelExportPath', '/tmp/');
	
	Configure::write('databaseVersion', 6);
	
	Configure::write('dbBackupUrl', 'boulangerie-faury.fr/boulangerie/config/dbBackup/');
	//Configure::write('dbBackupUrl', 'localhost/backery/Boulangerie/config/dbBackup/');
	
	Configure::write('email', array('from' => 
					array(
						'email'=>'boulangeriefaury@orange.fr',
						'name'=>'Boulangerie Faury'
						),
			'debug' => array(
								'status' => true,
								'email' => array('boulangeriefaury@orange.fr','mehdilauters@gmail.com')
							),
			
			)
			
  );
	

Configure::write('Order.pageBreakItemsMax', 20);
	
?>