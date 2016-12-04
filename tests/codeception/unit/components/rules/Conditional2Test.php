<?php

use app\tests\codeception\unit\components\rules\RulesTest;

class Conditional2Test  extends RulesTest {
	function __construct() {
		return parent::__construct('\app\components\rules\sentence\Conditional2');
	}

	
	public function getList()
	{
		return [
			'Mereka percaya kalau presiden yang datang langsung'=>
				'Kalau presiden yang datang langsung, mereka percaya',
				
			'Mereka yang kalau sedang galau dilarang masuk'=>
				'',
			
			];
	}
}
