<?php

App::import('Html');
class MyHtmlHelper extends HtmlHelper{
	
	function link($title, $url = NULL, $options = array(), $confirmMessage = false)
	{
		if(!is_array($url))
		{
			return parent::link($title,'/'.$url.$this->getLinkTitle($urlTitle), $options , $confirmMessage);
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
}

?>