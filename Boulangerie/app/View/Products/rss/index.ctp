<?php
$this->set('channelData', array(
    'title' => __("Les produits du jour"),
    'link' => $this->Html->url('/', true),
    'description' => __("Les produits du jour"),
    'language' => 'fr-fr'));
	
	
	foreach ($products as $product) {
	
	if($product['Product']['produced_today'] != 0)
	{
		$postLink = array(
					'controller' => 'produits',
					'action' => 'details', $product['Product']['id'].$this->MyHtml->getLinkTitle($product['Product']['name'])
					);

		$bodyText = $this->element('Products/Preview', array('product'=>$product));
		//$bodyText = $product['Product']['description'];
		// Retire & échappe tout HTML pour être sûr que le contenu va être validé.
		/*$bodyText = h(strip_tags($text));
		$bodyText = $this->Text->truncate($bodyText, 400, array(
			'ending' => '...',
			'exact'  => true,
			'html'   => true,
		));*/

		echo  $this->Rss->item(array(), array(
			'title' => $product['Product']['name'],
			'link' => $postLink,
			'guid' => array('url' => $postLink, 'isPermaLink' => 'true'),
			'description' => $bodyText,
			'pubDate' => date('c')
		));
	}
}
	
	
?>