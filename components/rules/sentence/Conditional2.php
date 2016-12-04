<?php
namespace app\components\rules\sentence;

use app\components\rules\sentence\ConditionalRule;
use app\components\StringHelper;

/**
 * Class Rule is for defining rewrite rule. 
 */
class Conditional2 extends ConditionalRule{
	
	
	public static function rewrite($matches)
	{
		//If the string contain 'yang' this is not suitable for change position
		if(StringHelper::getLastWord($matches[1])=='yang'){
			return '';
		}
		
		return ucfirst(trim($matches[2])).' '.$matches[3].', '.lcfirst(trim($matches[1]));
	}
	
	public static function rule()
	{
		$ifWord = self::ifWords();
		return self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)'.self::SENTENCE_CLOSING;
	}
}