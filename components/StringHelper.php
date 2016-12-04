<?php

namespace app\components;

/**
 * Description of String
 *
 * @author garry
 */
class StringHelper {
	/**
	 * Get the last word from the sentences.
	 */
	public static function getLastWord($string)
	{
		$split = explode(" ", $string);
		return $split[count($split)-1];
	}
	
	/**
	 * Count the number of words
	 * @param string $string
	 * @return int
	 */
	public static function countWord($string)
	{
		return str_word_count($string);
	}
}
