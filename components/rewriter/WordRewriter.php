<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components\rewriter;

use app\components\file\File;
use app\components\rewriter\Rewriter;
use Yii;

class WordRewriter extends Rewriter
{
	private static $cache;
	
	public static function rewrite($sentence)
	{
		//processing all word rules, such as negation phrase, very(exaggeration)
		$sentence = self::parseWordRules($sentence);
		
		//processing word
		$sentence = self::parseWord($sentence);
		
		//finalizing
		$sentence = self::finalize($sentence);
		
		return $sentence;
	}
	
	/**
	 * Load rules that located in 
	 * \app\components\rules\word\*
	 * Currently this is simple function. You may want to split this function to 
	 * other class files (names WordRewriterRule if it is getting complex)
	 */
	private static function autoLoad()
	{
		$files = File::loadDir(Yii::getAlias('@app/components/rules/word'), $filenameOnly=true);
		
		$result = [];
		foreach($files as $class){
			//calling static function rule like this: \app\components\rules\PassiveRule::rule()
			$rule = call_user_func('\app\components\rules\word\\'.$class. '::rule');
			//calling function func:\app\components\rules\PassiveRule::rewrite'
			$process = '\app\components\rules\word\\'.$class.'::rewrite';
			
			$result[] = 
				[
					'rule'=>$rule,
					'process'=>$process,
				];
		}
		return $result;
	}
	
	/**
	 * Run all the word rule in the sentence
	 */
	private static function parseWordRules($sentence)
	{
		$listRules = self::autoLoad();
		
		foreach($listRules as $item)
		{
			$rule = $item['rule'];
			$rewriteFunction = $item['process'];
			
			//The regex find negation phrases
			if (preg_match_all($rule, $sentence, $matches, PREG_SET_ORDER)){
				foreach ($matches as $match){
					$realPhrase = $match[0];
					$result = call_user_func($rewriteFunction, $match);
					$sentence = str_replace($realPhrase, $result, $sentence);
				}
			}
		}
		return $sentence;
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
	 * Simple function to get spinword
	 * @param array $matches
	 * @return string
	 */
	private static function spin($matches)
	{
		return \app\components\WordHelper::getSpinWord($matches[0]);
	}
	
	/**
	 * Finding the synonym of each word
	 * 
	 * @param string $word
	 * @return string $word
	 */
	private static function spinWord($word)
	{
		$pattern = '/([\w-`]+)/i';
		
		//The regex is for ignoring the symbol, so the system will
		//only see the word.
		return preg_replace_callback($pattern, "self::spin", $word);
	}
	
	
	/**
	 * Simple function to remove flag character
	 * @param Array $matches
	 * @return string
	 */
	private static function trim($matches)
	{
		return trim($matches[0], '`');
	}
	
	private static function finalize($word)
	{
		//remove marked from the word
		$pattern = '/`[\w\s-]+`/i';
		
		//The regex is for ignoring the symbol, so the system will
		//only see the word.
		return preg_replace_callback($pattern, "self::trim", $word);
	}
	
}
