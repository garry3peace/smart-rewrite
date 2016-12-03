<?php

namespace app\components;

/**
 * Class Rule is for defining rewrite rule. 
 */
abstract class Rule{
	public static function beforeRewrite(){}
	public static function afterRewrite(){}
	public static function rewrite($match){}
	public static function rule(){}
}