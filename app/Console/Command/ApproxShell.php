<?php
class ApproxShell extends AppShell {


    public function main() {
        $this->out('Starting learning');
		
		require_once(APP . 'Vendor' . DS . 'MVGradient.php');
		$this->out('fetching datas');
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week'))));
		$this->out('preprocessing datas');
		// debug($res);
		// x y
		$data = array();
		$initDate = NULL;
		foreach($res['results'] as $value)
		{
			if($initDate == NULL)
			{
				$initDate = new DateTime($value['Result']['date']);
			}
			$curDate = new DateTime($value['Result']['date']);
			$imgDateDiff = $initDate->diff($curDate);
		
			$nbDaysDifference = $imgDateDiff->days / 7;
			$data[] = array(array(2, 4000, 0.5),$value[0]['total']);
		}
		 debug($data);
		
		$this->out('Approximate...');
		$mvg = new MVGradient();
		$mvg->set_data($data);
		
		
		$mvg->set_learning_rate(0.1);
        $params = $mvg->find_params(10);
        debug( $mvg->score($params));
		
		// https://github.com/ianbarber/PHPIR/blob/master/multivagraddec.php
		
		
		$this->out('Done');
    }
	
	
}
?>