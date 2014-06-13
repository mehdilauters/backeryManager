<?php
class ImportShell extends AppShell {

public $uses = array('RemoteDB');

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

	if(!Configure::read('Settings.demo.active'))
	{
	  $this->out('######## This is not a demo version! #########');
	  return;
	}

	$demo = true;
	if($this->params['noDemo'])
	{
		$demo = false;
	}
	
	$login = $this->in('Login : ');
	$password = $this->in('Password : ');

	$this->RemoteDB->login($login, $password);
	

	$this->out('Fteching...');
	$sql = $this->RemoteDB->download($demo);
	$this->out('Done');

	$this->out('Applying to database');
	$this->RemoteDB->applyToDB($sql);
	$this->out('Done');
    }
	
	
}
?>