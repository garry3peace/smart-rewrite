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
	
	public function __construct($content)
	{
		$this->content = $content;
		$this->keywords = [];
		$this->numOfResult = 5;
		
		//collecting important keyword
		$this->getKeywords();
	}
	
	private function commonWords()
	{
		return ['yang','di','dengan','dan','telah','sudah','tetapi','namun',
			'agar','supaya','jika','kalau','maka','kemudian','ini','itu','lalu',
			'sesudah','sebelum','bukan','tidak','akan'];
	}
	
	/**
	 * Return sentence without multiple space.
	 */
	private function cleanContent()
	{
		$content = $this->content;
		$content = preg_replace('/\s+/', ' ', $content);
		return $content;
	}
	
	/**
	 * Remove all unnecassary symbol and such
	 * This function for counting keywords and such
	 */
	private function superCleanContent()
	{
		$content = $this->content;
		$content = preg_replace('/\s+/', ' ', $content);
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
	
	
	
	/**
	 * calculate each sentence point
	 */
	private function sentencePoint()
	{
		//SentenceVal will store the total point
		//of the sentence
		$sentenceVal = [];
		
		$content = strtolower($this->superCleanContent());
		$sentences = explode('. ',$content);
		foreach($sentences as $sentence){
			$words = explode(' ',$sentence);
			
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
			$value = [
				'sentence'=>$sentence,
				'score'=>$score,
			];
			$sentenceVal[] = $value;
		}
		
		uasort($sentenceVal, function($a, $b) {
			return $b['score'] - $a['score'];
		});
		
		return $sentenceVal;
	}
	
	/**
	 * Find out which sentence is important.
	 * Count base on number of result
	 */
	private function getKeySentence()
	{
		//return the sentence scores
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
		
	public function summarize(){
		$content = $this->cleanContent();
		
		$sentences = explode('. ', $content);
		$keySentence = $this->getKeySentence();
		
		$result = '';
		foreach($sentences as $key=>$sentence){
			if(in_array($key, $keySentence)){
				$result .= $sentence.'. ';
			}
		}
		
		//remove the last dot
		$result = str_replace('.. ', '.',$result);
		
		return $result;
	}
}