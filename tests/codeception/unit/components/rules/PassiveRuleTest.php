<?php


use app\tests\codeception\unit\components\rules\RulesTest;

class PassiveRuleTest extends RulesTest {
	
	function __construct() {
		return parent::__construct('\app\components\rules\sentence\PassiveRule');
	}
	
	public function getList()
	{
		return [
			'Mereka dengan santai mengerjakan tugas secara cermat'=>
				'Tugas secara cermat dikerjakan {|oleh} mereka dengan santai',
			
			'Saya dan Rian mengerjakan tugas ini demi masa depan'=>
				'Tugas ini dikerjakan {|oleh} saya dan Rian demi masa depan',
			
			'Dia sedang mengerjakan PR.'=>
				'PR sedang dikerjakan {|oleh} dia.',
			
			//Tidak dikonvert kalau dua verba berdempetan
			'Beberapa hari ini dia pergi memperkenalkan produk baru."'=>
				'',
			
			'Kami bisa mengerjakan tugas tersebut."'=>
				'Tugas tersebut bisa dikerjakan {|oleh} kami.',
			
			'Ditanya bagaimana prosesnya hingga terjadi pengurangan 40 persen bantuan ke Indonesia Menteri Hockey mengatakan, Menteri Luar Negeri Australia sebagai penanggung jawab pengelolaan dana tersebut mempertimbangkan sejumlah faktor'=>
				'Ditanya bagaimana prosesnya hingga terjadi pengurangan 40 persen bantuan ke Indonesia Menteri Hockey mengatakan, sejumlah faktor dipertimbangkan {|oleh} menteri Luar Negeri Australia sebagai penanggung jawab pengelolaan dana tersebut',
			
		];
	}

}

