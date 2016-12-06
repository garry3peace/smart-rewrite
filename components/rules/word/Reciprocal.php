<?php
namespace app\components\rules\word;

use app\components\Rule;
use app\components\SpinFormat;
use app\models\Lemma;

/**
 * Rewrite reciprocal form
 * For example "saling menyerang", will become "serang-menyerang"
 *
 * @author garry
 */

class Reciprocal extends Rule{
	public static function rewrite($match)
	{
		return self::process($match[2], $match[1]);
	}
	
	public static function rule()
	{
		$reciprocal = '(saling)';
		$pattern = '/'.$reciprocal.' ([\w]+)/i';
		return $pattern;
	}
	
	/**
	 * This is to generate negation phrase
	 * For example "saling menyerang" will return the "serang-menyerang"
	 * @param string $word, "serang"
	 * @param string $exaggeration, "saling"
	 * @return string {saling menyerang|serang menyerang}
	 */
	private static function process($word, $exaggeration)
	{
		$baseWord = Lemma::getBase($word);
		
		//If word is not adjective, then we can't change it
		if (!Lemma::isVerb($baseWord)){
			return '';
		}
		
		
		
		//convert to 
		$result = [];
		$result[] = $word.' sekali';
		
		//add the initial word
		array_unshift($result, "$exaggeration `$word`");
		

		return SpinFormat::generate($result);
	}
}
