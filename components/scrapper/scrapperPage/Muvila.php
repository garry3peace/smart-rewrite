<?php

namespace app\components\scrapper\scrapperPage;
use app\components\scrapper\ScrapperPage;

class Muvila extends ScrapperPage
{
	public function get()
	{
		$title =$this->find('.detail-headline h1');
		$content = $this->findAll('.body-paragraph p', 'outertext');
		$content = str_replace('Muvila.com &ndash; ','',$content);
		return [$title, $content];
	}
}