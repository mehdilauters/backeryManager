<?php

App::uses('Mysql', 'Model/Datasource/Database');

class MyMysql extends Mysql {
  public function backup($demo = true, $tables = '*')
  {
    $return = '';
    $databaseName = $this->getSchemaName();


    // Do a short header
     $return .= '-- Database: `' . $databaseName . '`' . "\n";
     $return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";
    $return .= "SET FOREIGN_KEY_CHECKS = 0;\n";


    if ($tables == '*') {
        $tables = array();
        $result = $this->query('SHOW TABLES');
        foreach($result as $resultKey => $resultValue){
            $tables[] = current($resultValue['TABLE_NAMES']);
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

	$tablePrefix = '';
	if($demo)
	{
		$tablePrefix = Configure::read('Settings.demo.dbPrefix');
	}

    // Run through all the tables
    
    $db = ConnectionManager::getDataSource('default');
    foreach ($tables as $table) {
        if ( !( strpos($table, $db->config['prefix']) !== false) ) {
          continue;
        }
	$queryEnd = '';
	if($demo && in_array($table, array('sales', 'results', 'results_entries')))
	{
	    $queryEnd = ' order by date desc limit '.Configure::read('Settings.demo.limit');
	}
        $tableData = $this->query('SELECT * FROM ' . $table.$queryEnd);

        $return .= 'DROP TABLE IF EXISTS ' .$tablePrefix. $table . ';';
        $createTableResult = $this->query('SHOW CREATE TABLE ' . $table);
        $createTableEntry = current(current($createTableResult));
	$create = $createTableEntry['Create Table'];
	if($demo)
	{
	  $create = str_replace('CREATE TABLE `'.$table.'`','CREATE TABLE `'.$tablePrefix.$table.'`', $createTableEntry['Create Table']);
	  $create = str_replace('`fk_','`fk_'.$tablePrefix, $create);
	  $create = str_replace('REFERENCES `','REFERENCES `'.$tablePrefix, $create);
	}

        $return .= "\n\n" . $create . ";\n\n";


        // Output the table data
        foreach($tableData as $tableDataIndex => $tableDataDetails) {

            $return .= 'INSERT INTO ' . $tablePrefix.$table . ' VALUES(';

            foreach($tableDataDetails[$table] as $dataKey => $dataValue) {

                if(is_null($dataValue)){
                    $escapedDataValue = 'NULL';
                }
                else {
                    // Convert the encoding
                    $escapedDataValue = $dataValue;

                    // Escape any apostrophes using the datasource of the model.
                    $escapedDataValue = $this->value($escapedDataValue);
                }

                $tableDataDetails[$table][$dataKey] = $escapedDataValue;
            }
            $return .= implode(',', $tableDataDetails[$table]);

            $return .= ");\n";
        }

        $return .= "\n\n\n";
    }
    $return .= "SET FOREIGN_KEY_CHECKS = 1;\n";
    return $return;
  }
} 
?>