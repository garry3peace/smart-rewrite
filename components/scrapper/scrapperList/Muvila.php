<?php

namespace app\components\scrapper\scrapperList;

use \app\components\scrapper\ScrapeListItem;

class Muvila extends ScrapeListItem
{
	public function getUrl()
	{
		return 'http://www.cnnindonesia.com/film/indeks/57/';
	}
	
	public function getItem()
	{
		return 'h3 a';
	}
	
	public function getList()
	{
		return '.entry';
	}
}