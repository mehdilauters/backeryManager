<?php

 
class AccountManagementController extends AccountManagementAppController {

	var $name = 'AccountManagement';
        var $administratorActions = array('*');
	
	function index() {
          $this->redirect(array('controller'=>'accounts'));
	}


}
?>
