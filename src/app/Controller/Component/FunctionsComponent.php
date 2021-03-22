 
<?php
App::uses('Component', 'Controller');
class FunctionsComponent extends Component{
  
  function noAccent($string)
  {
    $replace=	array('ê','é','è','ç','à','î','ï','ü');
    $by=		array('e','e','e','c','a','i','i','u');
    return str_replace($replace,$by,$string);
  }
  
  
  
  function getLinkTitle($title)
  {
    return '---'.$this->escapeLink($title);
  }
  
  function escapeLink($title)
  {
    
    $replace=array(' ','ê','é','&','"','\'','(','è','ç','à',')','=','+','~','#','{','[','|','`','\\','^','@',']','î','ï');
       $by=array('-','e','e','-','-','-','-','e','c','a','-','-','-','-','-','-','-','-','-','-','-','-','-','i','i');
       
    
    return str_replace($replace,$by,$title);
  }
  
  function cakeDateTimeFormHelpToDateTime($dateTime)
  {
    $pm = 0;
    if($dateTime['meridian'] == 'pm')
      $pm = 12;
    $timestamp = mktime ($dateTime['hour'] + $pm, $dateTime['min'], 0, $dateTime['month'], $dateTime['day'], $dateTime['year']);
    
    $dateTime = new DateTime();
    $dateTime->setTimestamp($timestamp);
    return $dateTime;
  }
  

  /**
   *  convert date from string to date time.
   *  Supported input strings:
   *  "dd/mm/aaaa" or "dd/mm/aaaa hh:mm"
   */
  
  function viewDateToDateTime($dateString, $fullYear = true)
  {
    $dateString = trim($dateString);
    $year = 'y';
    if($fullYear)
    {
      $year = 'Y';
    }
    //->format('Y-m-d H:i')
    $dateTime = DateTime::createFromFormat ( 'd/m/'.$year.' H:i' , $dateString );
    if($dateTime == false)
    {
    	$dateTime = DateTime::createFromFormat ( 'd/m/'.$year.' H:i' , $dateString.' 00:00' );
    }
    if(!$dateTime)
    {
      debug($dateString);
    }
    return  $dateTime;
  }

  function getAlphabet()
  {
    return array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
  }
}
?>