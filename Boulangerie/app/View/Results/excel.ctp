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


  // define table cells
    $table = array(
        array('label' => 'date', 'filter' => true),
        array('label' => 'total'),
        array('label' => 'especes'),
        array('label' => 'chèques'),
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
	  ($results['cash'] + $results['check']),
	  $results['cash'],
	  $results['check'],
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
		  ($shopData['total']['cash'] + $shopData['total']['check']),
		  $shopData['total']['cash'],
		  $shopData['total']['check']
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
        $this->PhpExcel->output($filename = 'export.xlsx', $writer = 'Excel5');

?>

