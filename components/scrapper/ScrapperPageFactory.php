<?php

namespace app\components\scrapper;

/**
 * Class specifically to get the right class of ScrapperPage
 * of the given $url
 */
class ScrapperPageFactory {
	public static function get($url)
	{
		$domain = new \app\components\web\Domain($url);
		$className = $domain->getClassName();
		
		$itemClass = '\app\components\scrapper\scrapperPage\\'.$className;
		return new $itemClass($url);
	}
}