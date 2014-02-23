<?php
App::uses('AppController', 'Controller');
/**
 * EventTypes Controller
 *
 * @property EventType $EventType
 */
class ConfigController extends AppController {
  var $uses = array('Photo', 'Product', 'DatabaseVersion');
  
  var $publicActions = array('upgradeDbStructure','deleteGcalCache','deleteSession','dbBackup', 'setDebug', 'oauth2callback');
  var $memberActions = array();
  
/**
 * index method
 *
 * @return void
 */
 
  public function index() {
    $actions = array(
      'importPhotos' => 'Importer les photos d\'un dossier',
      //'importUsers' => 'Importer les photos d\'un csv',
      'deleteGcalCache' => 'delete gcalendar Cache',
      'importProducts' => 'Importer les produits d\'un csv',
      'setAdmin/1' => 'set to admin',
       'upgradeDbStructure/1' => 'upgrade DBStructure',
      'dbBackup' => 'backup database',
      'setDebug/1' => 'activer debug',
	  'dbBackup/*/true' => 'downloadSql',
      'deleteSession' => 'deleteSession'
    );
    $this->set('actions', $actions);
  }
  
  public function deleteSession() {
      session_destroy();
      $this->redirect($this->referer());
  }


  public function setAdmin($admin)
  {
   $this->Session->write('isAdmin', $admin == 1); 
   $this->redirect(array('action' => 'index'));
  }
  
