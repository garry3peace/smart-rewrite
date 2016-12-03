<?php
namespace app\components\rules\word;

use app\components\Rule;
use app\components\SpinFormat;
use app\models\Antonym;

/**
 * Negation is change the negation words format to its non-negation form
 * For example "tidak cepat", will have "lambat" counterpart
 *
 * @author garry
 */

class Negation extends Rule{
	public static function rewrite($match)
	{
		return self::getNegationSpinWord($match[2], $match[1]);
	}
	
	public static function rule()
	{
		$negationWord = '(tidak|tak|nggak|gak)';
		$pattern = '/'.$negationWord.' ([\w]+)/i';
		return $pattern;
	}
	
	/**
	 * This is to generate negation phrase
	 * For example "tidak rajin" will return the {tidak rajin|malas}
	 * @param string $word, "rajin"
	 * @param string $negationWord, "tidak"
	 * @return string {tidak rajin|malas}
	 */
	private static function getNegationSpinWord($word, $negationWord)
	{
		$words = Antonym::get($word, false);
		
		//If it was empty then just directly return the word
		if(empty($words)){
			return $negationWord.' '.$word;
		}
		
		//mark the antonym so they won't be antonymize later
		$result = [];
		foreach($words as $antonym){
			$result[] = "`$antonym`";
		}
		
		//add the initial word
		array_unshift($result, "$negationWord `$word`");
		

		return SpinFormat::generate($result);
	}
}
