<?php

use app\tests\codeception\unit\components\rules\RulesTest;

class Conditional1Test  extends RulesTest {
	function __construct() {
		return parent::__construct('\app\components\rules\sentence\Conditional1');
	}

	
	public function getList()
	{
		
		return [
			'Mereka yang kalau dilukai tidak melawan maka mereka adalah orang hebat'=>
				'',
				
			'Mereka bilang kalau ada barang datang maka segera dicatat'=>
				'Mereka bilang segera dicatat kalau ada barang datang',
			
			];
	}
}
