<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components;

use app\components\SpinFormat;
use app\components\Rewriter;

class WordRewriter extends Rewriter
{
	private static $cache;
	
	public static function rewrite($sentence)
	{
		//processing negation phrase
		$sentence = self::parseNegationPhrase($sentence);
		
		//processing word
		$sentence = self::parseWord($sentence);
		
		//finalizing
		$sentence = self::finalize($sentence);
		
		return $sentence;
	}
	

	private static function parseNegationPhrase($sentence)
	{
		$negationWord = '(tidak|tak|nggak|gak)';
		$pattern = '/'.$negationWord.' ([\w]+)/ie';
		//$replacement = "self::getAntonymSpinWord('$1')";
		
		//The regex find negation phrases
		if (preg_match_all($pattern, $sentence, $matches, PREG_SET_ORDER)){
			foreach ($matches as $match){
				$realPhrase = $match[0];
				$result = self::getNegationSpinWord($match[2], $match[1]);
				$sentence = str_replace($realPhrase, $result, $sentence);
			}
		}
		
		return $sentence;
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
		$words = \app\models\Antonym::get($word, false);
		
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
	
	
	/**
	 * Parsing text and replace registered word into spin text format
	 * @param string $sentence
	 * @return string sentence
	 */
	private static function parseWord($sentence)
	{
		//explode all sentences into words
		$words = explode(' ', $sentence);
		
		foreach($words as $key=>$word){
			$words[$key] = self::spinWord($word);
		}
		
		return implode(' ',$words);
	}
	
	
	/**
	 * Finding the negation phrases
	 * that could possibly set as antonym
	 * 
	 * @param string $word
	 * @return string $word
	 */
	private static function spinNegationPhrase($word)
	{
		$negationWord = '(tidak|tak|nggak|gak)';
		$pattern = '/'.$negationWord.' ([\w\s-]+)/ie';
		$replacement = "self::getAntonymSpinWord('$1')";
		
		//The regex is for ignoring the symbol, so the system will
		//only see the word.
		return preg_replace($pattern, $replacement, $word);
	}
	
	
	/**
	 * Finding the synonym of each word
	 * 
	 * @param string $word
	 * @return string $word
	 */
	private static function spinWord($word)
	{
		$pattern = '/([\w-`]+)/ie';
		$replacement = "app\components\WordHelper::getSpinWord('$0')";
		
		//The regex is for ignoring the symbol, so the system will
		//only see the word.
		return preg_replace($pattern, $replacement, $word);
	}
	
	private static function finalize($word)
	{
		//remove marked from the word
		$pattern = '/`[\w\s-]+`/ie';
		$replacement = "trim('$0','`')";
		
		//The regex is for ignoring the symbol, so the system will
		//only see the word.
		return preg_replace($pattern, $replacement, $word);
	}
	
}
