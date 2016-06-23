<?php

namespace app\components;

/**
 * Class Rule is for defining rewrite rule. 
 */
abstract class Rule{
	public abstract static function rewrite($match);
	public abstract static function rule();
}