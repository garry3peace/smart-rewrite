<?php
use \app\components\word\VerbMekan;

class VerbTest extends \Codeception\Test\Unit
{
    public function testPassive()
    {
		$result = VerbMekan::toPassive('memakan');
		$this->assertEquals($result,'dimakan');
		
		$result = VerbMekan::toPassive('menyampaikan');
		$this->assertEquals($result,'disampaikan');
		
		$result = VerbMekan::toPassive('mencarikan');
		$this->assertEquals($result,'dicarikan');
		
		$result = VerbMekan::toPassive('menganalisakan');
		$this->assertEquals($result,'dianalisakan');
		
		$result = VerbMekan::toPassive('membubuhkan');
		$this->assertEquals($result,'dibubuhkan');
		
		$result = VerbMekan::toPassive('mencucikan');
		$this->assertEquals($result,'dicucikan');
		
		$result = VerbMekan::toPassive('mendongkelkan');
		$this->assertEquals($result,'didongkelkan');
		
		$result = VerbMekan::toPassive('meneruskan');
		$this->assertEquals($result,'diteruskan');
		
		$result = VerbMekan::toPassive('memperkenalkan');
		$this->assertEquals($result,'diperkenalkan');
    }
}