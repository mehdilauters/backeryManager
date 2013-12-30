<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
  
var $controllerInvalidatesFields = array();
  
  function beforeSave($options = array())
  {
    App::uses('Sanitize', 'Utility');
    $this->data[$this->name]=Sanitize::clean($this->data[$this->name],array(  'escape'=>true,
        'remove_html'=>true,
        'carriage'=>true,
        'odd_spaces'=>true,
        'encode'=>false,
    ));
    return true;
  }
  
    public function userDefinedSchema($field = false)
  {
  	$this->_schema = $this->schema;
    if($field != false)
    {
      $res = $this->schema[$field];      
    }
    else
    {
      $res = $this->schema;
    }
    debug($this->alias);
    debug($res);
    return $res;
  }
  
  public function afterFind($results,$primary = false)
  {
    return stripslashes_deep($results);
  }
  
  public function save($data = null, $validate = true, $fieldList = array())
  {
    $res = parent::save($data, $validate, $fieldList);
    if(!$res)
    {
      if ( Configure::read('debug') != 0)
      {
        debug('['.$this->alias.'] Save didn\'t work');
        debug($this->data);
        debug($this->validationErrors);
      }
    }
    return $res;
  }
  
  
  
  /**
   *  Set a field invalid from the controller
   *
   */
  public function invalidateField($fieldName, $errorMessage = 'This field is not valid')
  {
    debug($this->alias.'['.$fieldName.'] invalidated : '.$errorMessage);
    $this->controllerInvalidatesFields[$fieldName][]=$errorMessage;
  }
  
  
  public function validates($options = array())
  {
//     debug($this->controllerInvalidatesFields);
    $valide = parent::validates($options);
    $this->validationErrors = array_merge_recursive($this->validationErrors, $this->controllerInvalidatesFields);
    return $valide && count($this->controllerInvalidatesFields) == 0;
  }
}
