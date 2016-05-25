<?php
namespace app\components\scrapper\scrapperList;

abstract class ScrapeListItemXml extends ScrapeListItem
{
	
	private function getReader()
	{
		$url = $this->getUrl();
		return simplexml_load_file($url);
	}
	
	public function get()
	{
		$xml = $this->getReader();
		
		$result = [];
		foreach ($xml as $entry) {
			foreach ($entry->item as $item) {
				$result[] = (string) $item->link;
			}
		}
		
		return $result;
	}
}