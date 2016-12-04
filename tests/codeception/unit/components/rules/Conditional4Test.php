<?php

use app\tests\codeception\unit\components\rules\RulesTest;

class Conditional4Test  extends RulesTest {
	function __construct() {
		return parent::__construct('\app\components\rules\sentence\Conditional4');
	}

	
	public function getList()
	{
		return [
			'Saya akan pergi selama dia pergi'=>
				'Selama dia pergi, saya akan pergi',
				
			'Mereka yang selama ini bertengkar pun sudah akur'=>
				'',
			
			];
	}
}
