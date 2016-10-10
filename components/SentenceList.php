<?php

namespace app\components;

/**
 * SentenceList containing several SentenceItem. 
 * SentenceList is for storing paragraphs or articles.
 */
class SentenceList
{
	//The source text
	private $sentence;
	
	//list of SentenceItem objects
	private $list;
	
	/**
	 * Setup value for SentenceList
	 * If the parameter is string then it will set the $sentence
	 * While if the given parameter is array or SentenceItem, then it will set to $list
	 * @param mixed $value 
	 */
	public function __construct($value)
	{
		if (is_array($value)){
			$this->setList($value);
		}else if(is_object($value) && get_class($value)=='SentenceItem'){
				$value = [$value];
		}else{
			$this->setSentence($value);
		}
		
	}
	
	private function setSentence($sentence)
	{
		$this->sentence = $sentence;
		$this->tokenize();
	}
	
	private function setList($list)
	{
		foreach($list as $item){
			$this->list[] = $item;
		}
	}
	
	/**
	 * Split group of sentences (paragraphs, articles) 
	 * into separated sentence.
	 */
	private function tokenize()
	{
		$list = preg_split('/(?<=[.?!;:])\s+/', $this->sentence, -1, PREG_SPLIT_NO_EMPTY);
		
		$result = [];
		foreach($list as $string){
			$result[] = new \app\components\sentence\SentenceItem($string);
		}
		$this->list = $result;
	}

	
	public function getList()
	{
		return $this->list;
	}
	
	/**
	 * Get spin format of all the sentenceItem and combine
	 * them into one string sentence
	 * @return string
	 */
	public function getSpin()
	{
		if(empty($this->list)){
			return '';
		}
		
		$result = '';
		foreach($this->list as $item){
			$result .= $item->getSpin().' ';
		}
		
		return $result;
	}

}

