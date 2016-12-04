<?php

namespace app\components\rules\sentence;

use app\components\RegexElement;
use app\components\Rule;
use app\components\word\VerbMekan;
use app\components\word\Vocabulary;
use app\models\Lemma;

class PassiveRule extends Rule
{
	
	private static function tokenizeWord($words)
	{
		$result = explode(' ', $words);
		return $result;
	}
	
	/**
	 * Find the correct adverb in the sentence
	 * @param string $words
	 * @return mixed false if no adverb. If there is than the value
	 */
	private static function getAdverb($words)
	{
		$conjuctions = Vocabulary::conjunctions();
		$wordConjuctions = Vocabulary::wordConjunctions();
		
		$lastAdverbPosition = false;
		$tokens = self::tokenizeWord($words);
		foreach($tokens as $position=>$word){
			$token = strtolower($word);
//			var_dump(get_defined_vars());
			
			//Rule for passive is, word conjunction like "and", "or" only allowed at the front-most sentence
			//while other conjunctions type are allowed in the middle sentence
			if(
				(in_array($token, $conjuctions) && !in_array($token, $wordConjuctions)) || 
				(in_array($token, $wordConjuctions) && $position ==0)
				){
				$lastAdverbPosition = $position;
			}
		}
		
		if($lastAdverbPosition!==false){
			$adverb  = implode(' ',array_slice($tokens,0,$lastAdverbPosition+1));
//						var_dump(get_defined_vars());
			return $adverb;
		}
		
		return false;
	}
	
	/**
	 * If the words contain adverbs, it will
	 * break into two phrases.
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
	
	/**
	 * List of words that people seldom use it in passive form
	 * @param type $passive
	 * @return boolean
	 */
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
		$word = RegexElement::word();
		$phrase = RegexElement::phrase();
		$opening = RegexElement::opening();
		$closing = RegexElement::closing();
		return $opening."($phrase+) ($word) (me(?:[a-z]+)kan) ($phrase*)".$closing;
	}
	
	
	public static function rewrite($match){
		$rawPassive = $match[4];
		
		if(self::isNoPassiveForm($rawPassive)){
			return '';
		}
		
		//If the end part has adverb, rather don't passive, because it is too hard
		if(self::getAdverb($match[5])){
			return '';
		}
		
		$passive = VerbMekan::toPassive($rawPassive);
		
		//Sentence part
		$parts = [];
		
		//if match[3] is verb, then stop it immediately
		if(Lemma::isVerb($match[3]) && $match[3]!='bisa' && $match[3]!='dapat'){
			return '';
		}
		
		//Is containing auxiliaries
		$auxiliaries = Vocabulary::auxialiaries();
		if(in_array($match[3], $auxiliaries)){
			$parts[0] = $match[5];
			$parts[1]= $match[3]. ' '.$passive;
			$parts[2] = '{|oleh} '. $match[2];
			$parts[3]= '';
		}else{
			$parts[0] = $match[5];
			$parts[1]= $passive;
			$parts[2] = $match[2];
			$parts[3]= '{|oleh} '.$match[3];
		}
		
		//Break Adverb in front
		$listAdverb = self::breakAdverb($match[2]);
		if($listAdverb){
			$parts[0] = $listAdverb[0].' '.$parts[0];
			$parts[1] = $parts[1]. ' '.$listAdverb[1];
			$parts[2] = '';
		}
		
		//combining part
		$sentence = '';
		foreach($parts as $part){
			if(empty($part)){
				continue;
			}
			$sentence .= $part.' ';
		}
		
		$sentence = ucfirst($match[1].strtolower(trim($sentence)).$match[6]);
		return $sentence; 
	}
}