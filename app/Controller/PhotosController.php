<?php
App::uses('AppController', 'Controller');
/**
 * Photos Controller
 *
 * @property Photo $Photo
 */
class PhotosController extends AppController {

  var $publicActions = array('download');
  var $administratorActions = array('*');


  var $uses = array('Photo', 'Media', 'Company');



/**
 * index method
 *
 * @return void
 */
  public function index() {
    //$this->Photo->recursive = 0;
    $this->Photo->contain('Media.User');
    $this->set('photos', $this->paginate(array('Media.user_id in ( select U.'.$this->Photo->Media->User->primaryKey.' from '.$this->Photo->Media->User->table.' U where U.company_id = '.$this->getCompanyId().')')));
  }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->Photo->exists($id)) {
      throw new NotFoundException(__('Invalid photo'));
    }
    $this->Photo->contain('Media.User');
    $options = array('conditions' => array('Photo.' . $this->Photo->primaryKey => $id));
    $photo = $this->Photo->find('first', $options);
    if($photo['Photo']['User']['company_id'] != $this->getCompanyId() )
    {
      throw new NotFoundException(__('Invalid photo for this company'));
    }    


    $this->set('photo', $photo);
  }

  function download($id, $preview = true)
	{
		if (!$this->Photo->exists($id)) {
		  throw new NotFoundException(__('Invalid photo'));
		}
		
		$this->Photo->contain('Media.User');
		$photo=$this->Photo->findById($id);
		if($photo['Photo']['User']['company_id'] != $this->getCompanyId() )
		{
		  throw new NotFoundException(__('Invalid photo for this company'));
		}    
		$tokens = $this->getUserTokens();
		if(! $tokens['isAdmin'] )
		{
		  $isRib = ($this->Company->find('count', array('conditions'=>array('Company.rib' => $id))) == 0);
		  if (!$isRib) {
		    $this->log('trying to access rib from ip '.$this->request->clientIp(),'debug');
		    throw new NotFoundException(__('Invalid photo'));
		  }
		}

		$this->viewClass = 'Media';
		if($preview)
			$path='preview/';
		else
			$path='normal/';
		
		$path_parts = pathinfo($photo['Photo']['path']);
 		$params = array(
              'id' => $photo['Photo']['path'],
              'name' => $photo['Photo']['name'],
              'download' => true,
              'extension' => $path_parts['extension'],
              'path' => Configure::read('Settings.Medias.Photos.path').$path
       );
       $this->set($params);
    $filePath = Configure::read('Settings.Medias.Photos.path').$path.$photo['Photo']['path'];
    if( ! file_exists($filePath) )
    {
	$this->log('File '.$filePath.' doesn\'t exists for photo '.$photo['Photo']['id'], 'debug');
	throw new NotFoundException(__('Invalid file'));
    }
    $modified = filemtime($filePath);
    $this->response->modified($modified);
    if ($this->response->checkNotModified($this->request))
    {
      $this->autoRender = false;
      $this->response->send();
    }
    else
    {
      $params['cache'] = '+1 month';
      $params['modified'] = '@' . $modified; // Must be a string to work. See MediaView->render()
      $this->set($params);
    }
	}

  
  public function upload()
  {
  
    if (isset($this->request->data['Photo']))
    {
      if( $this->request->data['Photo']['upload']['size'] == 0 )
      {
        $this->Photo->invalidateField('upload','Please check the size of your image');
	$this->log("size too big", 'debug');
        return false;
      }
      if( !$this->Photo->checkType($this->request->data['Photo']['upload']['type']) )
      {
        $this->Photo->invalidateField('upload','Veuillez fournir une image .png, .jpg');

	$this->log("image type not valid", 'debug');
        return false;
      }
      if(!is_uploaded_file($this->request->data['Photo']['upload']['tmp_name']))
      {
	$this->log("error is_uploaded_file", 'debug');
        $this->Photo->invalidateField('upload','Erreur lors de l\'upload');
        return false;
      }
    }
  
    return true;
  }
  
  
  function saveFile($filePath = null)
  {
    if(empty($this->request->data['Photo']['upload']['name']) && $filePath == null)
    {
      return false;
    }
    
    if( ! empty($this->request->data['Photo']['upload']['name']) )
    {
      $filePath = $this->request->data['Photo']['upload']['tmp_name'];
      $filename = $this->request->data['Photo']['upload']['name'];
    }
    else
    {
        $filename = basename($filePath);
    }
      $photoPathNormal =   Configure::read('Settings.Medias.Photos.path').'normal/';
      $photoPathPreview = Configure::read('Settings.Medias.Photos.path').'preview/';
      if(!is_dir($photoPathNormal))
      {
	mkdir($photoPathNormal, 0755, true);
      }
      if(!is_dir($photoPathPreview))
      {
	mkdir($photoPathPreview, 0755, true);
      }
      $xPreview = Configure::read('Settings.Medias.Photos.xPreview');  
      $yPreview = Configure::read('Settings.Medias.Photos.yPreview');
    
      $xNormal = Configure::read('Settings.Medias.Photos.xNormal');  
      $yNormal = Configure::read('Settings.Medias.Photos.yNormal');
    
    $path_parts = pathinfo($filename);
    $filename = $this->Photo->getRandomName().'.'.strtolower($path_parts['extension']);
    
    if(!empty($this->request->data['Photo']['upload']['name']) )
     {
       debug($filePath);
	$dest = $photoPathNormal.$filename;
       if(move_uploaded_file($filePath,$dest) != true)
       {
         $this->log('move_uploaded_file to '.$dest.' failed', 'debug');
         return false;
       }
       $filePath = $photoPathNormal.$filename;
     }
    

    
    
    if( !$this->Photo->redimentionnerImage($filePath, $photoPathNormal.$filename,$xNormal , $yNormal))
    {
      $this->log('Redimmentionner Image normal fail', 'debug');
      return false;
    }
    if( !$this->Photo->redimentionnerImage($photoPathNormal.$filename, $photoPathPreview.$filename, $xPreview , $yPreview))
    {
      $this->log('Redimmentionner Image miniature fail', 'debug');
      return false;
    }
    
    return $filename;
  }
  
