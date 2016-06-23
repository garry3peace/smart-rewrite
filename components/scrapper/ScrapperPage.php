<?php
namespace app\components\scrapper;

use \app\components\scrapper\Scrapper;
use \app\components\scrapper\SimpleHtml;

class ScrapperPage extends Scrapper
{
	/**
	 * execute how to get content based on domain
	 * @param string $domain
	 * @param SimpleHtml $html
	 * @return type
	 */
	public function get()
	{
		$title = $content = '';
		return ['title'=>$title, 'content'=>$content];
	}	
}