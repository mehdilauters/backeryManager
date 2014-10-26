<?php
App::uses('AppController', 'Controller');


/**
*
*	Config controller
*
*/

class ConfigController extends AppController {
  var $uses = array('Photo', 'Product', 'DatabaseVersion','Sale', 'Result', 'ResultsEntry', 'OrderedItem','User', 'Shop', 'RemoteDB', 'Company');
  
  var $publicActions = array('upgradeDbStructure','deleteSession'/*,'dbBackup'*/, 'setDebug'/*, 'demoBaseSql', 'emailTest'*/, 'noSSL', 'initAcl' );
  var $memberActions = array();
  
/**
 * index method
 *
 * @return void
 */
 
  public function index() {
    $actions = array(
//       'importPhotos' => 'Importer les photos d\'un dossier',
      //'importUsers' => 'Importer les photos d\'un csv',
//       'importProducts' => 'Importer les produits d\'un csv',
      'initAcl' => 'initAcl',
      'setAdmin/1' => 'set to admin',
      'setCompany/id' => 'set to company id',
      'upgradeDbStructure/1' => 'upgrade DBStructure',
      'dbBackup/0' => 'backup database',
      'setDebug/1' => 'activer debug',
      'dbBackup/0/1' => 'downloadSql',
      'dbBackup/1/1' => 'download Demo Sql',
      'deleteSession' => 'deleteSession',
      'emailTest' => 'Test email',
      'phpInfo' => 'phpInfo',
      'console' => 'console',
      'noSSL' => 'noSSL',
      'importDb' => 'import db'
    );
    $this->set('actions', $actions);
	
	

	
  }
  
  public function phpInfo()
  {
  }
  

  public function initAcl()
  {
/*
 truncate aros_acos;
truncate aros;
truncate acos;
*/

    $aro = $this->Acl->Aro;
    $groups = array();
    $groups[] = array('alias' => 'Members');
    $groups[] = array('alias' => 'Administrators', 'parent_id' => 1);
    $groups[] = array('alias' => 'Root', 'parent_id' => 2);
  
    foreach($groups as $data)
    {
      $aro->create();
      $aro->save($data);
    }


    $aros = array();
    $users = $this->User->find('all');
    foreach($users as $user)
    {
      $parentId = 1; // members
      if($user['User']['isRoot'])
      {
	 $parentId = 2;
      }
      $aros[] = array('alias' => $user['User']['name'],
		  'parent_id' => $parentId,
		  'model' => 'User',
		  'foreign_key' => $user['User']['id'],
		  );
    }

    foreach($aros as $aroData)
    {
      $aro->create();
      $aro->save($aroData);
    }

    $aco = $this->Acl->Aco;
    $acos = array();
    $acos[] = array('alias' => 'memberActions');
    $acos[] = array('alias' => 'administratorActions');
    $acos[] = array('alias' => 'rootActions');
    foreach($acos as $acoData)
    {
      $aco->create();
      $aco->save($acoData);
    }




    $this->Acl->allow('Members', 'memberActions');
    $this->Acl->allow('Members/Administrators', 'administratorActions');
    $this->Acl->allow('Members/Administrators/Root', 'rootActions');



//     debug($this->Acl->check('Members/Administrators', 'administratorActions'));
//     debug($this->Acl->check(array('model' => 'User', 'foreign_key'=>$this->Auth->user('id')), 'administratorActions'));
    $this->redirect($this->referer());
  }

  public function console()
  {
	$output = '';
  	if ($this->request->is('post')) {
		chdir('/tmp');
		$output = shell_exec($this->request->data['Console']['commands']);
	}
	$this->set('output', $output);
  }
  
  public function emailTest()
  {
    if ($this->request->is('post')) {
	$mail = array(
				'email' => $this->request->data['User']['email'],
				'subject' => 'TestMail '.date('d/m/Y'),
				'view' => 'test'
				);
	$this->sendMail($mail);
    }
	
  }
  