/**
 * add method
 *
 * @return void
 */
  public function add($filePath = null) {
    //TODO if user.company_id is null
    if(!is_writable ( Configure::read('Settings.Medias.Photos.path') ))
    {
      throw new InternalErrorException(__('upload dir not writable '.Configure::read('Settings.Medias.Photos.path')));
    }
    if ($this->request->is('post')) {
      $ok = true;
      if($this->upload())
      {
        $imgName = $this->saveFile();

        if($imgName == false)
        {
          $ok = false;
          $this->Photo->deleteFile();
          $this->log('Could not save photo','debug');
          $this->Photo->invalidateField('upload','Erreur lors de l\'enregistrement du fichier');
        }
        $this->request->data['Photo']['path'] = $imgName;
	$this->request->data['Photo']['user_id'] = $this->Auth->user('id');

	$this->Photo->create();
	if ( $this->Photo->save($this->request->data)) {
	  $this->Session->setFlash(__('The photo has been saved'),'flash/ok');
	  $this->redirect(array('action' => 'index'));
	} else {
	  $this->Photo->deleteFile();
	  $this->Session->setFlash(__('The photo could not be saved. Please, try again.'),'flash/fail');
	}
      }
      else
      {
       $ok = false;
       $this->log('Could not upload photo','debug');
       $this->Photo->invalidateField('upload','Erreur lors de l\'upload du fichier');
      }
    }
    else
    {
      if($filePath != null)
      {
        $filePath = urldecode($filePath);
        
        $name = basename($filePath);
        $name = str_replace ( '_' , ' ', $name );
        
        if(count( $this->Media->findByName($name)) != 0)
        {
         return true;
        }
        
        
        $imgName = $this->saveFile($filePath );

        if($imgName == false)
        {
          $ok = false;
          $this->Photo->deleteFile();
          $this->log('Could not save photo','debug');
          $this->Photo->invalidateField('upload','Erreur lors de l\'enregistrement du fichier');
        }

        $data = array(
        'Photo' => array(
             'path' => $imgName,
              'name' => $name,
              'description' => 'autoloaded picture'
            )
        );
         $this->Photo->create();
         $this->Media->create();
      if ( $this->Photo->save($data)) {
        return true;
      } else {
        $this->Photo->deleteFile();
        return false;
      }
      } 
    }
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->Photo->exists($id)) {
      throw new NotFoundException(__('Invalid photo'));
    }

	$this->Photo->contain('Media.User');
	$photo=$this->Photo->findById($id);
	if($photo['Photo']['User']['company_id'] != $this->getCompanyId() )
	{
	  throw new NotFoundException(__('Invalid photo for this company'));
	}


    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Photo->save($this->request->data)) {
        $this->Session->setFlash(__('The photo has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The photo could not be saved. Please, try again.'),'flash/fail');
      }
    } else {
      $options = array('conditions' => array('Photo.' . $this->Photo->primaryKey => $id));
      $this->request->data = $this->Photo->find('first', $options);
    }
    $media = $this->Photo->Media->find('list');
    $this->set(compact('media'));
  }

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
  public function delete($id = null) {
    $this->Photo->id = $id;
    if (!$this->Photo->exists()) {
      throw new NotFoundException(__('Invalid photo'));
    }

    $this->Photo->contain('Media.User');
    $photo=$this->Photo->findById($id);
    if($photo['Photo']['User']['company_id'] != $this->getCompanyId() )
    {
      throw new NotFoundException(__('Invalid photo for this company'));
    }


    $this->request->onlyAllow('post', 'delete');
    if ($this->Photo->delete()) {
      $this->Session->setFlash(__('Photo deleted'),'flash/ok');
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Photo was not deleted'),'flash/fail');
    $this->redirect(array('action' => 'index'));
  }
}
