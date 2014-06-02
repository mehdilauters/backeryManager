<?php

App::import('Html');
class MyHtmlHelper extends HtmlHelper{
	
	function link($title, $url = NULL, $options = array(), $confirmMessage = false)
	{
		if(!is_array($url))
		{
			return parent::link($title,'/'.$url.$this->getLinkTitle($title), $options , $confirmMessage);
		}
		else
			return parent::link($title, $url, $options , $confirmMessage );
	}
	
	function getLinkTitle($title)
	{
		return '/'.$this->escapeLink($title);
	}
	
	function escapeLink($title)
	{
		
		$replace=array(' ','ê','é','&','"','\'','(','è','ç','à',')','=','+','~','#','{','[','|','`','\\','^','@',']','î','ï','ô');
			 $by=array('-','e','e','-','-','-','-','e','c','a','-','-','-','-','-','-','-','-','-','-','-','-','-','i','i','o');
			 
		
		return str_replace($replace,$by,$title);
	}


  function getAlphabet()
  {
    return array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
  }
}

?>