<?php

namespace app\components\rules\sentence;

use app\components\rules\sentence\ConditionalRule;
use app\components\StringHelper;

/**
 * Class Rule is for defining rewrite rule. 
 */
class Conditional1 extends ConditionalRule{
	
	
	public static function rewrite($matches)
	{
		//If the string contain 'yang' this is not suitable for change position
		if(StringHelper::getLastWord($matches[1])=='yang'){
			return '';
		}
		
		return ucfirst($matches[1]).' '.$matches[4].' '.$matches[2].' '.lcfirst(trim($matches[3]));
	}
	
	public static function rule()
	{
		$ifWord = self::ifWords();
		return self::SENTENCE_OPENING.'([\w\s\`\-]*) '.$ifWord .' ([\w\s`\-]*) maka ([\w\s\`\-]*)'.self::SENTENCE_CLOSING;
	}
}