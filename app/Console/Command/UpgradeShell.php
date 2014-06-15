<?php

define('UPG_DEBUG', true);

class UpgradeShell extends AppShell {

var $uses = array('DatabaseVersion');

private $curlHandler;
private $tmpFile = 'upgTmp.zip';
private $extractFolder = '../../upg/';
private $upgPath = '';

private $upgrader = NULL;

private $currentVersion;
private $upgradeVersion;

private $newDatabaseVersion = -1;

// tracker.ctp
// photos...

public function getOptionParser() {

    return ConsoleOptionParser::buildFromArray(array(
        'description' => array(
            __("Upgrade the application from ").Configure::read('Settings.github.downloadUrl'),
        ),
        'arguments' => array(
            'version' => array('help' => __('version'), 'required' => true),
        ),
        'options' => array(
//			'noDemo' => array('help' => __('change sales and results data for a demo version'), 'required' => true, 'boolean'=>true),
        ),
    ));
}

    public function main() {
    $this->log('Upgrade process started', 'debug');
	$this->curlHandler = curl_init();
	
	
	$this->currentVersion = file_get_contents(APP.'Version.txt');
	$this->currentDatabaseVersion = file_get_contents(APP.'dbVersion.txt');
	$commitRes = $this->downloadUrl(Configure::read('Settings.github.apiUrl').'commits/'.$this->args[0]);
	$commitInfos = json_decode($commitRes);
	$this->upgradeVersion = $commitInfos->sha;
	debug("FORCE VERSION");
	$this->upgradeVersion = 'master';
	$this->log('From version '.$this->currentVersion.' to '.$this->upgradeVersion, 'debug');
	
	
	
	$this->download($this->upgradeVersion);
	
	$zip = new ZipArchive();
	
	 $zip->open(TMP.$this->tmpFile, false);
	
	$this->log('Extracting to '.APP.$this->extractFolder, 'debug');
	
	App::uses('Folder', 'Utility');
	App::uses('File', 'Utility');
	
	
	$zip->extractTo(APP.$this->extractFolder);
	$extractedFiles = $zip->getNameIndex(0);

	$this->upgPath = APP.$this->extractFolder.$extractedFiles;
	require $this->upgPath.'app/Config/upgScripts/Upgrader.php';
	$this->upgrader = new Upgrader($this->upgPath, APP);
	
	if(!$this->upgrader->beforeUpgrade())
	{
		$this->log('Before upgrade callback fails', 'debug');
		$this->clean();
		return -1;
	}
	
	if(!$this->upgradeConfig())
	{
		$this->clean();
		return false;
	}
	
	
	if(!$this->upgradeDatabase())
	{
		$this->clean();
		return false;
	}
	
	if(!$this->upgradeApp())
	{
		$this->clean();
		return false;
	}
	
	
	if(!$this->upgrader->afterUpgrade())
	{
		$this->log('after upgrade callback fails', 'debug');
		$this->clean();
		return -1;
	}
	
	$this->clean();
	$this->out('Done');
    }
	
