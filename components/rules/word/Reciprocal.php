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
	 * @param string $pronoun, "saling"
	 * @return string {saling menyerang|serang menyerang}
	 */
	private static function process($word, $pronoun)
	{
		$result = [];
		$baseWord = Lemma::stem($word);
		
		$result[] = $baseWord.' '.\app\components\word\Verb::me($baseWord);
		//add the initial word
		array_unshift($result, "$pronoun `$word`");

		return SpinFormat::generate($result);

	}
}
