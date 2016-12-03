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
				'Pr sedang dikerjakan {|oleh} dia.',
			
			//Tidak dikonvert kalau dua verba berdempetan
			'Beberapa hari ini dia pergi memperkenalkan produk baru."'=>
				'Beberapa hari ini dia pergi memperkenalkan produk baru.',
			
			'Kami bisa mengerjakan tugas tersebut."'=>
				'Tugas tersebut bisa dikerjakan {|oleh} kami.',
			
		];
	}

}

