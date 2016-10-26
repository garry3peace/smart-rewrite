<?php

namespace app\tests\codeception\unit\components\rules;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RulesTest
 *
 * @author garry
 */
abstract class RulesTest extends \Codeception\Test\Unit  {
	public $className;
	
	function __construct($classname) {
		$this->className = $classname;
	}
	
	/**
	 * Function to parse the sentence and get the tokens 
	 * based on the regex rules
	 * @param type $sentence
	 * @return type
	 */
	protected function read($sentence)
	{
		$rule = $this->getRule();
		preg_match($rule, $sentence, $match);
		return $match;
	}
	
	/**
	 * Get the class rule
	 * @return type
	 */
	private function getRule()
	{
		$className = $this->className;
		return $className::rule();
	}
	
	abstract public function getList();
	
	public function testGeneral()
	{
		$list = $this->getList();
		$className = $this->className;
		
		foreach($list as $direct=>$indirect){
				$matches = $this->read($direct);
				$result = $className::rewrite($matches);
				$this->assertEquals($indirect,$result);
		}

	}

}
