<?php

namespace app\components\scrapper\scrapperPage;
use app\components\scrapper\ScrapperPage;

class Muvila extends ScrapperPage
{
	private function removeElements()
	{
		//remove the content "side see more"
		$nodes = $this->html->find('.crosslink');
		if(count($nodes)>0){
			foreach($nodes as $node){
				$node->outertext='';
			}
		}
	}
	
	
	public function get()
	{
		//collecting title
		$title =$this->find('.detail-headline h1');

		//remove content unnecessary content
		$this->removeElements();
		
		//collectig content
		$content = '<p>'.$this->find('.detail-img img','outertext').'</p>';
		$content .= $this->findAll('.body-paragraph', 'innertext');
		$content = str_replace('Muvila.com &ndash; ','',$content);

		return ['title'=>$title, 'content'=>$content];
	}
}
