<?php


use app\tests\codeception\unit\components\rules\RulesTest;

class PassiveRuleTest extends RulesTest {
	
	function __construct() {
		return parent::__construct('\app\components\rules\PassiveRule');
	}
	
	public function getList()
	{
		return [
			
			'Dia sedang mengerjakan PR.'=>
				'Pr sedang dikerjakan dia.',
			
			'Beberapa hari ini dia pergi memperkenalkan produk baru."'=>
				'Produk baru diperkenalkan beberapa hari ini dia pergi.',
			
		];
	}

}

