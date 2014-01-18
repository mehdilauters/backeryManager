<?php
App::uses('AppController', 'Controller');
/**
 * EventTypes Controller
 *
 * @property EventType $EventType
 */
class ConfigController extends AppController {
  var $uses = array('Photo', 'Product');
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
       'upgradeDbStructure' => 'upgrade DBStructure',
      'dbBackup' => 'backup database'
    );
    $this->set('actions', $actions);
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
  
  
  public function upgradeDbStructure()
  {
    App::uses('ConnectionManager', 'Model'); 
    $sql = '';
    $sql .= 'SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `results_entries`
add `shop_id` int(10) NOT NULL ,
add    `date` datetime NOT NULL,
add   KEY `fk_results_entries_shops` (`shop_id`),
add   CONSTRAINT `fk_results_entries_shops` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`);
update results_entries RE set RE.date=(select R.date from results R where RE.result_id = R.id), RE.shop_id=(select R.shop_id from results R where RE.result_id = R.id);
SET FOREIGN_KEY_CHECKS = 1;';
    $db = ConnectionManager::getDataSource('default');
    $db->rawQuery($sql);
    $this->redirect('/');
  }
  
  /**
 * Dumps the MySQL database that this controller's model is attached to.
 * This action will serve the sql file as a download so that the user can save the backup to their local computer.
 *
 * @param string $tables Comma separated list of tables you want to download, or '*' if you want to download them all.
 */
function dbBackup($tables = '*') {

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

    // Set the default file name
    $fileName = $databaseName . '-backup-' . date('Y-m-d') . '.sql';
  file_put_contents ( Configure::read('dbBackupPath').$fileName , "\xEF\xBB\xBF".$return );
    // Serve the file as a download
    //$this->autoRender = false;
    // $this->response->type('Content-Type: text/x-sql');
   //$this->response->download($fileName);
   // $this->response->body($return);
}
  
}
  
?>