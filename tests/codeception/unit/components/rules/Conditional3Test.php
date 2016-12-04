<?php

use app\tests\codeception\unit\components\rules\RulesTest;

class Conditional3Test  extends RulesTest {
	function __construct() {
		return parent::__construct('\app\components\rules\sentence\Conditional3');
	}

	
	public function getList()
	{
		return [
			'Jika mereka berhasil menebak, hadiah akan muncul'=>
				'Hadiah akan muncul jika mereka berhasil menebak',
				
			'Jika apa yang dikatakan mereka benar, ini menjadi masalah'=>
				'Ini menjadi masalah jika apa yang dikatakan mereka benar',
			
			];
	}
}
