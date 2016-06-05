<?php

namespace app\components\rules;

use app\components\word\Vocabulary;
use app\components\Rule;

class PassiveRule extends Rule
{
	const SENTENCE_OPENING = '%(^|\. |\, )';//symbols for opening sentence
	const SENTENCE_CLOSING = '($|\.)%i'; //symbols for closing sentence
	
	private static function getAdverb($words)
	{
		$adverbs = Vocabulary::conjunctions();
		foreach($adverbs as $adverb){
			if(stripos($words, $adverb)!==false){
				return $adverb;
			}
		}
		return false;
	}
	
	/**
	 * If the words contain adverbs, it will
	 * break into two phrases
	 * @param string $words to broken
	 * @return array if success
	 */
	private static function breakAdverb($words)
	{
		$adverb = self::getAdverb($words);
		if($adverb){
			$phrase = str_ireplace($adverb, '', $words);
			return [$adverb, $phrase];
		}
		
		return [];
	}
	
	private static function isNoPassiveForm($passive)
	{
		$exception = ['merupakan','memunculkan', 'mengenaskan'];
		if(in_array($passive, $exception)){
			return true;
		}
		return false;
	}
	
	public static function rule()
	{
		return self::SENTENCE_OPENING.'([\w\s-`#]+) (\w+) (me(?:[a-z]+)kan) ([\w\s-`#]*)'.self::SENTENCE_CLOSING;
	}
	
	public static function rewrite($match){
		$rawPassive = $match[4];
		
		if(self::isNoPassiveForm($rawPassive)){
			return $rawPassive;
		}
		
		$passive = \app\components\word\VerbMekan::toPassive($rawPassive);
		
		//Is containing auxiliaries
		$auxiliaries = Vocabulary::auxialiaries();
		if(in_array($match[3], $auxiliaries)){
			$subject = $match[5];
			$predicate= $match[3]. ' '.$passive;
			$object = $match[2];
			$extraObject = '';
		}else{
			$subject = $match[5];
			$predicate= $passive;
			$object = $match[2];
			$extraObject = $match[3];
		}
		
		$listAdverb = self::breakAdverb($match[2]);
		if($listAdverb){
			$subject = $listAdverb[0].' '.$subject;
			$predicate = $predicate. ' '.$listAdverb[1];
			$object = '';
		}
		
		$sentence = ucfirst(strtolower($match[1].$subject.' '.$predicate.' '.$object.' '.$extraObject.$match[6]));
		return $sentence; 
	}
}