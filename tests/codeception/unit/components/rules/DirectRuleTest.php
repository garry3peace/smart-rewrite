<?php


use app\components\rules\DirectRule;

class DirectRuleTest extends \Codeception\Test\Unit {
	private function getRule()
	{
		return DirectRule::rule();
	}
	
	protected function read($sentence)
	{
		$rule = $this->getRule();
		preg_match($rule, $sentence, $match);
		return $match;
	}
	
	public function testSentence()
	{
		
		$list = [
			'Saya langsung berkata, "Kalian segera pergi dari sini."'=>
				'Saya langsung berkata bahwa {mereka|mereka semua} segera pergi dari sini.',
			
			'Dia bilang, "Saya sudah makan."'=>
				'Dia bilang bahwa {dia|ia} sudah makan.',
			
			'Dia berkata pada mereka, "Kalian sudah tidak bisa pergi dari sini."'=>
				'Dia berkata pada mereka bahwa {mereka|mereka semua} sudah tidak bisa pergi dari sini.',
			
			'Dia berkata, "Kalian sudah tidak bisa pergi dari sini."'=>
				'Dia berkata bahwa {mereka|mereka semua} sudah tidak bisa pergi dari sini.'
			
		];
		
		foreach($list as $direct=>$indirect){
			$matches = $this->read($direct);
			$result = DirectRule::rewrite($matches);
			$this->assertEquals($indirect,$result);
		}
	}
	
	
}

