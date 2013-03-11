<?php
App::uses('AppModel', 'Model');
App::import('Model','Media'); 
/**
 * Photo Model
 *
 */
class Photo extends AppModel {

/**
 * Display field
 *
 * @var string
 */
  public $displayField = 'id';

  var $lengthName=10;
  
  public $actsAs = array( 'Inherit'=>array('inheritanceField'=>'id', 'parentClass'=>'Media') ); 
  
/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Photo::id must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'message' => 'Photo::id must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
  );
  
  /**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
    'Media' => array(
      'className' => 'Media',
      'foreignKey' => 'id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );
  
  
  function beforeDelete($options = array())
  {
    $p = parent::beforeDelete($options);

    $this->data = $this->findById($this->id);

    $this->deleteFile();
    return $p;
  }

  function deleteFile()
  {

      $photosPath = Configure::read('Medias.Photos.path');
    $filename = $this->data['Photo']['path'];
    if(file_exists ( $photosPath.'normal/'.$filename ))
      unlink($photosPath.'normal/'.$filename);
    
    if(file_exists ( $photosPath.'preview/'.$filename ))
      unlink($photosPath.'preview/'.$filename);
  }  
  
  
  function checkType($type)
  {
    switch($type)
    {
      case 'image/jpeg':
        break;
      case 'image/png':
        break;
      default:
        return false;
        break;
    }
    return true;
  }
  
  
  public function redimentionnerImage($source, $destination, $maxWidth, $maxHeight, $minWidth=0, $minHeight=0) {
    // Recuperer l'image original
    $dims = getimagesize($source) ;
    if(strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'gif') {
      $chemin = imagecreatefromgif($source) ;
    } // Gif
    elseif(strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'jpg' || strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'jpeg') { // Jpg
      $chemin = imagecreatefromjpeg($source) ;
    }
    elseif(strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'png') {
      $chemin = imagecreatefrompng($source) ;
    } // Png
    else { return false;
    } // Autres extensions non pris en charge
  
    // Calcul des dimensions de la nouvelle image
    if($dims[0] > $maxWidth || $dims[0] < $minWidth ||
        $dims[1] > $maxHeight || $dims[1] < $minHeight) { // Si les dimenssions doivent etre changes
      // Trouver les nouvelles dimensions
      if(($maxWidth*$maxHeight)/($dims[0]*$dims[1]) > ($minWidth*$minHeight)/($dims[0]*$dims[1])) { // L'image est trop grande
        if($dims[0]/$maxWidth > $dims[1]/$maxHeight) { // La largeur en priorite
          $largeur = $maxWidth;
          $hauteur = $dims[1]*$maxWidth/$dims[0];
        } else { // La hauteur en priorite
          $largeur = $dims[0]*$maxHeight/$dims[1];
          $hauteur = $maxHeight;
        }
      } else { // L'image est trop petite
        if($dims[0]/$minWidth < $dims[1]/$minHeight) { // La largeur en priorite
          $largeur = $minWidth;
          $hauteur = $dims[1]*$minWidth/$dims[0];
        } else { // La hauteur en priorite
          $largeur = $dims[0]*$minHeight/$dims[1];
          $hauteur = $minHeight;
        }
      }
  
      // Creer la nouvelle image a partir de l'originale avec de nouvelles dimensions
      $nouvelle = imagecreatetruecolor($largeur, $hauteur) ;
      imagecopyresampled($nouvelle,$chemin,0,0,0,0,$largeur,$hauteur,$dims[0],$dims[1]) ;
  
      // Enregistrement de la nouvelle image
      if(strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'gif') {
        return imagegif($nouvelle,$destination) ;
      } // Gif
      elseif(strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'jpg' || strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'jpeg') { // Jpg
        return imagejpeg($nouvelle,$destination) ;
      }
      elseif(strtolower(pathinfo($source, PATHINFO_EXTENSION)) == 'png') {
        return imagepng($nouvelle,$destination) ;
      } // Png
    }
    elseif($source != $destination) { // Si le fichier doit etre uniquement deplace
      // Deplacer le fichier
      return copy($source,$destination);
    }
  
    return true; // L'operation s'est bien deroulee
  }
  
    function getRandomName() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVW';
      $string ='';    
  
      for ($p = 0; $p < $this->lengthName; $p++) {
          $string .= @$characters[mt_rand(0, strlen($characters))];
      }
  
      return $string;
  }
  
}
