<?php
namespace app\components\sentence;


use app\components\SpinFormat;

/**
 * SentenceItem is for storing more than one alternate
 * form of the sentence with the same meaning
 * 
 */
class SentenceItem
{
	//store the real sentence
	public $real;
	
	//store the alternate sentence
	public $alternates =[];
	
	/**
	 * For the starter, set the real sentence
	 * @param type $real
	 */
	public function __construct($real)
	{
		$this->real = $real;
	}
	
	/**
	 * Return the spin format from the real and alternate
	 */
	public function getSpin()
	{
		$spinList = $this->getAllSentence();
		
		//If there are alternates then return in spinformat, 
		//if not then return the real sentence
		if(count($spinList)>1){
			return SpinFormat::generate($spinList);
		}else{
			return $this->real;
		}
	}
	
	/**
	 * Get all sentence, include the real.
	 * @return array 
	 */
	public function getAllSentence()
	{
		$realSentence = $this->real;
		$alternates = $this->alternates;
		$all = array_merge([$realSentence], $alternates);
		
		return $all;
	}
}