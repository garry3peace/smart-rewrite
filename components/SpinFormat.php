<?php

/* 
 * SpinFormat to manage spin text format
 */

namespace app\components;

class SpinFormat {
	/**
	 * Generate the given array as spin text format
	 * @param Array $words
	 * @retunr string text in spin text format
	 */
	public static function generate($words){
		$words = array_filter($words);//remove empty array
		return '{'.implode('|',$words).'}';
	}
	
	/**
	 * Parsing the spintax in multiline
	 * @param type $text
	 * @return type
	 */
	public static function parse($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array('app\components\SpinFormat', 'replace'),
            $text
        );
    }
 
    public static function replace($text)
    {
        $text = self::parse($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}
