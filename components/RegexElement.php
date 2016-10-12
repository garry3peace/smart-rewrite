<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * RegexElement return regex syntax used to check the sentence rule
 *
 * @author garry
 */
class RegexElement {
	public static function opening()
	{
		return '%(^|\. |[\w\s-`#]+\, )';
	}
	
	public static function closing()
	{
		return '($|\.)%i';
	}
	
	public static function phrase()
	{
		return '[\w\s-`#]';
	}
	
	public static function sentence()
	{
		return '[\w\s-`#.,]';
	}
	
	public static function word()
	{
		return '\w+';
	}
	
	/**
	 * Get nouns format. 
	 * @param string $type could be 'pronoun', 'auxialiaries'. See \app\components\Vocabulary for more
	 */
	public static function nouns($type)
	{
		$list = word\Vocabulary::$type();
		$result = implode('|', $list);
		return $result;
	}
}
