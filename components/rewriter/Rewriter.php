<?php

namespace app\components\rewriter;

abstract class Rewriter
{
	/**
	 * Change sentence content with the array of replacements
	 * @param string $sentence
	 * @param array $replacements
	 */
	protected static function replaceText($sentence, $replacements)
	{
		foreach($replacements as $label=>$replacement){
			$sentence = str_replace($label, $replacement, $sentence);
		}
		return $sentence;
	}
	
	public static function rewrite($sentence){}
}
