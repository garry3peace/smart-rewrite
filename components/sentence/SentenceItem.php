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
		return SpinFormat::generate($spinList);
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