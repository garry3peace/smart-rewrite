<?php

namespace app\components\scrapper\scrapperPage;
use app\components\scrapper\ScrapperPage;

class Muvila extends ScrapperPage
{
	public function get()
	{
		$html = $this->html;
		$title =$this->find($html, '.detail-headline h1');
		$content = $this->findAll($html, '.body-paragraph p', 'outertext');
		$content = str_replace('Muvila.com &ndash; ','',$content);
		return [$title, $content];
	}
}