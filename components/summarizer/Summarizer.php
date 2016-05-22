<?php

namespace app\components\summarizer;

class Summarizer
{
	//The source sentence for finding keyword and summary
	private $content;
	
	//The keyword in the corresponding sentence
	private $keywords;
	
	//Number of sentence for the summary
	private $numOfResult ; 
	
	public function __construct($content, $numOfResult=5)
	{
		$this->content = $content;
		$this->keywords = [];
		$this->numOfResult = $numOfResult;
		
		//collecting important keyword
		$this->getKeywords();
	}
	
	private function commonWords()
	{
		return ['atau','yang','di','dengan','dan','telah','sudah','tetapi','namun',
			'agar','supaya','jika','kalau','maka','kemudian','ini','itu','lalu',
			'sesudah','sebelum','bukan','tidak','akan','juga','pada','padahal','adalah',
			'dari','jadi','oleh','karena','sebab'];
	}
	
	/**
	 * Return sentence without multiple space.
	 */
	private function removeMultipleSpace()
	{
		$content = $this->content;
		$content = preg_replace('/\s+/', ' ', $content);
		return $content;
	}
	
	/**
	 * Remove all unnecassary symbols
	 * This function for counting keywords and such
	 */
	private function superCleanContent()
	{
		$content = $this->content;
		
		//double br or <p> must be identified as new sentence
		//remove paragraphs
		$content = str_ireplace('</p>', ' ', $content);
		
		//remove <br/>
		$content = preg_replace('%<br[\s]*/>%i', ' ', $content);
		
		//remove html tags
		$content = strip_tags($content);
		
		//remove multi-spaces
		$content = preg_replace('/\s+/', ' ', $content);
		
		//remove symbols
		$content = preg_replace('/[^\d\w\s\.]/i', '', $content);
		
		return $content;
	}
	
	
	private function isCommonWord($word)
	{
		if(in_array($word,$this->commonWords())){
			return true;
		}
		return false;
	}
	
	/**
	 * Find major keyword.
	 */
	private function getKeywords()
	{
		if(count($this->keywords)>0){
			return $this->keywords;
		}
		
		$sentence = strtolower($this->superCleanContent());
//		var_dump($sentence);die();
		$words = explode(' ', $sentence);
		foreach($words as $word)
		{
			$word = trim($word,'.');
			
			if($this->isCommonWord($word)){
				continue;
			}
			
			if(isset($this->keywords[$word])){
				$this->keywords[$word]++;
			}else{
				$this->keywords[$word] = 1;
			}
		}
		
//		arsort($this->keywords);
//		var_dump($this->keywords);die();
		return $this->keywords;
	}
	
	public function setNumOfResult($num)
	{
		$this->numOfResult = $num;
	}
	
	/**
	 * calculate each sentence point
	 */
	private function sentencePoint()
	{
		//SentenceVal will store the total point
		//of the sentence
		$sentenceVal = [];
		
		$sentences = $this->splitSentences();
		foreach($sentences as $sentence){
			$lowercaseSentence = strtolower($sentence);
			$words = explode(' ',$lowercaseSentence);
			
			//Looping each word in sentence and calculate the score
			$score=0;
			foreach($words as $word){
				if($this->isCommonWord($word)){
					continue;
				}
				
				if(!isset($this->keywords[$word])){
					continue;
				}
				
					
				$score += $this->keywords[$word];
			}
			
			$score = $score/count($words);
			
			$value = [
				'sentence'=>$sentence,
				'score'=>$score,
			];
			$sentenceVal[] = $value;
		}
		
		uasort($sentenceVal, function($a, $b) {
			return intval($b['score']*1000) - intval($a['score']*1000);
		});
		
		return $sentenceVal;
	}
	
	/**
	 * Find out which sentence is important.
	 * Count base on number of result
	 */
	private function getKeySentence()
	{
		//return the sentence with scores
		$result = $this->sentencePoint();
		
		$line = [];
		$count = 1;
		foreach($result as $key=>$sentence){
			$line[] = $key;
			$count++;
			if($count > $this->numOfResult){
				break;
			}
		}
		
		return $line;
	}
		
	
	private function splitSentences()
	{
		$content = $this->superCleanContent();
		$sentences = explode('. ',$content);
		return $sentences;
	}
	
	public function summarize(){

		$sentences = $this->splitSentences();
		
		$keySentence = $this->getKeySentence();
		
//		var_dump($keySentence);
//		var_dump($sentences);die('#');
		
		$result = '';
		foreach($sentences as $key=>$sentence){
			if(in_array($key, $keySentence)){
				$result .= $sentence.'. ';
			}
		}
		
		//remove the last dot
		$result = str_replace('.. ', '.',$result);
		
		//strip all html if exists
		$result = strip_tags($result);
		
		return $result;
	}
}