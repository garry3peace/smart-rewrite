<?php

namespace app\components\rules\sentence;

use app\components\rules\sentence\ConditionalRule;
use app\components\StringHelper;

/**
 * Class Rule is for defining rewrite rule. 
 */
class Causality extends ConditionalRule{
	
	
	public static function rewrite($matches)
	{
		//If the string contain 'yang' this is not suitable for change position
		if(StringHelper::getLastWord($matches[1])=='yang'){
			return '';
		}
		
		$sentence = ucfirst(trim($matches[2])).' '.trim($matches[3]).', '.lcfirst($matches[1]);
		return $sentence;
	}
	
	public static function rule()
	{
		return self::SENTENCE_OPENING.'([\w\s\-\`\,]+) (dikarenakan|karena|sebab|supaya|agar) ([\w\s\`\-]*)'.self::SENTENCE_CLOSING;
	}
}