  public function demoBaseSql()
  {
		$sinCoef = 1;
	$tablePrefix = Configure::read('Settings.demo.dbPrefix');
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
		$sql .= 'update '.$tablePrefix.'sales Sale set 
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

		$sql .= 'update '.$tablePrefix.'results Result set 
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


		$sql .= 'update '.$tablePrefix.'results_entries ResultsEntry set 
			result = '.$resultMapping.";\n";
			
			
			//////////////////////////////////////////////:
	$ordered_item = $this->OrderedItem->find('first', array(
			'fields' => array(
					'max( OrderedItem.quantity ) as maxItem',
					'min( OrderedItem.quantity ) as minItem',
				),
		));

	$sin = 'abs('.$sinCoef.' * sin(OrderedItem.id) )';

		$minItem = $ordered_item[0]['minItem'];
		$maxItem = $ordered_item[0]['maxItem'];
		

		
		if($minItem < 0)
		{
			$minItem = 0;
		}
		
		$rangeItem = $maxItem - $minItem;
		
		$newMaxItem = 1000;
		
		
	$quantityMapping  	= 'round( abs( (quantity  - '.$minItem.') / '.$rangeItem.' * '.$newMaxItem.' * '.$sin.' ) )';


		$sql .= 'update '.$tablePrefix.'ordered_items OrderedItem set 
			quantity = '.$quantityMapping.";\n";
			
	
	

// change custommers name
$row = 0;
$names = array();
if (($handle = fopen(APP."Model/Datasource/names.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	if($row != 0)
	{
	  $names[] = $data[0];
	  $names[] = $data[1];
	}
        $row++;
//         for ($c=0; $c < $num; $c++) {
//             echo $data[$c] . "<br />\n";
//         }
    }
    fclose($handle);
    $users = $this->User->find('all');
    $nbMaxNames = count($names) -1;
    foreach($users as $user)
    {
	$name = strtolower($names[rand(0, $nbMaxNames)]);
	$sql .= 'update '.$tablePrefix.'users set address=\'35 Rue Lakanal 31000 Bordeaux\', phone=\'0656763875\', name=\''.$name.'\', email = \''.$name.'@lauters.fr\', password=\''.AuthComponent::password($name).'\' where id = '.$user['User']['id'].";\n";
    }
}

    $shops = $this->Shop->find('all');
    $i = 1;
    foreach($shops as $shop)
    {
	$name = 'magasin #'.$i;
	$sql .= 'update '.$tablePrefix.'shops set address=\'35 Rue Lakanal 31000 Bordeaux\', phone=\'0656763875\', name=\''.$name.'\', description = \'Magasin demo #'.$i.'\' where id = '.$shop['Shop']['id'].";\n";
	$i++;
    }


		 $sql .= 'update '.$tablePrefix.'companies set address=\'35 Rue Lakanal 31000 Bordeaux\', email=\'demo@lauters.fr\', phone=\'0656763875\', capital=\'7000\', siret=\'91919191919191\', name=\'SARL Demo\', title=\'Démo\';'."\n";

		// add demo user
		$sql .= 'insert into '.$tablePrefix.'users (email, name, password, company_id) values (\''.Configure::read('Settings.demo.User.email').'\', \'demo\', \''.AuthComponent::password(Configure::read('demo.User.password'))."', ".$this->getCompanyId().");\n";

		
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


public function setCompany($id = NULL)
{
  if ($this->request->is('post') && $id == NULL)
  {
    $id = $this->request->data['Company']['company_id'];
  }
  if($id != NULL)
  {
    if (!$this->Company->exists($id)) {
	    throw new NotFoundException(__('Invalid company'));
    }
    $this->Session->setFlash('Company set to #'.$id, 'flash/warning');
    $this->Session->write('companyId',$id);
    return $this->redirect('/');
  }
  $companies = $this->Company->find('list');
  $this->set(compact('companies'));
}


   public function noSSL($bool)
  {
    $this->Session->write('noSSL',$bool);
    if($bool)
    {
      $this->Session->setFlash('ssl disabled', 'flash/warning');
      $this->log('ssl disableb by '.$this->request->clientIp(), 'debug');
    }
    else
    {
      $this->Session->setFlash('ssl enabled','flash/warning');
      $this->log('ssl enabled by '.$this->request->clientIp(), 'debug');
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
	if($version['DatabaseVersion']['version'] < Configure::read('Settings.databaseVersion'))
	{
		$this->log('upgrade db from version '.$version['DatabaseVersion']['version'].' to '.Configure::read('Settings.databaseVersion'), 'debug');
		App::uses('ConnectionManager', 'Model'); 
		$sql = '';
		$sql .= '
SET FOREIGN_KEY_CHECKS = 0;






alter table companies add
  `domain_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null,
add  
`order_legals_mentions` text CHARACTER SET utf8 COLLATE utf8_bin;

alter table orders drop order_legals_mentions;






alter table companies add
  `domain_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null,
add  
`order_legals_mentions` text CHARACTER SET utf8 COLLATE utf8_bin;

alter table drop order_legals_mentions;


alter table events add
  `internal` tinyint(1) NOT NULL DEFAULT \'0\';

alter table shops add
  `event_type_closed_id` int(10);

SET FOREIGN_KEY_CHECKS = 1;
';	
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery($sql);
		
		$version['DatabaseVersion']['version'] = Configure::read('Settings.databaseVersion');
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
    $modelName = $this->modelClass;

    $dataSource = $this->{$modelName}->getDataSource();
    $databaseName = $dataSource->getSchemaName();
    $return = $dataSource->backup($demo, $tables);    

	if($demo)
	{
		$return .= $this->demoBaseSql() . "\n" ;
	}
	// utf8
	//$return = "\xEF\xBB\xBF".$return;
	
    // Set the default file name
    $fileName = $databaseName . '-backup-' . date('Y-m-d') . '.sql';
	if(!$download)
	{
		file_put_contents ( Configure::read('Settings.dbBackupPath').$fileName , $return );
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

  public function importDb()
  {
    if(!Configure::read('Settings.demo.active'))
    {
      $this->Session->setFlash('This is not a demo version!', 'flash/warning');
      $this->redirect(array('action' => 'index'));
      return;
    }
      if ($this->request->is('post')) {
	  $demo = true;
// 	  if($this->params['noDemo'])
// 	  {
// 		  $demo = false;
// 	  }
	  
	  $login = $this->request->data['User']['email'];
	  $password = $this->request->data['User']['password'];

	  $this->RemoteDB->login($login, $password);
	  

	  debug('Fteching...');
	  $sql = $this->RemoteDB->download($demo);
	  debug('Done');
	  debug('Applying to database');
	  $this->RemoteDB->applyToDB($sql);
	  debug('Done');
      }
  }

  
}
  
?>