	public function upgradeConfig()
	{
			if(!$this->upgrader->beforeConfigUpgrade())
		{
			$this->log('Before config upgrade callback fails', 'debug');
			return false;
		}
		$userInitConfig = Configure::read('Settings');
		require $this->upgPath.'app/Config/boulangerie.php';

		$this->newDatabaseVersion = Configure::read('Settings.databaseVersion');
		
		$upgInitConfig = Configure::read('Settings');
		


		$userConfig = array_replace_recursive($userInitConfig, $upgInitConfig);
		
		$configFile = new File($this->upgPath.'app/Config/boulangerie.php');
		if( !$configFile->copy($this->upgPath.'app/Config/boulangerie.php.backup') )
		{
			$this->log('Could not backup config file', 'debug');
		}
		
		
		$text = 'Configure::write(\'Settings\','.var_export($userConfig, true).');';


//  		$constant = get_defined_constants(true); // true => categorize
// 		debug($constant);
		// TODO Do not preserve constant such as TMP, APP..

		if(! $configFile->write($text) )
		{
			$this->log('Could not upgrade UserConfig', 'debug');
		}
		

		$dbConfigFile = new File(APP.'Config/database.php');
		if( !$dbConfigFile->copy($this->upgPath.'app/Config/database.php') )
		{
			$this->log('Could not upgrade database config file', 'debug');
		}

		if(!$this->upgrader->afterConfigUpgrade())
		{
			$this->log('after config upgrade callback fails', 'debug');
			return false;
		}
		
		return true;
	}
	
	
	public function upgradeDatabase()
	{
	  $return = true;
		if(!$this->upgrader->beforeDatabaseUpgrade())
		{
			$this->log('Before database upgrade callback fails', 'debug');
			$this->clean();
			return -1;
		}
		$sqlHistory = array();




	try{
	  $version = $this->DatabaseVersion->find('first');
	}
	catch (Exception $e)
	{
		$version['DatabaseVersion']['version'] = -1;
	}
	
	$this->log('database : upgrade from '.$version['DatabaseVersion']['version'].' to '.$this->newDatabaseVersion, 'debug');

	if($version['DatabaseVersion']['version'] < $this->newDatabaseVersion)
	{
	      $this->log('database upgrade started', 'debug');
	      $dataSource = $this->DatabaseVersion->getDataSource();
	      $databaseName = $dataSource->getSchemaName();
	      $fileName = $databaseName . '-upgBackup-' . date('Y-m-d') . '.sql';

	      $dbBackupPath = $this->upgPath.'app/Config/upgScripts/schema/'.$fileName;
	      $this->log('backup database to '.$dbBackupPath, 'debug');

	      $sqlBackup = $dataSource->backup(false);
	      $file = new File($dbBackupPath);
	      $file->write($sqlBackup);
	      $file->close();



		$dir = new Folder($this->upgPath.'app/Config/upgScripts/schema/');
		$files = $dir->find('.*\.sql');

		foreach ($files as $file) {
		    $file = new File($dir->pwd() . DS . $file);

		    preg_match ( '/(\d+)\.sql/' , $file->name , $matches );
		    if(count($matches) == 2)
		    {
		      if($matches[1] >= $version['DatabaseVersion']['version'])
		      {
			  $this->log('database getting upgrade script from '.$matches[1], 'debug');
			  $sqlHistory[$matches[1]] =  $file->read();
		      }
		    }
		}
		



	      App::uses('ConnectionManager', 'Model'); 
	      $db = ConnectionManager::getDataSource('default');

	      foreach($sqlHistory as $dbVersionFrom => $sql)
	      {
		  $this->log('database upgrade from '.$dbVersionFrom, 'debug');
		try{
		  $db->rawQuery($sql);
		}
		catch (Exception $e)
		{
			$this->log('database upgrade from '.$dbVersionFrom.' failed', 'debug');
			$return = false;
		}
	      }

		$version['DatabaseVersion']['version'] = $this->newDatabaseVersion;
// 		$this->DatabaseVersion->save($version);
	}
	else
	    {
	      $this->log('database already up to date', 'debug');
	    }

		if(!$this->upgrader->afterDatabaseUpgrade())
		{
			$this->log('after database upgrade callback fails', 'debug');
			$this->clean();
			return -1;
		}

	  return $return;
	}
	
	private function downloadUrl($url)
	{
		$this->out('Fetching...'.$url);	
		curl_setopt($this->curlHandler, CURLOPT_URL, $url);
		curl_setopt($this->curlHandler, CURLOPT_USERAGENT, 'BakeryManager upgrader');
		// https certificate
		curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYHOST, false);
		
		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);
		
		$res = curl_exec($this->curlHandler);
		debug(curl_error($this->curlHandler));
		return $res;
	}
	
	public function download($versionId)
	{
		$url = Configure::read('Settings.github.downloadUrl').'zip/'.$versionId;


		$res = $this->downloadUrl($url);
		$this->out('saved to '.TMP.$this->tmpFile);	
		return file_put_contents(TMP.$this->tmpFile, $res);
	}
	

	public function upgradeApp()
	{
	  // backup previous version
	  $currentVersionDir = new Folder(APP.'../');
	  $backupPath = APP.'../../backup';
	  $backupDir = new Folder($backupPath);
	  if($backupDir->delete())
	  {
	      $this->log('Could not delete backup dir '.$backupPath, 'debug');
	  }

	  if(!$currentVersionDir->move($backupPath))
	  {
	    $this->log('Could not backup currentVersion to '.$backupPath, 'debug');
	  }

	  $newVersionDir = new Folder($this->upgPath);

	  $newVersionDirPath = str_replace('app/', '', APP);
	  if(!$newVersionDir->move($newVersionDirPath))
	  {
	    $this->log('Could not move to '.$newVersionDirPath, 'debug');
	  }
	  else
	  {
	      $this->log('move '.$this->upgPath.' to '.$newVersionDirPath, 'debug');
	  }
	}
	
	public function clean()
	{
		// delete zip file
		// rm extracted folder
		
	}
	
	
}
?>