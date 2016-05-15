<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components\rewriter;

use app\components\rewriter\Rewriter;

class ParagraphRewriter extends Rewriter
{
	private $config; 
	
	private $content; //the content of paragraphs to rewrite
	private $arrParagraphs; //the content separated in array paragraphs
	
	private $firstParagraph = '';
	private $lastParagraph = '';
	
	private $isText = null; //flag to determine the content is plaintext or html
	
	public function __construct($content, $config=null)
	{
		$this->content = $content;
		$this->config = $config;
		$this->arrParagraphs = $this->getArrParagraphs();
		$this->firstParagraph = $this->arrParagraphs[0];
		$this->lastParagraph = end($this->arrParagraphs);
	}
	
	private function getArrParagraphs()
	{
		if(!empty($this->arrParagraphs)){
			return $this->arrParagraphs;
		}
		
		if($this->isText()){
			$result = $this->getTextParagraph();
		}else{
			$result = $this->getHtmlParagraph();
		}
		
		$this->arrParagraphs = $result;
		return $this->arrParagraphs;
	}
	
	private function merge()
	{
		$result = $this->arrParagraphs;
		
		array_unshift($this->arrParagraphs, $this->firstParagraph);
		array_push($this->arrParagraphs, $this->lastParagraph);
		
		if($this->isText()){
			$text = $this->mergeText();
		}else{
			$text = $this->mergeHtml();
		}
		return $text;
	}
	
	private function getTextParagraph()
	{
		$paragraph = $this->content;
		$paragraph = preg_replace('%$[\s]*$%m','<p>',$paragraph);
		$paragraph = str_replace('<p><p>', '<p>', $paragraph);
		$paragraph = trim($paragraph,'<p>');
		
		$paragraphs = explode('<p>', $paragraph);
		//		print_r($paragraphs);die();
		
		return $paragraphs;
	}
	
	private function getHtmlParagraph()
	{
		//we change all paragraph related into single symbol.
		$paragraph = $this->content;
		$paragraph = str_replace('</p>', '', $paragraph);
		$paragraph = preg_replace('%<br/>[\s]*<br/>%', '<p>', $paragraph);
		
		$paragraphs = explode('<p>', $paragraph);
		return $paragraphs;
	}
	
	/**
	 * Shuffling for text
	 */
	private function mergeText()
	{
		$result = implode("\n", $this->arrParagraphs);		
		return $result;
	}
	
	private function mergeHtml()
	{
		$result = '';
		foreach($this->arrParagraphs as $item){
			$result .= '<p>'.$item.'</p>';
		}
		return $result;
	}
	
	private function isText()
	{
		if($this->isText!==null){
			return $this->isText;
		}
		
		if(strpos('<p>',$this->content)!==false){
			$this->isText = false;
		}
		$this->isText = true;
		
		return $this->isText;
	}
	
	private function exclude($paragraphs)
	{
		return array_slice($paragraphs,1,-1);
	}
	
	public function rearrange()
	{
		//if exclude front and last paragraph is enable, then must exclude them
		if($this->config->is('paragraph_exclude_first_last')){
			$this->arrParagraphs = $this->exclude($this->arrParagraphs);
		}
		
		shuffle($this->arrParagraphs);
		
		$result = $this->merge();
		
		return $result;
	}
}
