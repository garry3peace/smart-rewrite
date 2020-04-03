<?php
namespace app\components\rules\sentence;

use app\components\rules\sentence\ConditionalRule;
use app\components\StringHelper;

/**
 * Condition 4 for simple if rule
 * "Jika hari sudah malam, saya akan pulang." to 
 * "Saya akan pulang jika hari sudah malam" 
 * "
 */
class Conditional4 extends ConditionalRule{
	
	
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
		$timeWord = '(sejak|semenjak|sedari|sewaktu|tatkala|ketika|sementara|seraya|
			selagi|selama|sambil|demi|setelah|sesudah|sebelum|sehabis|selesai|seusai|
			hingga)';
		
		return self::SENTENCE_OPENING.'([\w\s\-\`]+) '.$timeWord.' ([\w\s\-\`]*)'.self::SENTENCE_CLOSING;
	}
}