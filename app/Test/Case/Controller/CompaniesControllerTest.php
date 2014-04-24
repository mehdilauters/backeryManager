<?php
App::uses('CompaniesController', 'Controller');
include 'AppControllerTest.php';

/**
 * CompaniesController Test Case
 *
 */
class CompaniesControllerTest extends AppControllerTest {

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

	
/*	public function testIndex() {
		parent::testIndex();
	}
	
	public function testView() {
		parent::testView();
	}
	
	public function testEdit() {
		parent::testEdit();
	}
	
	public function testDelete() {
		parent::testDelete();
	}*/
	
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
	
	
}
