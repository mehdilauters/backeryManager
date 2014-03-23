<?php
App::uses('AppController', 'Controller');


/**
*
*	Config controller
*
*/

class ConfigController extends AppController {
  var $uses = array('Photo', 'Product', 'DatabaseVersion','Sale', 'Result', 'ResultsEntry');
  
  var $publicActions = array('upgradeDbStructure','deleteGcalCache','deleteSession','dbBackup', 'setDebug', 'demoBaseSql' );
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
      'dbBackup/false' => 'backup database',
      'setDebug/1' => 'activer debug',
      'dbBackup/false/true' => 'downloadSql',
	  'dbBackup/true/true' => 'download Demo Sql',
      'deleteSession' => 'deleteSession'
    );
    $this->set('actions', $actions);
	
	

	
  }
  
  
  public function demoBaseSql()
  {
		$sinCoef = 1;
	
	$sin = 'abs('.$sinCoef.' * sin(Sale.id) )';

	$sale = $this->Sale->find('first', array(
		'fields' => array(
				'max( Sale.produced ) as maxProduced',
				'min( Sale.produced ) as minProduced',
				'max( Sale.lost ) as maxLost',
				'min( Sale.lost ) as minLost',
			),
	));
	$minProduced = $sale[0]['minProduced'];
	$maxProduced = $sale[0]['maxProduced'];
	
	$minLost = $sale[0]['minLost'];
	$maxLost = $sale[0]['maxLost'];
	
	if($minLost < 0)
	{
		$minLost = 0;
	}
	
	if($minProduced < 0)
	{
		$minProduced = 0;
	}
	
	$rangeLost = $maxLost - $minLost;
	$rangeProduced = $maxProduced - $minProduced;
	
	$percentLost = $maxLost / $maxProduced;
	$newMaxProduced = 1000;
	$newMaxLost = $newMaxProduced * $percentLost;
		
		
	$producedMapping  	= '(produced  - '.$minProduced.') / '.$rangeProduced.' * '.$newMaxProduced.' * '.$sin;
	$lostMapping  		= '(lost - '.$minLost.') / '.$rangeLost.' *'.$newMaxLost.' * '.$sin;


		
		$sql = '';
		$sql .= 'update sales Sale set 
			produced = '.$producedMapping.',
			lost = '.$lostMapping.";\n";


	/////////////////////////////////////////////
	$result = $this->Result->find('first', array(
			'fields' => array(
					'max( Result.cash ) as maxCash',
					'min( Result.cash ) as minCash',
					'max( Result.check ) as maxCheck',
					'min( Result.check ) as minCheck',
				),
		));

	$sin = 'abs('.$sinCoef.' * sin(Result.id) )';

	$minCash = $result[0]['minCash'];
	$maxCash = $result[0]['maxCash'];
	
	$minCheck = $result[0]['minCheck'];
	$maxCheck = $result[0]['maxCheck'];
	
	if($minCheck < 0)
	{
		$minCheck = 0;
	}
	
	if($minCash < 0)
	{
		$minCash = 0;
	}
	
	$rangeCheck = $maxCheck - $minCheck;
	$rangeCash = $maxCash - $minCash;
	
	$percentCheck = $maxCheck / $maxCash;
	$newMaxCash = 1000;
	$newMaxCheck = $newMaxCash * $percentCheck;
	
	
	$cashMapping  	= '(cash  - '.$minCash.') / '.$rangeCash.' * '.$newMaxCash.' * '.$sin;
	$checkMapping  		= '(`check` - '.$minCheck.') / '.$rangeCheck.' *'.$newMaxCheck.' * '.$sin;

		$sql .= 'update results Result set 
			cash = '.$cashMapping.',
			`check` = '.$checkMapping.";\n";

	//////////////////////////////////////////////:
	$result_entry = $this->ResultsEntry->find('first', array(
			'fields' => array(
					'max( ResultsEntry.result ) as maxResult',
					'min( ResultsEntry.result ) as minResult',
				),
		));

	$sin = 'abs('.$sinCoef.' * sin(ResultsEntry.id) )';

		$minResult = $result_entry[0]['minResult'];
		$maxResult = $result_entry[0]['maxResult'];
		

		
		if($minResult < 0)
		{
			$minResult = 0;
		}
		
		$rangeResult = $maxResult - $minResult;
		
		$newMaxResult = 1000;
		
		
	$resultMapping  	= '(result  - '.$minResult.') / '.$rangeResult.' * '.$newMaxResult.' * '.$sin;


		$sql .= 'update results_entries ResultsEntry set 
			result = '.$resultMapping.";\n";

		// add demo user
		$sql .= 'insert into users (email, password) values (\'demo@demo.fr\', \''.AuthComponent::password('demo')."\');\n";

		
		return $sql;
	}
  
  public function deleteSession() {
      session_destroy();
      $this->redirect($this->referer());
  }


  public function setAdmin($admin)
  {
	$this->log('config: setAdmin from ip '.$this->request->clientIp(), 'debug');
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
		$this->log('upgrade db from version '.$version['DatabaseVersion']['version'].' to '.Configure::read('databaseVersion'), 'debug');
		App::uses('ConnectionManager', 'Model'); 
		$sql = '';
		$sql .= '


truncate table ordered_items;
truncate table orders;

alter table users add `discount` float(3) default 0 ;
alter table orders add `discount` float(3) default 0;



';	
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery($sql);
		
		$version['DatabaseVersion']['version'] = Configure::read('databaseVersion');
		$this->DatabaseVersion->save($version);
		if($redirect)
		{
			$this->redirect('/');
		}
	}
    {
      debug('already up to date');
    }
    $this->render('index');
  }
  

  /**
 * Dumps the MySQL database that this controller's model is attached to.
 * This action will serve the sql file as a download so that the user can save the backup to their local computer.
 *
 * @param string $tables Comma separated list of tables you want to download, or '*' if you want to download them all.
 */
function dbBackup($demo = true, $download = false, $tables = '*') {
	header('Content-Type: text/html; charset=utf-8');
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
	if($demo)
	{
		$return .= $this->demoBaseSql() . "\n" ;
	}
    $return .= "SET FOREIGN_KEY_CHECKS = 1;\n";

	// utf8
	//$return = "\xEF\xBB\xBF".$return;
	
    // Set the default file name
    $fileName = $databaseName . '-backup-' . date('Y-m-d') . '.sql';
	if(!$download)
	{
		file_put_contents ( Configure::read('dbBackupPath').$fileName , $return );
	}
	else
	{
		$this->log('downloading database ( demo = '.$demo.' ) from ip '.$this->request->clientIp(), 'debug');
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


  
}
  
?>