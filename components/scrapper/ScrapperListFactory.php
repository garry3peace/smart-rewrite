<?php

namespace app\components\scrapper;

/**
 * Class specifically to get the right class of ScrapperList
 * of the given $url
 */
class ScrapperListFactory {
	public static function get($url)
	{
		$domain = new \app\components\web\Domain($url);
		$className = $domain->getClassName();
		
		$itemClass = '\app\components\scrapper\scrapperList\\'.$className;
		return new $itemClass($url);
	}
}