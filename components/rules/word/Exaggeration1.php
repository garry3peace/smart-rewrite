<?php
namespace app\components\rules\word;

use app\components\Rule;
use app\components\SpinFormat;
use app\models\Lemma;

/**
 * Exageration changes certain adjective format.
 * For example "sangat lambat", will become "lambat sekali"
 *
 * @author garry
 */

class Exaggeration1 extends Rule{
	public static function rewrite($match)
	{
		return self::process($match[2], $match[1]);
	}
	
	public static function rule()
	{
		$exaggeration = '(sangat|begitu|sebegitu|sedemikian)';
		$pattern = '/'.$exaggeration.' ([\w]+)/i';
		return $pattern;
	}
	
	/**
	 * This is to generate negation phrase
	 * For example "sangat cantik" will return the "cantik sekali"
	 * @param string $word, "rajin"
	 * @param string $exaggeration, "sangat"
	 * @return string {sangat cantik|cantik sekali}
	 */
	private static function process($word, $exaggeration)
	{
		//If word is not adjective, then we can't change it
		if (!Lemma::isAdjective($word)){
			return $exaggeration.' '.$word;
		}
		
		//convert to 
		$result = [];
		$result[] = $word.' sekali';
		
		//add the initial word
		array_unshift($result, "$exaggeration `$word`");
		

		return SpinFormat::generate($result);
	}
}
