<?php

/* 
 * SpinFormat to manage spin text format
 */

namespace app\components;

use app\components\rewriter\ParagraphRewriter;
use app\components\rewriter\SentenceRewriter;
use app\components\rewriter\PhraseRewriter;
use app\components\rewriter\WordRewriter;
use Yii;

class Sentence 
{	
	const SYMBOL = '.",';//The symbol that can be ignored
	const SENTENCE_OPENING = '%(?:^|\.)';//symbols for opening sentence
	const SENTENCE_CLOSING = '(?:$|\.)%i'; //symbols for closing sentence
	
	private static $config; //Config Object

	
	/**
	 * Store the submitted content into the log
	 */
	private static function log($sentence, $config)
	{
		$log = new \app\models\Log;
		return $log->add($sentence, $config);
	}
	
	public static function parse($sentence, $config)
	{
		//loading the Config object
		self::$config = $config;
		
		//saving into log
		self::log($sentence, $config);
		
		//exclude all execptions
		$sentence = self::exclude($sentence);
		
		//processing paragraph
		if($config->is('paragraph')){
			$paragraphWriter = new ParagraphRewriter($sentence,self::$config);
			$sentence = $paragraphWriter->rearrange($sentence);
		}
		//processing rewrite sentence
		$sentence = SentenceRewriter::rewrite($sentence);
		//processing phrase
		$sentence = PhraseRewriter::rewrite($sentence);
		//processing word
		$sentence = WordRewriter::rewrite($sentence);
		
		$sentence = self::finalize($sentence);
		
		return $sentence;
	}
	
	
	
	/**
	 * Excluding all the words so it will not processed
	 * All excluded words will be stored in the cache
	 * which will be retrieved later in finalize()
	 * @param type $sentence
	 * @return type
	 */
	private static function exclude($sentence)
	{
		//Excluding text that is html link/src
		$regex = '%(<(?i:img|a)[\w\s-\.\"\=\<\>\:./]*?>)%';
		$sentence = preg_replace_callback($regex,function ($match){
			$word = $match[0];
			$target = $match[1];
			return Yii::$app->wordCache->store($target, $word);
		},$sentence);
		
		//Excluding names. Names always uppercase and most of it doesn't at in front
		$regex = '%\b([A-Z]\w+)\b%';
		$sentence = preg_replace_callback($regex,function ($match){
			$word = $match[0];
			$target = $match[1];
			return Yii::$app->wordCache->store($target, $word);
		},$sentence);
		
		//Checking the database
		$exclusions = \app\models\Exclusion::find()->all();
		foreach($exclusions as $exclusion){
			$sentence = Yii::$app->wordCache->store($exclusion->value, $sentence, $caseSensitive=false);
		}
		
		//checking user submit data
		$exclusions = self::$config->getArray('exception');
		foreach($exclusions as $exclusion){
			$sentence = Yii::$app->wordCache->store($exclusion, $sentence);
		}
		
		//ignore  time which contain dots in indonesia
		if (preg_match_all('%(?:\s|^)[\d]{1,2}\.[\d]{2}(?:\s|$|,|\.)%', $sentence, $matches)){
			foreach($matches[0] as $match){
				$sentence = Yii::$app->wordCache->store($match, $sentence);
			}
		}
		
		return $sentence;
	}
	
	/**
	 * Remove initial spin text.
	 * Initial text, always located at the first.
	 * So we only remove the first one.
	 * @param type $sentence
	 */
	private static function removeInitial($sentence)
	{
		return preg_replace(
            '%\{[^\{\}\|]+\|%',
            '{',
            $sentence
        );
	}
	
	private static function finalize($sentence)
	{
		//return the changed word into the text
		$all = Yii::$app->wordCache->getAll();
		
		foreach($all as $label=>$word){
			$sentence = str_replace($label, $word, $sentence);
		}
		
		//remove any initial text from the spintext if UNIQUE is enabled
		//initial text always the very first position
		if(self::$config->is('unique')){
			$sentence = self::removeInitial($sentence);
		}
		
		return $sentence;
	}
	
}
