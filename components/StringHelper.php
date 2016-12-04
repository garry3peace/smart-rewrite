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
}
