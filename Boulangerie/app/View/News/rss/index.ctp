<?php
$this->set('channelData', array(
    'title' => __("Les news"),
    'link' => $this->Html->url('/', true),
    'description' => __("Les news"),
    'language' => 'fr-fr'));
	
	
	 foreach ($news as $new) {
	// debug($new);
	
		 $postLink = array(
					'controller' => 'pages',
					'action' => 'home'
					);

		 $bodyText = $new['details'];
	////	$bodyText = $product['Product']['description'];
	////	Retire & �chappe tout HTML pour �tre s�r que le contenu va �tre valid�.
		// /*$bodyText = h(strip_tags($text));
		// $bodyText = $this->Text->truncate($bodyText, 400, array(
			// 'ending' => '...',
			// 'exact'  => true,
			// 'html'   => true,
		// ));*/

		 echo  $this->Rss->item(array(), array(
			'title' => $new['title'],
			'link' => $postLink,
			'guid' => array('url' => $postLink, 'isPermaLink' => 'true'),
			'description' => $bodyText,
			'pubDate' => $new['start']
		)); 
}
	
	
?>