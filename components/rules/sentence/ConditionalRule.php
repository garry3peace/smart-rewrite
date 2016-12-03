<?php

namespace app\components\rules\sentence;
use app\components\Rule;

/**
 * Class Rule is for defining rewrite rule. 
 */
class ConditionalRule extends Rule{
	
	const SENTENCE_OPENING = '%\b';//symbols for opening sentence
	const SENTENCE_CLOSING = '\b%i'; //symbols for closing sentence
	
	public static function rewrite($matches)
	{
		
	}
	
	private static function ifWords()
	{
		return "(seandainya|andaikan|jika|kalau|jikalau|kalaupun|sungguhpun|asal|asalkan|manakala)";
	}
	
	public static function rule()
	{
		//Conditional still under development
		return '/./';
		
		
		$ifWord = self::ifWords();
		
		return [
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)maka([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match1|ucfirst,:match4 :match2 :match3|trim|lcfirst',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3, :match1|lcfirst|trim',
			],
			[
				'rule'=>self::SENTENCE_OPENING.$ifWord.' ([\w\s`-]*), ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match3|trim|ucfirst :match1|lcfirst :match2',
			]
		];
	}
}