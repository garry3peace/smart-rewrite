<?php

use app\tests\codeception\unit\components\rules\RulesTest;

class DirectRuleTest extends RulesTest {
	
	function __construct() {
		return parent::__construct('\app\components\rules\DirectRule');
	}

	
	public function getList()
	{
		
		return [
			'Saya langsung berkata, "Kalian segera pergi dari sini."'=>
				'Saya langsung berkata bahwa {mereka|mereka semua} segera pergi dari sini.',
			
			'Dia bilang, "Saya sudah makan."'=>
				'Dia bilang bahwa {dia|ia} sudah makan.',
			
			'Dia berkata pada mereka, "Kalian sudah tidak bisa pergi dari sini."'=>
				'Dia berkata pada mereka bahwa {mereka|mereka semua} sudah tidak bisa pergi dari sini.',
			
			'Dia berkata, "Kalian sudah tidak bisa pergi dari sini."'=>
				'Dia berkata bahwa {mereka|mereka semua} sudah tidak bisa pergi dari sini.',
			
			'Dia tadi bilang ke aku, "Kalian sudah tidak bisa pergi dari sini."'=>
				'Dia tadi bilang ke aku bahwa {mereka|mereka semua} sudah tidak bisa pergi dari sini.'
		];
	}
	
	
}

