<?php

App::uses('UsersController', 'Controller');

/**
 * CompaniesController Test Case
 *
 */
class AppControllerTest extends ControllerTestCase {

public $controllerName = "";

/**
*	public $testRecord = array(
*								array(
*									$this->controller->modelClass => array(
*										'name' => 'testInsert1'
*									)
*								),
*								array(
*									$this->controller->modelClass => array(
*										'name' => 'testInsert2'
*									)
*								),
*							);
*
*/
	var $testRecord = array();



	public function setUp() {
		parent::setUp();
		$this->controllerName = str_replace('Test', '', get_class($this));
		 $this->controller = new $this->controllerName();
		
		// debug($this->controllerName);
	/*	$this->controller = $this->generate($this->controllerName, array(
			'methods' => array(
				'isAuthorized'
			),

		));
		
		$this->controller->method('isAuthorized')
        ->will($this->returnValue(true));
		*/
		$ctrl = new $this->controllerName();
		$this->controllerName = strtolower( str_replace('Controller', '', $this->controllerName) );
		$this->controller->modelClass = $ctrl->modelClass;
		$this->loadModels();
	//	$user = ClassRegistry::init('User');
		
	//	$usersController = new UsersController();
	//	$usersController->constructClasses();
	//	 $userRes = $user->find('first', array('conditions' => array('email' => 'mehdilauters@gmail.com')));
		 //debug($usersController->Auth);
		//$usersController->Auth->login($userRes);
		
	}
	
	public function loadModels() {
    $models = func_get_args();
	if(count($models) == 0 )
	{
		$models[] = $this->controller->modelClass;
	}
    foreach ($models as $modelClass) {
      $name = $modelClass;
      if(!isset($this->{$name})) {
        $this->{$name} = ClassRegistry::init(array(
          'class' => $modelClass, 'alias' => $modelClass
        ));
      }
    }
  }
	
/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		// $result = $this->testAction('/'.$this->controllerName.'/index');
        // debug($result);
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$data = $this->{$this->controller->modelClass}->find('first');
		 $result = $this->testAction('/'.$this->controllerName.'/view/'.$data[$this->controller->modelClass]['id']);
		$exception = false;
		try{
			$result = $this->testAction('/'.$this->controllerName.'/view/-1');
		}
		catch (Exception $e)
		{
			$exception = true;
		}
		$this->assertEquals(true, $exception);
	}

/**
 * testAdd method
 *
 * @return void
 */
/*	public function testAdd() {
		$this->{$this->controller->modelClass}->query('TRUNCATE '.$this->controllerName.';');
		

		// post
		$res = $this->{$this->controller->modelClass}->find('count');
		$this->assertEquals(0, $res);
		
		$result = $this->testAction(
			'/'.$this->controllerName.'/add',
			array('data' => $this->testRecord[0], 'method' => 'post')
		);
		$res = $this->{$this->controller->modelClass}->find('count');
		$this->assertEquals(1, $res);
		
		
		$result = $this->headers['Location'];
		debug('add redirect to '.$result);
		// $this->assertRegExp('/'.$this->controllerName.'$/', $result);

		
		// try to add a new data (already one saved)
		 
		$res = $this->{$this->controller->modelClass}->find('count');
		$this->assertEquals(1, $res);
		
		
        $result = $this->testAction(
            '/'.$this->controllerName.'/add',
            array('data' => $this->testRecord[1], 'method' => 'post')
        );
		$res = $this->{$this->controller->modelClass}->find('count');
		$this->assertEquals(2, $res);
		
		 $result = $this->headers['Location'];
		 // $this->assertContains('index', $result);
		 debug('add redirect to '.$result);
		 
		 // get
		$result = $this->testAction('/'.$this->controllerName.'/add', array('method' => 'get'));
        debug($result);
		$this->assertEquals(1, 0);
	}
*/
/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		// post
		$data = $this->{$this->controller->modelClass}->find('first');
		$data[$this->controller->modelClass]['name'] = 'test 3';
		
		$result = $this->testAction(
			'/'.$this->controllerName.'/edit/'.$data[$this->controller->modelClass]['id'],
			array('data' => $data, 'method' => 'post')
		);
		
		$company1 = $this->{$this->controller->modelClass}->find('first');
		
		$this->assertEquals($data[$this->controller->modelClass]['name'], $company1[$this->controller->modelClass]['name']);
		
		// get
		$result = $this->testAction('/'.$this->controllerName.'/edit/'.$data[$this->controller->modelClass]['id'], array('method' => 'get'));
        debug($result);
		
		
		
		$exception = false;
		try{
			$result = $this->testAction('/'.$this->controllerName.'/edit/-1');
		}
		catch (Exception $e)
		{
			$exception = true;
		}
        
		$this->assertEquals(true, $exception);
	}
	
	

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	
	
		
		$nb = $this->{$this->controller->modelClass}->find('count');
		
		$data = $this->{$this->controller->modelClass}->find('first');
		

		// get (no deletion)
		
		$exception = false;
		try{
			$result = $this->testAction('/'.$this->controllerName.'/delete/'.$data[$this->controller->modelClass]['id'],
			array('method' => 'get'));
			debug($result);	
		}
		catch (MethodNotAllowedException $e)
		{
			$exception = true;
		}
        
		$this->assertEquals(true, $exception);
		
        	
		$nbNew = $this->{$this->controller->modelClass}->find('count');
		debug($nbNew);
		$this->assertEquals(($nb) , $nbNew);
		
		
		
		// post
		
		try{
		
		$result = $this->testAction(
			'/'.$this->controllerName.'/delete/'.$data[$this->controller->modelClass]['id'],
			array('method' => 'post')
			);
			debug($nbNew);
		}
		catch (MethodNotAllowedException $e)
		{
			$exception = true;
		}

		$this->assertEquals(true, $exception);
		
		
		$nbNew = $this->{$this->controller->modelClass}->find('count');
	}
	
}


?>