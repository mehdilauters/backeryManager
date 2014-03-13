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
		$url = Configure::read('dbBackupUrl').$demoString.'/true';
		$ch = curl_init();
		$timeout = 5;
		$this->out('Downloading '.$url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$sql = curl_exec($ch);
		curl_close($ch);
		//debug($sql);
		$this->out('Done');
		$this->out('Applying to database');
		App::uses('ConnectionManager', 'Model'); 
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery($sql);
		
		$this->out('Done');
    }
	
	
}
?>