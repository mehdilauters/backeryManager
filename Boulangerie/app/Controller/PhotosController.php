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
    $this->Photo->recursive = 0;
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

  
  public function upload()
  {
  
    if (isset($this->request->data['Photo']))
    {
      if( !$this->Photo->checkType($this->data['Photo']['upload']['type']) )
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
  
  
  function saveFile()
  {
    if(empty($this->request->data['Photo']['upload']['name']))
    {
      return false;
    }
    
      $photoPath =   Configure::read('Medias.Photos.path');
      $xPreview = Configure::read('Medias.Photos.xPreview');  
      $yPreview = Configure::read('Medias.Photos.yPreview');
    
      $xNormal = Configure::read('Medias.Photos.xNormal');  
      $yNormal = Configure::read('Medias.Photos.yNormal');
    
    $path_parts = pathinfo($this->request->data['Photo']['upload']['name']);
    $filename = $this->Photo->getRandomName().'.'.strtolower($path_parts['extension']);
    
    $this->request->data['Photo']['path'] = $filename;
    if(move_uploaded_file($this->request->data['Photo']['upload']['tmp_name'],$photoPath.'normal/'.$filename) != true)
    {
      $this->log('move_uploaded_file failed', 'debug');
      return false;
    }
    

    
    
    if( !$this->Photo->redimentionnerImage($photoPath.'normal/'.$filename, $photoPath.'normal/'.$filename,$xNormal , $yNormal))
    {
      $this->log('Redimmentionner Image normal fail', 'debug');
      return false;
    }
    if( !$this->Photo->redimentionnerImage($photoPath.'normal/'.$filename, $photoPath.'preview/'.$filename, $xPreview , $yPreview))
    {
      $this->log('Redimmentionner Image miniature fail', 'debug');
      return false;
    }
    
    return true;
  }
  
/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      
      if($this->upload())
      {
        if(!$this->saveFile())
        {
          $this->Photo->deleteFile();
          $this->log('Could not save photo','debug');
          $this->Photo->invalidateField('upload','Erreur lors de l\'enregistrement du fichier');
        }
      }
      else
      {
       $this->log('Could not upload photo','debug');
       $this->Photo->invalidateField('upload','Erreur lors de l\'upload du fichier');
      }
          
      
      $this->Photo->create();
      if ($this->Photo->save($this->request->data)) {
        $this->Session->setFlash(__('The photo has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Photo->deleteFile();
        $this->Session->setFlash(__('The photo could not be saved. Please, try again.'));
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
