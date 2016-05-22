<?php

namespace app\components\scrapper;
use app\components\scrapper\Scrapper;

class ScrapperList extends Scrapper
{		
	//Item class, that tell how to retrieve the data
	private $scrapeListItem;
	
	/**
	 * function to setup the value of scrapper
	 * @param string $url
	 */
	public function __construct($url)
	{
		//temporary set the variable to process
		$this->url = $url;
		
		//load the appropriate itemclass
		$scrapeItem = \app\components\scrapper\ScrapperListFactory::get($url);
		
		$this->scrapeListItem = $scrapeItem;
		
		$this->url = $scrapeItem->getUrl();

		parent::__construct($this->url);
	}
	
	private function getList()
	{
		$scrapeItem = $this->scrapeListItem ;
			
		$rule = $scrapeItem->getList();
		$list = $this->html->find($rule);
		
		return $list;
	}
	
	public function get()
	{
		$list = $this->getList();
		
		$result = [];
		foreach($list as $item){
			$itemRule = $this->scrapeListItem->getItem();
			$object = $item->find($itemRule,0);
			$result[] = $object->href;
		}
		
		return $result;
	}
}