  public function importProducts()
  {
    $handle = @fopen ( APP.'Model/Datasource/productList.csv', 'r');
    $header = true;
    if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
    if($header) // skip header (first line)
    {
      $header = false;
      continue;
    }
    $this->Product->create();
    $data = array('Product' => array());
    $csv=explode(',',$buffer);
    $data['Product']['name'] = $csv[0];
    $data['Product']['description'] = 'auto import';
    $data['Product']['price'] = $csv[1]+0;
    $data['Product']['product_types_id'] = $csv[2]+0;

    if(!$this->Product->save($data))
    {
      debug($data);
      echo '<div style="background-color:red; color:green;">'.$data['name'].' erreur save</div>';
    }
      }
    }
  }

  public function deleteGcalCache($all = false)
  {
      App::uses('Folder', 'Utility');  
      App::uses('File', 'Utility');

      $cacheFolder = CACHE.'gcalendar/';

      $dir = new Folder($cacheFolder);
      $files = $dir->find('.*\.gcal\.tmp');
      foreach ($files as $file) {
  $filePath = $dir->pwd() . DS . $file;
  $matches = array();
  preg_match ( '/(\d+)_.*\.gcal\.tmp/' , $file, $matches);
  if( time() > $matches[1] + 7*24*60*60 || $all )
  {
    $fileObj = new File($filePath);
    $fileObj->delete();
  }
      }
  }

  public function importPhotos()
  {
    set_time_limit ( 500 );
    $dirPath = APP.'webroot/img/photos';
    
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');
    $dir = new Folder($dirPath);
    $files = $dir->find('.*\.jpg', true);
    debug(    $files);
    foreach( $files as $file )
    {
      debug('===>'.$file);
      $filePath = urlencode( $dirPath.'/'.$file );
      $res = $this->requestAction(
        array('controller' => 'photos', 'action' => 'add'),
        array('pass' => array('filePath' =>   $filePath ))
      );
      $filePath = urldecode($filePath);
      if($res == true)
      {
         debug($filePath.' OK');
      }
      else
      {
         debug($filePath.' NOK');
    }
    }
  }
  
  
   public function setDebug($bool)
  {
    $this->Session->write('debugMode',$bool);
    if($bool)
    {
      $this->Session->setFlash('Debug enabled', 'flash/warning');
    }
    else
    {
      $this->Session->setFlash('Debug disabled','flash/warning');
    }
    $this->redirect($this->referer());
  }
  
  
  public function upgradeDbStructure($redirect = false)
  {
  try{
	$version = $this->DatabaseVersion->find('first');
	}
	catch (Exception $e)
	{
		$version['DatabaseVersion']['version'] = -1;
	}
	if($version['DatabaseVersion']['version'] < Configure::read('databaseVersion'))
	{
		App::uses('ConnectionManager', 'Model'); 
		$sql = '';
		$sql .= 'SET FOREIGN_KEY_CHECKS = 0;
				update shops set event_type_id = NULL;
				drop table if exists events;
				drop table if exists event_types;
				CREATE TABLE IF NOT EXISTS `event_types` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;


				CREATE TABLE IF NOT EXISTS `events` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `event_type_id` int(11) NOT NULL,
				  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  `details` text COLLATE utf8_unicode_ci NOT NULL,
				  `start` datetime NOT NULL,
				  `end` datetime NOT NULL,
				  `all_day` tinyint(1) NOT NULL DEFAULT \'1\',
				  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Scheduled\{,
				  `active` tinyint(1) NOT NULL DEFAULT \'1\',
				  `created` date DEFAULT NULL,
				  `modified` date DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `fk_events_event_type` (`event_type_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
				ALTER TABLE `events`
   ADD CONSTRAINT `fk_events_event_types` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`);
	SET FOREIGN_KEY_CHECKS = 1;';
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery($sql);
		
		$version['DatabaseVersion']['version'] = Configure::read('databaseVersion');
		$this->DatabaseVersion->save($version);
		if($redirect)
		{
			$this->redirect('/');
		}
	}
  }
  

  /**
 * Dumps the MySQL database that this controller's model is attached to.
 * This action will serve the sql file as a download so that the user can save the backup to their local computer.
 *
 * @param string $tables Comma separated list of tables you want to download, or '*' if you want to download them all.
 */
function dbBackup($tables = '*', $download = false) {

    $return = '';

    $modelName = $this->modelClass;

    $dataSource = $this->{$modelName}->getDataSource();
    $databaseName = $dataSource->getSchemaName();


    // Do a short header
    $return .= '-- Database: `' . $databaseName . '`' . "\n";
    $return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";
    $return .= "SET FOREIGN_KEY_CHECKS = 0;\n";


    if ($tables == '*') {
        $tables = array();
        $result = $this->{$modelName}->query('SHOW TABLES');
        foreach($result as $resultKey => $resultValue){
            $tables[] = current($resultValue['TABLE_NAMES']);
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    // Run through all the tables
    foreach ($tables as $table) {
        $tableData = $this->{$modelName}->query('SELECT * FROM ' . $table);

        $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
        $createTableResult = $this->{$modelName}->query('SHOW CREATE TABLE ' . $table);
        $createTableEntry = current(current($createTableResult));
        $return .= "\n\n" . $createTableEntry['Create Table'] . ";\n\n";

        // Output the table data
        foreach($tableData as $tableDataIndex => $tableDataDetails) {

            $return .= 'INSERT INTO ' . $table . ' VALUES(';

            foreach($tableDataDetails[$table] as $dataKey => $dataValue) {

                if(is_null($dataValue)){
                    $escapedDataValue = 'NULL';
                }
                else {
                    // Convert the encoding
                    $escapedDataValue = $dataValue;

                    // Escape any apostrophes using the datasource of the model.
                    $escapedDataValue = $this->{$modelName}->getDataSource()->value($escapedDataValue);
                }

                $tableDataDetails[$table][$dataKey] = $escapedDataValue;
            }
            $return .= implode(',', $tableDataDetails[$table]);

            $return .= ");\n";
        }

        $return .= "\n\n\n";
    }
    $return .= "SET FOREIGN_KEY_CHECKS = 1;\n";

	// utf8
	$return = "\xEF\xBB\xBF".$return;
	
    // Set the default file name
    $fileName = $databaseName . '-backup-' . date('Y-m-d') . '.sql';
	if(!$download)
	{
		file_put_contents ( Configure::read('dbBackupPath').$fileName , $return );
	}
	else
	{
		$this->layout = 'ajax'; 
		$this->response->type('Content-Type: text/x-sql');
		$this->response->download($fileName);
		$this->set('content', $return);
	}
    // Serve the file as a download
    //$this->autoRender = false;
    // $this->response->type('Content-Type: text/x-sql');
   //$this->response->download($fileName);
   // $this->response->body($return);
}


	// public function oauth2callback()
	// {
		// if (isset($_GET['code'])) {
			// App::uses('ConnectionManager', 'Model'); 
			// debug($_GET['code']);
			// $client = ConnectionManager::getDataSource('calendar')->authenticate($_GET['code']);
			// /*$client->authenticate($_GET['code']);
			// if($client->getAccessToken())
			// {
			// }
			// else
			// {
				// debug ('FAIIIIIIIL');
			// }*/
		  // /*$client->authenticate();
		  // $_SESSION['token'] = $client->getAccessToken();
		  // header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);*/
		// }
	// }
  
}
  
?>