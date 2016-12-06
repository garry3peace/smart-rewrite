<?php

namespace app\components\rules\sentence;
use app\components\Rule;

/**
 * Class Rule is for defining rewrite rule. 
 * This is only parent class. 
 */
class ConditionalRule extends Rule{
	
	const SENTENCE_OPENING = '%\b';//symbols for opening sentence
	const SENTENCE_CLOSING = '\b%i'; //symbols for closing sentence
	
	public static function rewrite($matches)
	{
		
	}
	
	protected static function ifWords()
	{
		return "(seandainya|andaikan|jika|kalau|jikalau|kalaupun|sungguhpun|asal|asalkan|manakala)";
	}
	
	public static function rule()
	{
		//Conditional still under development
		return false;
		
	}
}