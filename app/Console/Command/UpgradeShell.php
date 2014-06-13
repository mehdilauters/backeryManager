<?php

define('UPG_DEBUG', true);

class UpgradeShell extends AppShell {

private $curlHandler;
private $tmpFile = 'upgTmp.zip';
private $tmpExtractFolder = 'upgTmp/';
private $upgPath = 'upgTmp/';

private $upgrader = NULL;

private $currentVersion;
private $upgradeVersion;

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
	$commitRes = $this->downloadUrl(Configure::read('Settings.github.apiUrl').'commits/'.$this->args[0]);
	debug($commitRes);
	return;
	$commitInfos = json_decode($commitRes);
	$this->upgradeVersion = $commitInfos->sha;
	
	$this->log('From version '.$this->currentVersion.' to '.$this->upgradeVersion, 'debug');
	
	
	
	$this->download($this->upgradeVersion);
	
	$zip = new ZipArchive();
	
	 $zip->open(TMP.$this->tmpFile, false);
	
	$extractPath = TMP.$this->tmpExtractFolder;
	$this->log('Extracting to '.$extractPath, 'debug');
	
	App::uses('Folder', 'Utility');
	App::uses('File', 'Utility');
	
	$dir = new Folder($extractPath, true);
	
	 // $zip->extractTo($extractPath);
	$extractedFiles = $zip->getNameIndex(0);
	
	$this->upgPath = $extractPath.$extractedFiles;
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
		
		$upgInitConfig = Configure::read('Settings');
		
		// TODO Do not preserve constant such as TMP, APP..
		$userConfig = array_replace_recursive($userInitConfig, $upgInitConfig);
		
		$configFile = new File(APP.'Config/boulangerie.php');
		if( !$configFile->copy(APP.'Config/boulangerie.php.backup') )
		{
			$this->log('Could not backup config file', 'debug');
		}
		
		
		$text = 'Configure::write(\'Settings\','.var_export($userConfig, true).');';
		if(! UPG_DEBUG )
		{
			if(! $configFile->write($userConfig) )
			{
				$this->log('Could not upgrade UserConfig', 'debug');
			}
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
		if(!$this->upgrader->beforeDatabaseUpgrade())
		{
			$this->log('Before database upgrade callback fails', 'debug');
			$this->clean();
			return -1;
		}
		$sqlHistory = array();
		$commits = json_decode($this->downloadUrl(Configure::read('Settings.github.apiUrl').'commits'));
		
		$record = false;
		
		
		foreach($commits as $commit)
		{
			$this->out('Commit '.$commit->sha);
			if($commit->sha == $this->currentVersion)
			{
				break;
			}
			if($commit->sha == $this->upgradeVersion)
			{
				$record = true;
			}
			
			if($record)
			{
				$treeUrl = $commit->url;
				$commitInfos = json_decode($this->downloadUrl($treeUrl));
				foreach($commitInfos->files as $file)
				{
					$this->out('File '.$file->filename);
					// https://api.github.com/repos/mehdilauters/bakeryManager/commits/b9a98d18559c5ae6483ebaba9855b9732986182b
					if($file->filename == 'app/Config/boulangerie.php' ) // database
					{
						$sql = '';
						$sql .= '-- ################### commit '.$commit->sha." --\n\n";
						// TODO multiline messages
						$sql .= '-- '.$commit->message." --\n\n";
						
						$fileInfos = json_decode($this->downloadUrl($file->contents_url));
						$sql .= $this->downloadUrl($file->content);
						$sqlHistory[] = base64_decode ($sql);
					}
				}
			}
		}
		
		
		// TODO reverse array
		debug($sqlHistory);
		if(!$this->upgrader->afterDatabaseUpgrade())
		{
			$this->log('after database upgrade callback fails', 'debug');
			$this->clean();
			return -1;
		}
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
	
	
	public function clean()
	{
		// delete zip file
		// rm extracted folder
		
	}
	
	
}
?>