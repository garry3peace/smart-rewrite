<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components\rewriter;

use app\components\rewriter\Rewriter;
use app\components\Config;

class ParagraphRewriter extends Rewriter
{
	private $config; 
	
	private $content; //the content of paragraphs to rewrite
	private $paragraph; //array of all paragraphs. each array item represent a paragraph
	
	//array of paragraph that is going to shuffle. used in rearrange paragraph function
	private $shuffleParagraph; 
	
	private $firstParagraph = '';
	private $lastParagraph = '';
	
	private $isText = null; //flag to determine the content is plaintext or html
	
	public function __construct($content, $config=null)
	{
		if($config==null){
			$config = new Config(['paragraph_exclude'=>'1;2;-2;-1']);
		}
		
		$this->content = $content;
		$this->config = $config;
		$this->paragraph = $this->getArrParagraphs();
		
		$this->shuffleParagraph = $this->paragraph;
		$this->firstParagraph = $this->paragraph[0];
		$this->lastParagraph = end($this->paragraph);
	}
	
	private function getArrParagraphs()
	{
		if(!empty($this->paragraph)){
			return $this->paragraph;
		}
		
		if($this->isText()){
			$result = $this->getTextParagraph();
		}else{
			$result = $this->getHtmlParagraph();
		}
		
		$this->paragraph = $result;
		return $this->paragraph;
	}
	
	private function getExcludedParagraph()
	{
		$paragraphExclude = $this->config->getArray('paragraph_exclude');
		$count = count($this->paragraph);

		$result=[];
		foreach($paragraphExclude as $position){
			if($position <0){
				$position = $count+$position+1;
			}
			//reduce 1, to match the array system (which start from zero)
			$position = $position-1;
			$result[] = $position;
		}
		return $result;
	}
	/**
	 * Combining all the array of paragraphs into text
	 * @return string
	 */
	private function merge()
	{
		$paragraphExclude = $this->getExcludedParagraph();
		foreach($paragraphExclude as $position){
			//inserting paragraph into the shuffle paragraph
			array_splice( $this->shuffleParagraph, $position, 0, $this->paragraph[$position]);
		}
		
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
		$paragraph = preg_replace('%<br[\s]*/>[\s]*<br[\s]*/>%', '<p>', $paragraph);
		
		$paragraphs = explode('<p>', $paragraph);
		
		return $paragraphs;
	}
	
	/**
	 * Shuffling for text
	 */
	private function mergeText()
	{
		$result = implode("\n", $this->shuffleParagraph);		
		return $result;
	}
	
	private function mergeHtml()
	{
		$result = '';
		foreach($this->shuffleParagraph as $item){
			$result .= '<p>'.$item.'</p>';
		}
		return $result;
	}
	
	private function isText()
	{
		if($this->isText!==null){
			return $this->isText;
		}
		
		if($this->content != strip_tags($this->content)){
			$this->isText = false;
		}else{
			$this->isText = true;
		}
		
		return $this->isText;
	}
	
	/**
	 * execute the exclusion of the paragraph
	 * that excluded in Config
	 */
	private function exclude()
	{
		$paragraphExclude = $this->getExcludedParagraph();
		foreach($paragraphExclude as $index){
			unset($this->shuffleParagraph[$index]);
		}
	}
	
	/**
	 * Randomize/shuffle the paragraph position
	 * Return as text
	 * @return string
	 */
	public function rearrange()
	{
		//if exclude front and last paragraph is enable, then must exclude them
		if($this->config->is('paragraph_exclude')){
			$this->exclude($this->config->getArray('paragraph_exclude'));
		}
		
		shuffle($this->shuffleParagraph);
		
		$result = $this->merge();
		
		return $result;
	}
}
