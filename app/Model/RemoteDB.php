<?php
App::uses('AppModel', 'Model');

App::uses('User', 'Model');

/**
 * Result Model
 *
 * @property Shop $Shop
 */
class RemoteDB extends AppModel {
	public $useTable = false;
	
	private $curlHandler;
	private $demo = false;
	
	public function login($login, $password)
	{
		$loginUrl = Configure::read('Settings.dbBackupUrl').'users/login';
		$postParams = 'data[User][email]='.$login.'&data[User][password]='.$password;
		
		$this->curlHandler = curl_init();
		$timeout = 5;
		debug($loginUrl);
		
		// LOGIN
		//Set the URL to work with
		curl_setopt($this->curlHandler, CURLOPT_URL, $loginUrl);

		// https certificate
		curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYHOST, false);

		// ENABLE HTTP POST
		curl_setopt($this->curlHandler, CURLOPT_POST, 1);

		//Set the post parameters
		curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $postParams);

		//Handle cookies for the login
		curl_setopt($this->curlHandler, CURLOPT_COOKIEJAR, TMP.'cookie.txt');

		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);

		//execute the request (the login)
		$res = curl_exec($this->curlHandler);
		debug(curl_error($this->curlHandler));

		//the login is now done and you can continue to get the
		//protected content.
	}
	
	public function download($demo = true)
	{
		$this->demo = $demo;
		$timeout = 5;
		$demoString = '1';
		if(!$demo)
		{
			$demoString = '0';
		}
		$backupUrl = Configure::read('Settings.dbBackupUrl').'config/dbBackup/'.$demoString.'/1';
		debug($backupUrl);
		curl_setopt($this->curlHandler, CURLOPT_URL, $backupUrl);
		curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curlHandler, CURLOPT_CONNECTTIMEOUT, $timeout);
		$sql = curl_exec($this->curlHandler);
		curl_close($this->curlHandler);
 		//debug($sql);
		$sql = $this->removeUtf8Bom($sql);
		return $sql;
	}
	
	public function applyToDB($sql)
	{
	    if(Configure::read('Settings.demo.active'))
	    {
		App::uses('ConnectionManager', 'Model'); 
		$db = ConnectionManager::getDataSource('default');
		require_once(APP . 'Vendor' . DS . 'SqlFormatter.php');
		$sqlSplitted = SqlFormatter::splitQuery($sql);
		debug(count($sqlSplitted).' queries');
		foreach($sqlSplitted as $sqlQuery)
		{
		  $res = $db->execute($sqlQuery);
		  echo('.');
		}
		debug($res);
		
		
		if( $this->demo )
		{
			// hash password
			$userModel = new User();
			$users = $userModel->find('all');
			App::import('Component','Auth');
			foreach( $users as $user )
			{
				$user['User']['password'] = AuthComponent::password($user['User']['name']);
				$userModel->save($user);
			}
		}
		
		return $res;
	    }
		

	    return false;
	}
	
	
	function removeUtf8Bom($str=""){
    if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
        $str=substr($str, 3);
    }
    return $str;
}

}

?>