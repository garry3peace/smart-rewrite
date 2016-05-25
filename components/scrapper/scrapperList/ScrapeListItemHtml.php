<?php
namespace app\components\scrapper\scrapperList;

abstract class ScrapeListItemHtml extends ScrapeListItem
{
	protected abstract function itemClass();
	protected abstract function listClass();
	
	private function getReader()
	{
		$url = $this->getUrl();
		$scrapper = new \app\components\scrapper\Scrapper($url);
		return $scrapper->getHtml();
	}
	
	public function get()
	{
		$html = $this->getReader();
		
		$listRule = $this->listClass();
		$itemRule = $this->itemClass();
		
		$list = $html->find($listRule);
		
		$result = [];
		foreach($list as $item){
			$object = $item->find($itemRule,0);
			$result[] = $object->href;
		}
		
		return $result;
	}
}