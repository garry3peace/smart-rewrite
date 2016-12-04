<?php

use app\tests\codeception\unit\components\rules\RulesTest;

class CausalityTest  extends RulesTest  {
	function __construct() {
		return parent::__construct('\app\components\rules\sentence\Causality');
	}

	
	public function getList()
	{
		return [
			'Mereka tergusur karena menempati lahan pemerintah'=>
				'Karena menempati lahan pemerintah, mereka tergusur',
				
			'Mereka yang karena sedang galau memilih mengundurkan diri'=>
				'',
			
			];
	}
}
