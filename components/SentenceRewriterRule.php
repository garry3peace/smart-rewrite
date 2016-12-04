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
		//Include all the rules that located in components/rules/ folder
		$rules = self::autoLoad();
		
		return $rules;
	}
}