<?php

namespace app\components;

use app\components\file\File;
use Yii;

/**
 * List of all the rules to rewrite sentence
 * The parameter accept either array rule or
 * a Rule class
 */
class SentenceRewriterRule{

	const SENTENCE_OPENING = '%\b';//symbols for opening sentence
	const SENTENCE_CLOSING = '\b%i'; //symbols for closing sentence
	
	/**
	 * Load rules that located in 
	 * \app\components\rules\
	 */
	private static function autoLoad()
	{
		$files = File::loadDir(Yii::getAlias('@app/components/rules/sentence'), $filenameOnly=true);
		
		$result = [];
		foreach($files as $class){
			//calling static function rule like this: \app\components\rules\PassiveRule::rule()
			$rule = call_user_func('\app\components\rules\sentence\\'.$class. '::rule');
			//calling function func:\app\components\rules\PassiveRule::rewrite'
			$process = 'func:\app\components\rules\sentence\\'.$class.'::rewrite';
			
			$result[] = 
				[
					'rule'=>$rule,
					'process'=>$process,
				];
		}
		return $result;
	}
	
	public static function rules()
	{
		$ifWord = "(seandainya|andaikan|jika|kalau|jikalau|asal|asalkan|manakala)";

		$timeWord = '(sejak|semenjak|sedari|sewaktu|tatkala|ketika|sementara|seraya|
			selagi|selama|sambil|demi|setelah|sesudah|sebelum|sehabis|selesai|seusai|
			hingga)';

		$rules =  [
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*) maka ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match1|ucfirst,:match4 :match2 :match3|trim|lcfirst',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3, :match1|lcfirst|trim',
			],
			[
				'rule'=>self::SENTENCE_OPENING.$ifWord.' ([\w\s`-]*), ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match3|trim|ucfirst :match1|lcfirst :match2',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s-`,]+) (dikarenakan|karena|sebab|supaya|agar) ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3|trim, :match1|lcfirst',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s-`]+) '.$timeWord.' ([\w\s-`]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3|trim, :match1|trim|lcfirst',
			],
		];
		
		//Include all the rules that located in components/rules/ folder
		$extra = self::autoLoad();
		
		foreach($extra as $rule)
		{
			$rules[] = $rule;
		}
		
		return $rules;
	}
}