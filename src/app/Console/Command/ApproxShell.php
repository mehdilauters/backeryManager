<?php
class ApproxShell extends AppShell {

	var $uses = array('Shop', 'Product', 'ProductType');

    public function main() {
        $this->out('Starting learning');
		// http://polynomialregression.drque.net/
		require_once(APP . 'Vendor' . DS . 'PolynomialRegression.php');
		
		$approxDegree = 8;
		
		 bcscale( 10 );
		
		
		$this->out('======== Shops ========');
		$this->Shop->contain();
		$shops = $this->Shop->find('all');
		foreach($shops as $shop)
		{
			$dataExists = false;
			$regression = new PolynomialRegression( $approxDegree );
			$this->out('	'.$shop['Shop']['name']);
			$this->out('fetching data');
			$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('shop'=>$shop['Shop']['id']), 'group' => array('time'=>'day'))));
			$initDate = NULL;
			$this->out('preprocessing data');
			foreach($res['results'] as $value)
			{
				$dataExists = true;
				if($initDate == NULL)
				{
					$initDate = new DateTime($value['Result']['date']);
				}
				$curDate = new DateTime($value['Result']['date']);
				$imgDateDiff = $initDate->diff($curDate);
			
				$nbWeeksDifference = $imgDateDiff->days;
				// debug($nbWeeksDifference.'=='.$value[0]['total']);
				$regression->addData( $nbWeeksDifference, $value[0]['total'] );
			}
			if($dataExists)
			{
				$this->out('compute coefficients...');
				$coefficients = $regression->getCoefficients();
				debug($this->getFunctionText($coefficients));
				$shop['Shop']['equation_parameters'] = serialize($coefficients);
				$this->Shop->save($shop);
				// debug(serialize($coefficients));
			}
			else
			{
				$this->out('/!\\ No data');
			}
			$this->out('done');
		}
		
		
		$this->out('======== Products ========');
		$this->Product->contain();
		$products = $this->Product->find('all');
		foreach($products as $product)
		{
			$dataExists = false;
			$regression = new PolynomialRegression( $approxDegree );
			$this->out('	'.$product['Product']['name']);
			$this->out('fetching data');
			$res = $this->requestAction(array('controller'=>'sales', 'action'=>'stats'), array( 'pass'=>array('conditions'=>array('Sale.product_id'=>$product['Product']['id']), 'group' => array('time'=>'week', 'shop'=>'', 'product'=>''))));
			$initDate = NULL;
			$this->out('preprocessing data');
			foreach($res['sales'] as $sale)
			{
				$dataExists = true;
				if($initDate == NULL)
				{
					$initDate = new DateTime($sale['Sale']['date']);
				}
				$curDate = new DateTime($sale['Sale']['date']);
				$imgDateDiff = $initDate->diff($curDate);
			
				$nbWeeksDifference = $imgDateDiff->days / 7;
				// debug($nbWeeksDifference.'=='.$value[0]['total']);
				$regression->addData( $nbWeeksDifference, $sale[0]['sold'] );
			}
			if($dataExists)
			{
				$this->out('compute coefficients...');
				$coefficients = $regression->getCoefficients();
				debug($this->getFunctionText($coefficients));
				$product['Product']['equation_parameters'] = serialize($coefficients);
				$this->Product->save($product);
				// debug(serialize($coefficients));
			}
			else
			{
				$this->out('/!\\ No data');
			}
			$this->out('done');
		}
		
		
		
		
		$this->out('======== Product Types ========');
		$this->ProductType->contain();
		$productTypes = $this->Product->find('all');
		foreach($productTypes as $productType)
		{
			$dataExists = false;
			$regression = new PolynomialRegression( $approxDegree );
			$this->out('	'.$productType['ProductType']['name']);
			$this->out('fetching data');
			$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('productType'=>$productType['ProductType']['id']), 'group' => array('time'=>'week', 'shop'=>''))));
			$initDate = NULL;
			$this->out('preprocessing data');
			foreach($res['resultsEntries'] as $resultsEntry)
			{
				$dataExists = true;
				if($initDate == NULL)
				{
					$initDate = new DateTime($resultsEntry['ResultsEntry']['date']);
				}
				$curDate = new DateTime($resultsEntry['ResultsEntry']['date']);
				$imgDateDiff = $initDate->diff($curDate);
			
				$nbWeeksDifference = $imgDateDiff->days / 7;
				// debug($nbWeeksDifference.'=='.$value[0]['total']);
				$regression->addData( $nbWeeksDifference, $resultsEntry[0]['result'] );
			}
			if($dataExists)
			{
				$this->out('compute coefficients...');
				$coefficients = $regression->getCoefficients();
				debug($this->getFunctionText($coefficients));
				$productType['ProductType']['equation_parameters'] = serialize($coefficients);
				$this->ProductType->save($productType);
				// debug(serialize($coefficients));
			}
			else
			{
				$this->out('/!\\ No data');
			}
			$this->out('done');
		}
		
		return;
		
		$regression = new PolynomialRegression( 10 );
		
		$this->out('fetching data');
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('shop'=>$id), 'group' => array('time'=>'week'))));
		
		$this->out('preprocessing datas');
		// debug($res);
		// x y
		$initDate = NULL;
		$lastX = NULL;
		foreach($res['results'] as $value)
		{
			if($initDate == NULL)
			{
				$initDate = new DateTime($value['Result']['date']);
			}
			$curDate = new DateTime($value['Result']['date']);
			$imgDateDiff = $initDate->diff($curDate);
		
			$nbWeeksDifference = $imgDateDiff->days / 7;
			// debug($nbWeeksDifference.'=='.$value[0]['total']);
			$regression->addData( $nbWeeksDifference, $value[0]['total'] );
			$lastX = $nbWeeksDifference;
		}
		
		$this->out('compute coefficients...');
		
		$coefficients = $regression->getCoefficients();
		debug($coefficients);
		
		$functionText = "f(x) = ";

		foreach($coefficients as $power => $coefficient)

		{

		  if($power > 0)

			$functionText .= ($coefficient > 0) ? " + " : " - ";

		  $functionText .= abs(round($coefficient, 4));

		  if ($power > 0)

		  {

			$functionText .= "x";

			if ($power > 1)

			  $functionText .= "^" . $power;

		  }

		}
		debug($functionText);
		
		$this->out('Approximate...');
		$y = $regression->interpolate( $coefficients, $lastX +1 );
		debug($y);
		// https://github.com/ianbarber/PHPIR/blob/master/multivagraddec.php
		
		
		$this->out('Done');
    }
	
	
	public function getFunctionText($coefficients)
	{
		$functionText = "f(x) = ";

			foreach($coefficients as $power => $coefficient)

			{

				$functionText .= ($coefficient > 0) ? " + " : " - ";

			  $functionText .= abs(round($coefficient, 4));

			  if ($power > 0)

			  {

				$functionText .= "x";

				if ($power > 1)

				  $functionText .= "^" . $power;

			  }

			}
		return $functionText;
		}
	
}
?>