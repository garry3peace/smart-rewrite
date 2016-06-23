<?php

namespace app\components\scrapper\scrapperList;

class Muvila extends ScrapeListItemXml
{
	protected function getUrl()
	{
		return 'http://www.muvila.com/rss/';
	}
}