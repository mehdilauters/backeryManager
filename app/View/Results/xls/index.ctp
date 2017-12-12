<?php 
// debug($data) 

$this->PhpExcel->createWorksheet();

$sheetId = 0;
foreach($data['entries'] as $shopId => $shopData)
{
  $this->PhpExcel->createSheet();
  $this->PhpExcel->setRow(1);
  $this->PhpExcel->setActiveSheetIndex($sheetId);
  $this->PhpExcel->setWorksheetName($shops[$shopId]);
  $sheetId++;

  // freeze first row/column
  $this->PhpExcel->getActiveSheet()->freezePane('B2');

  // define table cells
    $table = array(
        array('label' => 'date', 'filter' => true),
        array('label' => 'total'),
        array('label' => 'especes'),
        array('label' => 'chèques'),
        array('label' => 'carte bleue'),
        array('label' => 'Compte clients'),
    );
    foreach($productTypes as $typeId => $typeName)
    {
	  $table[] = array('label' => $typeName);
    }

    // add heading with different font and bold text
     $this->PhpExcel->addTableHeader($table, array('name' => 'Cambria', 'bold' => true));
    

    foreach($shopData['entries'] as $results)
    {
	$date = new DateTime($results['date']);
	$data = array(
	  $date->format('d/m/Y'),
	  ($results['cash'] + $results['check'] + $results['card'] + $results['account']),
	  $results['cash'],
	  $results['check'],
	  $results['card'],
    $results['account'],
	);
	foreach($productTypes as $typeId => $typeName)
	{
	  $value = '';
	  if(isset($results['productTypes'][$typeId]))
	  {
	    $value = $results['productTypes'][$typeId]['result']; 
	  }
	  $data[] = $value;
	}
	$this->PhpExcel->addData($data);
    }
    //totaux
    $data = array(
		  'Totaux',
      ($shopData['total']['cash'] + $shopData['total']['check'] + $shopData['total']['card'] + $shopData['total']['account']),
		  $shopData['total']['cash'],
		  $shopData['total']['check'],
		  $shopData['total']['card'],
      $shopData['total']['account']
		  );
    foreach($productTypes as $typeId => $typeName)
    {
	$value = '';
	if(isset($shopData['total'][$typeId]))
	{
	  $value = $shopData['total'][$typeId]; 
	}
	$data[] = $value;
      }
      $this->PhpExcel->addData($data);
  $this->PhpExcel->addTableFooter();
}




    // add data


  
//     foreach ($data as $d) {
//         $this->PhpExcel->addData(array(
//             'username',
//             'typename',
//             'date',
//             'descritpion',
//             'modified'
//         ));
//     }

    // close table and output
      if( isset($fileName))
      {
	$this->PhpExcel->save($fileName, $writer = 'Excel5');
      }
      else
      {
	$filename = 'export_'.str_replace(DS, '-', $dateStart).'_'.str_replace(DS, '-', $dateEnd).'.xls';
        $this->PhpExcel->output($filename, $writer = 'Excel5');
      }

?>

