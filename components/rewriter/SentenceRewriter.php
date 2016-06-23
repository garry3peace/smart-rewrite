<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components\rewriter;

use app\components\SentenceList;
use app\components\rewriter\Rewriter;

class SentenceRewriter extends Rewriter
{
	
	
	/**
	 * Parsing the $process string by replacing the variable
	 * with the given $match array
	 * @param string $process
	 * @param array $param
	 * @return string result text
	 */
	private static function processingPregMatch($process, $param)
	{
		$regexPattern = '%:[\w\d|]+%i';
		preg_match_all($regexPattern, $process, $matches);
		
		foreach($matches[0] as $match){
			$pieces = explode('|',$match);
			
			$variable = str_replace(':match','', $pieces[0]);
			unset($pieces[0]);
			$value = trim($param[$variable]);
			
			//if there is no extra param for variable, then continue
			if(count($pieces)>0){
				foreach($pieces as $function){
					$value = call_user_func($function,$value);
				}
			}
			
			$process = str_replace($match, $value, $process);
		}
		
		return $process;
	}

	/**
	 * Executer process that is function type
	 */
	private static function executeProcess($process, $match)
	{
		$function = str_replace('func:', '', $process);
		return call_user_func("$function",$match);
	}
	
	/**
	 * Process could be function or string
	 * If it is function, the process must preceed with "func:"
	 * to let the system know
	 */
	private static function isFunctionProcess($process)
	{
		if(strpos($process, 'func:')===0){
			return true;
		}
		return false;
	}
	
	/**
	 * Running rule on the SentenceItem object (SentenceItem containing one real format and several alternate formats)
	 * And directly update the new generate sentence to the object
	 * @param SentenceItem $sentenceItem
	 * @param string $rule rule in regex
	 */
	private static function runRule(&$sentenceItem, $rule)
	{
		$sentences = $sentenceItem->getAllSentence();
		
		//Every sentence alternate must be run.
		foreach($sentences as $sentence){
			$regexPattern = $rule['rule'];
			if (preg_match($regexPattern, $sentence, $match)){
				$process = $rule['process'];
				if(self::isFunctionProcess($process)){
					$newSentence = self::executeProcess($process, $match);
				}else{
					$newSentence = self::processingPregMatch($process, $match); 
				}
				$sentenceItem->alternates[] = $newSentence;
			}
		}
	}
	
	public static function rewrite($sentence)
	{
		$sentenceList = new SentenceList($sentence);
		$list = $sentenceList->getList();
		
		$items = [];
		foreach($list as $item){
			
			//Execute each Rule onto each sentence
			$listRules = \app\components\SentenceRewriterRule::rules();
			foreach($listRules as $rule){
				self::runRule($item, $rule);
			}
			
			$items[] = $item;
		}
		
		$newSentenceList = new SentenceList($items);
		$result = $newSentenceList->getSpin();
		
		return $result;
	}

}
