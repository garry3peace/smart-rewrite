<?php

namespace app\components\scrapper\scrapperList;

use \app\components\scrapper\ScrapeListItem;

class Muvila extends ScrapeListItem
{
	public function getUrl()
	{
		return 'http://www.muvila.com/rss/';
	}
	
	public function getItem()
	{
		return 'link';
	}
	
	public function getList()
	{
		return 'item';
	}
}