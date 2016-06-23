<?php

namespace app\components\scrapper\scrapperPage;
use app\components\scrapper\ScrapperPage;

class Kapanlagi extends ScrapperPage
{
	public function get()
	{
		$html = $this->html;
		$title =$this->find($html, '.entertainment-newsdetail-title-new');
		$content = $this->find($html, '.entertainment-detail-news');
		$content = str_replace('Kapanlagi.com - ','',$content);
		return ['title'=>$title, 'content'=>$content];
	}
}