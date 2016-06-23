<?php

namespace app\components\web;

class Html{
	
	/**
	 * Strip to only allow certain html code
	 * @param string $string
	 * @return string
	 */
	private static function strip($string, $tags='')
	{
		$content = strip_tags($string, $tags);
		return $content;
	}
	
	/**
	 * Find youtube URL and convert it into youtube video
	 * @param type $string
	 */
	public static function embedYoutube($string)
	{
		//remove anchor tag, but keep the "href" value
		$string = preg_replace('%<a.* href=["|\'](.*?)["|\'].*>.*<\/a>%i','$1',$string);
		//replace URL youtube into embed
		return preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",$string);
	}
	
	public static function plaintext($string)
	{
		$content = self::strip($string, '<em><b><strong><p><i><br><u><ul><ol><li>');
		return $content;
	}
	
	
	public static function blog($string)
	{
		//replace youtube link into youtube embed first
		$string = self::embedYoutube($string);
		$allowed = '<em><b><strong><p><i><br><u><img><blockquote><sup><sub>
			<article><h2><h3><h4><h5><h6><ul><ol><li><iframe>
			';
		$content = self::strip($string, $allowed);
		return $content;
	}
}