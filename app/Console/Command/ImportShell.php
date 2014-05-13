<?php
class ImportShell extends AppShell {

public function getOptionParser() {
    return ConsoleOptionParser::buildFromArray(array(
        'description' => array(
            __("Import from the remote database to the local database"),
        ),
   /*     'arguments' => array(
            'dest' => array('help' => __('path where will be stored output images'), 'required' => true),
            'tagId' => array('help' => __('Tag Id'))
        ),*/
        'options' => array(
			'noDemo' => array('help' => __('change sales and results data for a demo version'), 'required' => true, 'boolean'=>true),
// 'tagId' => array('help' => __('Tag Id'))
        ),
    ));
}

    public function main() {
        $this->out('Import remote database');
		$demoString = 'true';
		if($this->params['noDemo'])
		{
			$demoString = 'false';
		}
		$backupUrl = Configure::read('dbBackupUrl').'config/dbBackup/'.$demoString.'/true';
		$loginUrl = Configure::read('dbBackupUrl').'users/login';
		$login = $this->in('Login : ');
		$password = $this->in('Password : ');
		
		$postParams = 'data[User][email]='.$login.'&data[User][password]='.$password;
		
		$ch = curl_init();
		$timeout = 5;
		debug($loginUrl);
		
		// LOGIN
		//Set the URL to work with
		curl_setopt($ch, CURLOPT_URL, $loginUrl);

		// https certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		// ENABLE HTTP POST
		curl_setopt($ch, CURLOPT_POST, 1);

		//Set the post parameters
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);

		//Handle cookies for the login
		curl_setopt($ch, CURLOPT_COOKIEJAR, TMP.'cookie.txt');

		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//execute the request (the login)
		$res = curl_exec($ch);
		debug(curl_error($ch));

		//the login is now done and you can continue to get the
		//protected content.
		
		
		
		
		
		
		
		$this->out('Downloading '.$backupUrl);
		curl_setopt($ch, CURLOPT_URL, $backupUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$sql = curl_exec($ch);
		curl_close($ch);
// 		debug($sql);
		$this->out('Done');
		$this->out('Applying to database');
		App::uses('ConnectionManager', 'Model'); 
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery($sql);
		
		$this->out('Done');
    }
	
	
}
?>