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
			$adverb  = implode(' ',array_slice($tokens,0,$lastAdverbPosition));
//						var_dump(get_defined_vars(), $adverb);die();
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
		$noun = self::getAdverb($words);
		if($noun){
			$adverb = trim(str_ireplace($noun, '', $words));
			return [$noun, $adverb];
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
		
		$passive = VerbMekan::toPassive($rawPassive);
		
		//Sentence part
		$parts = [];
		
		//if match[3] is verb, then stop it immediately
		//this type of verb can't changed to passive form
		//"saya pergi mengerjakan PR" can't changed to passive voice
		if(Lemma::isVerb($match[3]) && $match[3]!='bisa' && $match[3]!='dapat'){
			return '';
		}
		
		//set each sentence component
		$object = $match[5];
		$predicate = $passive;
		$subject = '{|oleh} '.lcfirst($match[2]);
		$complement = '';
		
		//in case the end of part has adverbs, we will split it, so we only get the object
		//for example: 
		//"Dia membaca buku dengan teliti", "buku" should be object, while "dengan teliti" is complement
		$arrPhrases = self::breakAdverb($object);
		if($arrPhrases){
			$object = $arrPhrases[0];
			$complement = $arrPhrases[1];
		}
		
		//Is the word before predicate auxiliaries?
		//If yes, we should keep it before predicate
		//eg: "Dia bisa membaca buku", should make to "Buku bisa dibaca dia"
		$auxiliaries = Vocabulary::auxialiaries();
		if(in_array($match[3], $auxiliaries)){
			$predicate = $match[3].' '.$predicate;
		}else{
			//if it is not auxiliaries then, this is part of subject
			$subject = $subject.' '.$match[3];
		}
		
		//Is there is adverb before predicate
		//if yes, we should take the adverb to complement
		//"Dia dengan rajin membaca buku itu", "buku itu dibaca dia dengan rajin"
		$arrPhrases = self::breakAdverb($subject);
		if($arrPhrases){
			$subject = $arrPhrases[0];
			$complement = trim($complement.' '.$arrPhrases[1]);
		}
		
		//combining to become a sentence
		$passiveSentence = $object.' '.$predicate.' '.$subject.' '.$complement;
		
		$sentence = ucfirst($match[1].trim($passiveSentence).$match[6]);
		return $sentence; 
	}
}