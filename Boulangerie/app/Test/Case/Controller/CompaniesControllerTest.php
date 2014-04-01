<?php
App::uses('CompaniesController', 'Controller');

/**
 * CompaniesController Test Case
 *
 */
class CompaniesControllerTest extends ControllerTestCase {

public $controllerName = "";
public $modelName = "";

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.company',
		'app.media',
		'app.user',
		'app.order',
		'app.shop',
		'app.event_type',
		'app.event',
		'app.ordered_item',
		'app.product',
		'app.product_type',
		'app.sale',
		'app.photo'
	);

	
	
	
	
	
	public function setUp() {
		parent::setUp();
		
		$this->controllerName = str_replace('Test', '', get_class($this));
		$ctrl = new $this->controllerName();
		$this->controllerName = strtolower( str_replace('Controller', '', $this->controllerName) );
		$this->modelName = $ctrl->modelClass;
		
		$this->loadModels();
	}
	
	public function loadModels() {
    $models = func_get_args();
	if(count($models) == 0 )
	{
		$models[] = $this->modelName;
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
		$result = $this->testAction('/'.$this->controllerName.'/index');
        debug($result);
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$company = $this->{$this->modelName}->find('first');
		$result = $this->testAction('/'.$this->controllerName.'/view/'.$company[$this->modelName]['id']);
        debug($result);
		
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
	public function testAdd() {
		
		$this->{$this->modelName}->query('TRUNCATE companies;');
		
		$data = array(
            $this->modelName => array(
                'id' => '',
                'name' => 'test 1',
            )
        );
		// post
		$res = $this->{$this->modelName}->find('count');
		$this->assertEquals(0, $res);
		
		$result = $this->testAction(
			'/'.$this->controllerName.'/add',
			array('data' => $data, 'method' => 'post')
		);
		$res = $this->{$this->modelName}->find('count');
		$this->assertEquals(1, $res);
		
		
		$result = $this->headers['Location'];
		$this->assertRegExp('/companies$/', $result);

		
		// try to add a new company (already one saved)
		 
		$res = $this->{$this->modelName}->find('count');
		$this->assertEquals(1, $res);
		
		$data[$this->modelName]['name'] = 'test 2';
		
        $result = $this->testAction(
            '/'.$this->controllerName.'/add',
            array('data' => $data, 'method' => 'post')
        );
		$res = $this->{$this->modelName}->find('count');
		$this->assertEquals(1, $res);
		
		 $result = $this->headers['Location'];
		 $this->assertContains('edit', $result);
		 
		 
		 // get
		$result = $this->testAction('/'.$this->controllerName.'/add', array('method' => 'get'));
        debug($result);
		
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		// post
		$company = $this->{$this->modelName}->find('first');
		$company[$this->modelName]['name'] = 'test 3';
		
		$result = $this->testAction(
			'/'.$this->controllerName.'/edit/'.$company[$this->modelName]['id'],
			array('data' => $company, 'method' => 'post')
		);
		
		$company1 = $this->{$this->modelName}->find('first');
		
		$this->assertEquals($company[$this->modelName]['name'], $company1[$this->modelName]['name']);
		
		// get
		$result = $this->testAction('/'.$this->controllerName.'/edit/'.$company[$this->modelName]['id'], array('method' => 'get'));
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
	
	
		
		$nb = $this->{$this->modelName}->find('count');
		
		$company = $this->{$this->modelName}->find('first');
		

		// get (no deletion)
		$result = $this->testAction('/'.$this->controllerName.'/delete/'.$company[$this->modelName]['id'],
			array('method' => 'get'));
        debug($result);		
		$nbNew = $this->{$this->modelName}->find('count');
		debug($nbNew);
		$this->assertEquals(($nb) , $nbNew);
		
		
		
		// post
		$result = $this->testAction(
			'/'.$this->controllerName.'/delete/'.$company[$this->modelName]['id'],
			array('method' => 'post')
		);
		
		
		
		$nbNew = $this->{$this->modelName}->find('count');
		debug($nbNew);
		$this->assertEquals(($nb - 1) , $nbNew);
	


			$exception = false;
		try{
			$result = $this->testAction('/'.$this->controllerName.'/delete/-1');
		}
		catch (Exception $e)
		{
			$exception = true;
		}
        
		$this->assertEquals(true, $exception);
	}

}
