<?php

namespace app\components;

class WordHelper
{
	/**
	 * This function is going to check all uppercase and first uppercase word
	 * to change the found words have same format with the former
	 * @param type $sourceWord, the former word
	 */
	public static function syncCase($sourceWord, $targetWord)
	{
		//all lowercase
		if(ctype_lower($sourceWord)){
			return $targetWord;
		}
		
		//case ucfirst
		if(ord($sourceWord[0])>=65 && ord($sourceWord[0])<=90) {
			return ucfirst($targetWord);
		}
		
		//case all uppercase
		if(ctype_upper ($sourceWord)){
			return strtoupper($targetWord);
		}
		
		return $targetWord;
	}
	
	public static function process($sourceWord, $targetWord)
	{
		return self::syncCase($sourceWord, $targetWord);
	}
	
	/**
	 * Get the synonym and antonym of the words
	 * Used at spin() function
	 * @param type $word
	 */
	public static function getSpinWord($word)
	{
		$cleanWord = trim($word, '`');
		
		//Get the synonyms and antonyms of the words
		$synonymWord = \app\models\Synonym::get($cleanWord);
		
		//ignore marked words/phrases to antonymized
		$antonymWord = [];
		if (!self::isMarked($word)){
			$antonymWord = \app\models\Antonym::get($cleanWord);
		}
		
		$words = array_merge($synonymWord, $antonymWord);

		//If it was empty then just directly return the word
		if(empty($words)){
			return $word;
		}
		
		//add the own word
		array_unshift($words, $word);
		

		return SpinFormat::generate($words);
	}
	
	/**
	 * Marked, mean that the word is given aphostrophe, so it won't antonymized
	 * @param type $word
	 * @return boolean
	 */
	private static function isMarked($word)
	{
		//length less than 3 is impossible to marked
		if(strlen($word)<3){
			return false;
		}

		$pattern = "%`[\w\s-]+`%";
		if(preg_match($pattern, $word)){
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check whether the given word, is the last word in the sentence
	 * if the word last character is '.'
	 */
	public static function isLast($word)
	{
		if(substr($word,-1)=='.'){
			return true;
		}
		return false;
	}
	
	/**
	 * Check if the given word is a name.(which has capital at the first character)
	 * @param type $word
	 * @return boolean
	 */
	public static function isName($word)
	{
		if(ucfirst($word)==$word){
			return true;
		}
		return false;
	}
}