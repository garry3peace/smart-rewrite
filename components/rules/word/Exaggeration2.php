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

class Exaggeration2 extends Rule{
	public static function rewrite($match)
	{
		return self::process($match[1], $match[2]);
	}
	
	public static function rule()
	{
		$exaggeration = '(sekali)';
		$pattern = '/([\w]+) '.$exaggeration.'/i';
		return $pattern;
	}
	
	/**
	 * This is to generate negation phrase
	 * For example "cantik sekali" will return the "sangat cantik"
	 * @param string $word, "rajin"
	 * @param string $exaggeration, "sangat"
	 * @return string {sangat cantik|cantik sekali}
	 */
	private static function process($word, $exaggeration)
	{
		//If word is not adjective, then we can't change it
		if (!Lemma::isAdjective($word)){
			return $word.' '.$exaggeration;
		}
		
		//convert to 
		$result = [];
		$result[] = 'sangat '.$word;
		
		//add the initial word
		array_unshift($result, "`$word` $exaggeration");
		

		return SpinFormat::generate($result);
	}
}
