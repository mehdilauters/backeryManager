<?php 

// debug($data) 
ob_end_clean();
$this->PhpExcel->createWorksheet();

$sheetId = 0;

$alphabet =   $this->MyHtml->getAlphabet();

foreach($shops as $shopId => $shopName)
{
  if($sheetId != 0)
  {
    $this->PhpExcel->createSheet();
  }
  $this->PhpExcel->setRow(1);
  $this->PhpExcel->setActiveSheetIndex($sheetId);
  $this->PhpExcel->setWorksheetName('#'.$shopId.' '.$shopName);
  $sheetId++;

  // freeze first row/column
  $this->PhpExcel->getActiveSheet()->freezePane('B2');
  
  //activate protection
	$this->PhpExcel->getActiveSheet()->getProtection()->setSheet(true);
	$this->PhpExcel->getActiveSheet()->getProtection()->setFormatColumns(false);
    $this->PhpExcel->getActiveSheet()->getProtection()->setFormatCells(false);
  
  
  
  // define table cells
    $table = array(
        array('label' => 'date', 'filter' => true),
        array('label' => 'total'),
	array('label' => 'valide'),
        array('label' => 'especes'),
        array('label' => 'cheques'),
	array('label' => 'carte bleue'),
	array('label' => 'Commentaire'),
    );
    foreach($productTypes as $typeId => $typeName)
    {
	  $table[] = array('label' => '#'.$typeId.' '.$typeName);
    }
	$columnLetter = $alphabet[count($table) -1 ];
	$this->PhpExcel->getActiveSheet()
            ->getStyle('A2:A'.Configure::read('Settings.Excel.maxNbRow'))
            ->getProtection()->setLocked(
                PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
            );
	
	$this->PhpExcel->getActiveSheet()
            ->getStyle('D2:'.$columnLetter.Configure::read('Settings.Excel.maxNbRow'))
            ->getProtection()->setLocked(
                PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
            );
	
	// set date format
	$this->PhpExcel->getActiveSheet()
    ->getStyle('A2:A'.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
    );
	
	// set € format
	$this->PhpExcel->getActiveSheet()
    ->getStyle('B2:B'.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
    );
	
	$this->PhpExcel->getActiveSheet()
    ->getStyle('D2:F'.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
    );
	
	$this->PhpExcel->getActiveSheet()
    ->getStyle('H2:'.$columnLetter.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
    );
	
	
	for($i = 2; $i <= Configure::read('Settings.Excel.maxNbRow'); $i++)
	{

        $this->PhpExcel->getActiveSheet()->setCellValue(
            'B' . $i,
            '=if(SUM(D'.$i.':F'.$i.')=0,"",SUM(D'.$i.':F'.$i.'))'
        );
		
		$this->PhpExcel->getActiveSheet()->setCellValue(
            'C' . $i,
            //'=SUM(D'.$i.':F'.$i.')=SUM(H'.$i.':'.$columnLetter.$i.')'
			'=if(B' . $i.'="","",if(B' . $i.'=SUM(H'.$i.':'.$columnLetter.$i.'),"ok","NOK"))'
        );
		
	}

    // add heading with different font and bold text
     $this->PhpExcel->addTableHeader($table, array('name' => 'Cambria', 'bold' => true));
    

    
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
	$filename = 'Comptabilite'.'.xls';
        $this->PhpExcel->output($filename, $writer = 'Excel5');
      }

?>