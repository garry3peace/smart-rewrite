<?php
namespace app\components\rules\sentence;

use app\components\rules\sentence\ConditionalRule;
use app\components\StringHelper;

/**
 * Condition 3 for simple if rule
 * "Jika hari sudah malam, saya akan pulang." to 
 * "Saya akan pulang jika hari sudah malam" 
 * "
 */
class Conditional3 extends ConditionalRule{
	
	
	public static function rewrite($matches)
	{
		//If the string contain 'yang' this is not suitable for change position
		if(StringHelper::getLastWord($matches[2])=='yang'){
			return '';
		}
		
		return ucfirst(trim($matches[3])).' '.lcfirst($matches[1]).' '.$matches[2];
	}
	
	public static function rule()
	{
		$ifWord = self::ifWords();
		return self::SENTENCE_OPENING.$ifWord.' ([\w\s\`\-]*)\, ([\w\s\`\-]*)'.self::SENTENCE_CLOSING;
	}
}