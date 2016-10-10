<?php

/* 
 * SpinFormat to manage spin text format
 */

namespace app\components;

use app\components\rewriter\ParagraphRewriter;
use app\components\rewriter\SentenceRewriter;
use app\components\rewriter\PhraseRewriter;
use app\components\rewriter\WordRewriter;
use app\components\WordHelper;
use Yii;

/**
 * The very core of SmartRewrite
 * This class handling rewriting based on the given text
 * and the configuration
 */
class SmartRewrite 
{	
	const SYMBOL = '.",';//The symbol that can be ignored
	const SENTENCE_OPENING = '%(?:^|\.)';//symbols for opening sentence
	const SENTENCE_CLOSING = '(?:$|\.)%i'; //symbols for closing sentence
	
	private $config; //Config Object
	private $realSentence;
	private $sentence;
	
	public function __construct($sentence, $config)
	{
		$this->config = $config;
		
		$this->realSentence = $sentence;
		$this->sentence = $sentence; 
	}
	
	/**
	 * Store the submitted content into the log
	 */
	private function log()
	{
		$log = new \app\models\Log;
		return $log->add($this->sentence, $this->config);
	}
	
	public function rewrite()
	{
		//saving into log
		$this->log();
		
		//exclude all execptions
		$this->exclude();
		
		//processing paragraph
		if($this->config->is('paragraph')){
			$paragraphWriter = new ParagraphRewriter($this->sentence ,$this->config);
			$this->sentence = $paragraphWriter->rearrange();
		}
		
		//processing rewrite sentence
		$this->sentence = SentenceRewriter::rewrite($this->sentence);
		//processing phrase
		$this->sentence = PhraseRewriter::rewrite($this->sentence );
		//processing word
		$this->sentence = WordRewriter::rewrite($this->sentence );
		
		$this->finalize();
	}
	
	public function getRewriteSentence()
	{
		return $this->sentence;
	}
	
	
	/**
	 * Excluding names. Names always uppercase and most of it doesn't at in front
	 * tokenize the sentence
	 */
	private function excludeName()
	{
		//Find per word to check whether it is name word 
		$token = strtok($this->sentence, ' ');
		$lastWordFound = true;
		$tempSentence = '';
		while($token !==false){
			
			if(!$lastWordFound && WordHelper::isName($token)){
				//Give flag if this is last word, so next loop we must skip it.
				if(WordHelper::isLast($token)){
					$lastWordFound = true;
				}else{
					$lastWordFound = false;
				}
				
				//trim the dot at the end. Because most probably this is the 
				//end sentence. And dot must not included into cache, so the 
				//sentence will preserved.
				if($lastWordFound){
					$token = rtrim($token, '.');
					$token = Yii::$app->wordCache->store($token).'.';
				}else{

					//store to the cache
					$token = Yii::$app->wordCache->store($token);
				}
			}
			
			
			$tempSentence .= $token.' ';

			$token = strtok(' ');
		}
		
		$this->sentence =  $tempSentence;
	}
	
	/**
	 * Excluding all the words so it will not processed
	 * All excluded words will be stored in the cache
	 * which will be retrieved later in finalize()
	 * @param type $sentence
	 * @return type
	 */
	private function exclude()
	{
		
		//Excluding text that is html link/img
		$regex = '%(<(?i:img|a)[\w\s-\.\"\=\<\>\:./]*?>)%';
		$this->sentence = preg_replace_callback($regex,function ($match){
			$word = $match[0];
			$target = $match[1];
			return Yii::$app->wordCache->storeInPlace($target, $word);
		},$this->sentence);
		
		
		$this->excludeName();
		
		
		//Checking the database
		$exclusions = \app\models\Exclusion::find()->all();
		foreach($exclusions as $exclusion){
			$this->sentence = Yii::$app->wordCache->storeInPlace($exclusion->value, $this->sentence, $caseSensitive=false);
		}
		
		//checking user submit data
		$exclusions = $this->config->getArray('exception');
		foreach($exclusions as $exclusion){
			$this->sentence = Yii::$app->wordCache->storeInPlace($exclusion, $this->sentence);
		}
		
		//ignore  time which contain dots in indonesia
		if (preg_match_all('%(?:\s|^)[\d]{1,2}\.[\d]{2}(?:\s|$|,|\.)%', $this->sentence, $matches)){
			foreach($matches[0] as $match){
				$this->sentence = Yii::$app->wordCache->storeInPlace($match, $this->sentence);
			}
		}
		
		return $this->sentence;
	}
	
	/**
	 * Remove initial spin text.
	 * Initial text, always located at the first.
	 * So we only remove the first one.
	 * @param type $sentence
	 */
	private function removeInitial()
	{
		return preg_replace(
            '%\{[^\{\}\|]+\|%',
            '{',
            $this->sentence
        );
	}
	
	private function finalizeLoadCache()
	{
		//return the changed word into the text
		$all = Yii::$app->wordCache->getAll();
		$sentence = $this->sentence;
		foreach($all as $label=>$word){
			$sentence = str_replace($label, $word, $sentence);
		}
		$this->sentence = $sentence;
	}
	
	private function finalizeRemoveInitial()
	{
		//remove any initial text from the spintext if UNIQUE is enabled
		//initial text always the very first position
		if($this->config->is('unique')){
			$this->removeInitial();
		}
	}
	
	private function finalize()
	{
		$this->finalizeLoadCache();
		
		$this->finalizeRemoveInitial();
		
	}
}
