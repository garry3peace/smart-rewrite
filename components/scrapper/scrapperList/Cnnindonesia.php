<?php

namespace app\components\scrapper\scrapperList;

class Cnnindonesia extends ScrapeListItemHtml
{
	protected function getUrl()
	{
		return 'http://www.cnnindonesia.com/film/indeks/57/';
	}
	
	protected function itemClass()
	{
		return 'h3 a';
	}
	
	protected function listClass()
	{
		return '.list_indeks li';
	}
}