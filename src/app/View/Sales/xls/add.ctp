<?php 

// debug($data) 
ob_end_clean();
$this->PhpExcel->createWorksheet();

$sheetId = 0;



// Enum sheet
  $this->PhpExcel->setRow(1);
  $this->PhpExcel->setActiveSheetIndex($sheetId);
  $this->PhpExcel->setWorksheetName('listes');
  $sheetId++;

  
  $i = 0;
  $nbShops = count($shops);
  $nbProducts = count($products);
  
  $max = max($nbShops, $nbProducts);

  
  for($i = 0; $i < $max; $i++)
  {
	$row = array();
	$row[0] = NULL;
	$row[1] = NULL;

	if($i < $nbShops)
	{
		$row[0] = '#'.$shops[$i]['Shop']['id'].' '.$shops[$i]['Shop']['name'];
	}
	if($i < $nbProducts)
	{
		$row[1] = '#'.$products[$i]['Product']['id'].' '.$products[$i]['Product']['name'];
	}
	$this->PhpExcel->addData($row);
  }
  
  $this->PhpExcel->getActiveSheet()->getProtection()->setSheet(true);
  
  
  // data sheet
  
  $this->PhpExcel->createSheet();
  $this->PhpExcel->setRow(1);
  $this->PhpExcel->setActiveSheetIndex($sheetId);
  $this->PhpExcel->setWorksheetName('data');
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
		array('label' => 'Magasin'),
		array('label' => 'Produit'),
		array('label' => 'Valide'),
		array('label' => 'fabriques'),
		array('label' => 'perdus'),
		array('label' => 'Commentaire'),
    );
	
	$this->PhpExcel->addTableHeader($table, array('name' => 'Cambria', 'bold' => true));
	
	$alphabet =   $this->MyHtml->getAlphabet();
	$nbCols = count($table);
	for($i = 0; $i < $nbCols; $i++)
	{
		// $this->PhpExcel->getActiveSheet()->getColumnDimension($alphabet[$i])->setAutoSize(true);
		$this->PhpExcel->getActiveSheet()->getColumnDimension($alphabet[$i])->setWidth(100);
	}
	
	// freeze first row/column
  $this->PhpExcel->getActiveSheet()->freezePane('B2');
	
	$this->PhpExcel->getActiveSheet()
            ->getStyle('A2:C'.Configure::read('Settings.Excel.maxNbRow'))
            ->getProtection()->setLocked(
                PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
            );


	$this->PhpExcel->getActiveSheet()
            ->getStyle('E2:G'.Configure::read('Settings.Excel.maxNbRow'))
            ->getProtection()->setLocked(
                PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
            );

			
	for($i = 2; $i < Configure::read('Settings.Excel.maxNbRow'); $i++)
	{
		$objValidation = $this->PhpExcel->getActiveSheet()->getCell('B'.$i)->getDataValidation();
		$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation->setAllowBlank(true);
		$objValidation->setShowInputMessage(true);
		$objValidation->setShowErrorMessage(true);
		$objValidation->setShowDropDown(true);
		$objValidation->setErrorTitle('Invalide');
		$objValidation->setError('Le magasin n\'existe pas');
		$objValidation->setFormula1('listes!$A$1:$A$'.$nbShops);	
		$this->PhpExcel->getActiveSheet()->getCell('B'.$i)->setDataValidation($objValidation);



		$objValidation = $this->PhpExcel->getActiveSheet()->getCell('C'.$i)->getDataValidation();
		$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation->setAllowBlank(true);
		$objValidation->setShowInputMessage(true);
		$objValidation->setShowErrorMessage(true);
		$objValidation->setShowDropDown(true);
		$objValidation->setErrorTitle('Invalide');
		$objValidation->setError('Le produit n\'existe pas');
		$objValidation->setFormula1('listes!$B$1:$B$'.$nbProducts);	
		$this->PhpExcel->getActiveSheet()->getCell('C'.$i)->setDataValidation($objValidation);



	    $this->PhpExcel->getActiveSheet()->setCellValue(
            'D' . $i,
            '=if(E'.$i.'="","",E'.$i.'-F'.$i.'>0)'
        );
	}

	
	// $objConditional = new PHPExcel_Style_Conditional();
	// $objConditional->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS);
	// $objConditional->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_EQUAL);
	// $objConditional->addCondition('D2');
	// $objConditional->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
	// $objConditional->getStyle()->getFont()->setBold(true);
	// $this->PhpExcel->getActiveSheet()->getStyle('B2')->setConditionalStyles(array($objConditional));
	//$this->PhpExcel->getActiveSheet()->duplicateStyle( $this->PhpExcel->getActiveSheet()->getStyle('B2'), 'B3:B7' );

    // set date format
    $this->PhpExcel->getActiveSheet()
    ->getStyle('A2:A'.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
    );

	// set format
	$this->PhpExcel->getActiveSheet()
    ->getStyle('D2:C'.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
    );

	// set format
	$this->PhpExcel->getActiveSheet()
    ->getStyle('E2:G'.Configure::read('Settings.Excel.maxNbRow'))
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
    );

  $this->PhpExcel->addTableFooter();


    // close table and output
      if( isset($fileName))
      {
	$this->PhpExcel->save($fileName, $writer = 'Excel5');
      }
      else
      {
	$filename = 'Ventes'.'.xls';
        $this->PhpExcel->output($filename, $writer = 'Excel5');
      }

?>