<?php

namespace app\components\scrapper\scrapperPage;
use app\components\scrapper\ScrapperPage;

class Wowkeren extends ScrapperPage
{
	public function get()
	{
		
		$html = $this->html;
		$title =$this->find($html, '#JudulHalaman h1');
		
		//remove link
		$nodes = $html->find('#IsiBerita .content p a');
		foreach($nodes as $node){
			$node->innertext=$node->plaintext;
		}
		
		$content = $this->find($html, '#IsiBerita .content p','innertext');
		
		//remove brand
		$content = str_replace('<strong>WowKeren.com</strong> - ','',$content);
		
		return ['title'=>$title, 'content'=>$content];
		
	}
}