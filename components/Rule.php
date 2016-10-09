<?php

namespace app\components;

/**
 * Class Rule is for defining rewrite rule. 
 */
abstract class Rule{
	public static function rewrite($match){}
	public static function rule(){}
}