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
      'upgradeDbStructure' => 'upgrade DBStructure'
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
    $sql = 'ALTER TABLE `sales` ADD `lost` INT( 10 ) AFTER `produced` ;
    update `sales` set lost = (produced-sold);
    ALTER TABLE `sales` DROP `sold`; ';
    $db = ConnectionManager::getDataSource('default');
    $db->rawQuery($sql);
    $this->redirect('/');
  }
  
}
  
?>