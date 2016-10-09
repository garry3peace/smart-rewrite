<?php
use \app\components\Number;

class NumberTest extends \Codeception\Test\Unit
{
    public function testToNumeric()
    {
		$result = Number::toNumeric('satu');
		$this->assertEquals(1,$result);
		
		$result = Number::toNumeric('tiga puluh satu');
		$this->assertEquals(31, $result);
		
		$result = Number::toNumeric('tiga ratus');
		$this->assertEquals(300, $result);
		
		$result = Number::toNumeric('tiga ratus lima puluh satu');
		$this->assertEquals(351, $result);
		
		$result = Number::toNumeric('sebelas');
		$this->assertEquals(11, $result);
		
		$result = Number::toNumeric('dua belas');
		$this->assertEquals(12, $result);
		
		$result = Number::toNumeric('tiga ratus lima');
		$this->assertEquals(305, $result);
		
		$result = Number::toNumeric('seratus');
		$this->assertEquals(100, $result);
		
		$result = Number::toNumeric('seratus lima');
		$this->assertEquals(105, $result);
		
		$result = Number::toNumeric('seribu satu');
		$this->assertEquals(1001, $result);
		
		//$result = Number::toNumeric('delapan koma enam');
		//$this->assertEquals($result,8.6, '', 0.1);//perlu delta untuk batas toleransi
    }
}