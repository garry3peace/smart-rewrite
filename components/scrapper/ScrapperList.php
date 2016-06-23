<?php

namespace app\components\scrapper;

class ScrapperList
{		
	//Item class, that tell how to retrieve the data
	private $scrapeListItem;
	
	/**
	 * function to setup the value of scrapper
	 * @param string $url
	 */
	public function __construct($url)
	{
		//load the appropriate itemclass
		$scrapeItem = \app\components\scrapper\ScrapperListFactory::get($url);
		$this->scrapeListItem = $scrapeItem;
		
	}
	
	public function get()
	{
		$scrapeItem = $this->scrapeListItem ;
		return $scrapeItem->get();
	}
	
}