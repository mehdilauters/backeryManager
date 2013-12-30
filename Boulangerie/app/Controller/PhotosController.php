<?php
App::uses('AppController', 'Controller');
/**
 * Photos Controller
 *
 * @property Photo $Photo
 */
class PhotosController extends AppController {

  var $uses = array('Photo', 'Media');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    //$this->Photo->recursive = 0;
    $this->set('photos', $this->paginate());
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
    $options = array('conditions' => array('Photo.' . $this->Photo->primaryKey => $id));
    $this->set('photo', $this->Photo->find('first', $options));
  }

/*  function download($idPhoto,$miniature='true')
	{
		$this->viewClass = 'Media';
		if($miniature=='true')
			$path='miniatures/';
		else
			$path='normales/';
		
		$photo=$this->Photo->findByIdPhoto($idPhoto);
		
		$path_parts = pathinfo($photo['Photo']['path']);
 		$params = array(
              'id' => $photo['Photo']['path'],
              'name' => $photo['Media']['titre'],
              'download' => true,
              'extension' => $path_parts['extension'],
              'path' => APP.'webroot/upload/photos/'.$path
       );
       $this->set($params);
		
	}
*/
  
  public function upload()
  {
  
    if (isset($this->request->data['Photo']))
    {
      if( $this->request->data['Photo']['upload']['size'] == 0 )
      {
        $this->Photo->invalidateField('upload','Please check the size of your image');
        return false;
      }
      if( !$this->Photo->checkType($this->request->data['Photo']['upload']['type']) )
      {
        $this->Photo->invalidateField('upload','Veuillez fournir une image .png, .jpg');
        return false;
      }
      if(!is_uploaded_file($this->request->data['Photo']['upload']['tmp_name']))
      {
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
      $photoPath =   Configure::read('Medias.Photos.path');
      $xPreview = Configure::read('Medias.Photos.xPreview');  
      $yPreview = Configure::read('Medias.Photos.yPreview');
    
      $xNormal = Configure::read('Medias.Photos.xNormal');  
      $yNormal = Configure::read('Medias.Photos.yNormal');
    
    $path_parts = pathinfo($filename);
    $filename = $this->Photo->getRandomName().'.'.strtolower($path_parts['extension']);
    
    if(!empty($this->request->data['Photo']['upload']['name']) )
     {
       debug($filePath);
       if(move_uploaded_file($filePath,$photoPath.'normal/'.$filename) != true)
       {
         $this->log('move_uploaded_file failed', 'debug');
         return false;
       }
       $filePath = $photoPath.'normal/'.$filename;
     }
    

    
    
    if( !$this->Photo->redimentionnerImage($filePath, $photoPath.'normal/'.$filename,$xNormal , $yNormal))
    {
      $this->log('Redimmentionner Image normal fail', 'debug');
      return false;
    }
    if( !$this->Photo->redimentionnerImage($photoPath.'normal/'.$filename, $photoPath.'preview/'.$filename, $xPreview , $yPreview))
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

	$this->Photo->create();
	if ( $this->Photo->save($this->request->data)) {
	  $this->Session->setFlash(__('The photo has been saved'));
	  $this->redirect(array('action' => 'index'));
	} else {
	  $this->Photo->deleteFile();
	  $this->Session->setFlash(__('The photo could not be saved. Please, try again.'));
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
    $media = $this->Photo->Media->find('list');
    $this->set(compact('media'));
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
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Photo->save($this->request->data)) {
        $this->Session->setFlash(__('The photo has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The photo could not be saved. Please, try again.'));
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
    $this->request->onlyAllow('post', 'delete');
    if ($this->Photo->delete()) {
      $this->Session->setFlash(__('Photo deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Photo was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
}
