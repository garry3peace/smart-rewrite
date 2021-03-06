<?php

namespace app\components\scrapper\scrapperPage;
use app\components\scrapper\ScrapperPage;

class Cnnindonesia extends ScrapperPage
{
	private function removeElements()
	{
		//remove the content "side see more"
		$nodes = $this->html->find('.text_detail .topiksisip');
		if(count($nodes)>0){
			foreach($nodes as $node){
				$node->outertext='';
			}
		}
		
		//remove link sisip
		$nodes = $this->html->find('.text_detail .linksisip');
		if(count($nodes)>0){
			foreach($nodes as $node){
				$node->outertext='';
			}
		}
		
		//remove the first "cnn-jakarta"
		$node = $this->html->find('.text_detail strong', 0);
		if($node){
			$node->outertext='';
		}
	
		//remove author initial
		$node = $this->html->find('.text_detail b', -1);
		if($node){
			$node->outertext='';
		}
	}
	
	public function get()
	{
		$title = $this->find('.content_detail h1');
		
		$this->removeElements();
		
		//collecting content
		$content = $this->find('.pic_artikel img','outertext');
		$content .= '<br/><br/>';
		$content .= $this->find('.text_detail','innertext');
		
		//Processing
		$content = str_replace('<!--// -->','',$content);
		$content = str_replace('-- ','',$content);
		
		
		$content = \app\components\web\Html::blog($content);
		
		return ['title'=>$title, 'content'=>$content];
		
	}
}