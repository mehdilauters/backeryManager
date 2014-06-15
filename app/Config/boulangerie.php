<?php
  
  Configure::write('Settings.Medias.Photos.path', APP.'../../bakeryManagerPhotos/');  

  Configure::write('Settings.Medias.Photos.xPreview', 200);  
  Configure::write('Settings.Medias.Photos.yPreview', 150);  

  Configure::write('Settings.Medias.Photos.xNormal', 800);  
  Configure::write('Settings.Medias.Photos.yNormal', 600);    

    Configure::write('Settings.dbBackupPath', '/tmp/');
    Configure::write('Settings.excelExportPath', '/tmp/');
	
	Configure::write('Settings.dbBackupUrl', 'https://boulangerie-faury.fr/dev/');
	
	Configure::write('Settings.email', array('from' => 
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
	

Configure::write('Settings.Order.pageBreakItemsMax', 20);

Configure::write('Settings.Approximation.order', 4);
Configure::write('Settings.Approximation.bcscale', 10);
Configure::write('Settings.Approximation.nbProjectionsPoint', 0);


Configure::write('Settings.Security.ssl', false);

Configure::write('Settings.demo', array(
  'active' => true,
  'dbPrefix' => 'demo_',
  'User' => array('email'=>'demo@lauters.fr', 'password'=> 'demo')

));


Configure::write('Settings.Excel', array(
  'maxNbRow' => 500,

));

	
	// https://github.com/mehdilauters/bakeryManager/archive/b9a98d18559c5ae6483ebaba9855b9732986182b.zip
	// https://github.com/mehdilauters/bakeryManager/archive/master.zip
	
	Configure::write('Settings.github.downloadUrl', 'https://codeload.github.com/mehdilauters/bakeryManager/');
	Configure::write('Settings.github.apiUrl', 'https://api.github.com/repos/mehdilauters/bakeryManager/');
	//commits
	
?>