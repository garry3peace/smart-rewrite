<?php
namespace app\components\word;

use app\components\SpinFormat;

class Vocabulary
{
	public static function conjunctions()
	{
		return ['dan', 'serta', 'atau', 'tetapi', 'namun', 'melainkan', 
			'padahal', 'sedangkan', 'yang', 'agar', 'supaya', 'biar', 'jika', 'kalau', 
			'jikalau', 'asal', 'asalkan', 'bila', 'manakala', 'sejak', 'semenjak', 'sedari', 
			'sewaktu', 'tatkala', 'ketika', 'sementara', 'begitu', 'seraya', 'selagi', 'selama', 
			'serta', 'sambil', 'demi', 'setelah', 'sesudah', 'sebelum', 'sehabis', 'selesai', 
			'seusai', 'hingga', 'sampai', 'andai', 'andaikan', 'seandaikan', 'seandainya', 
			'umpama', 'umpamanya', 'sekiranya', 'biar', 'biarpun', 'walau', 
			'walaupun', 'sekalipun', 'sungguhpun', 'kendati', 'kendatipun', 
			'seakan', 'seakan-akan', 'seolah', 'seolah-olah', 'sebagaimana', 
			'seperti', 'laksana', 'ibarat', 'daripada', 'alih-alih', 
			'sebab', 'karena', 'lantaran', 'gara-gara', 'akibat', 'oleh karena', 
			'oleh sebab', 'sehingga', 'sampai', 'maka', 'makanya', 'dengan', 'tanpa', 
			'bahwa','kemudian','lalu','mula-mula','kecuali','selain','misal','misalkan',
			'misalnya','contoh','contohnya','umpama','umpamanya','akhirnya','alhasil'];
	}
	
	public static function wordConjunctions()
	{
		return ['dan', 'serta', 'atau', 'yang'];
	}
	
	public static function auxialiaries()
	{
		return ['akan','sudah','telah','belum','masih','sedang','lagi','tetap',
			'langsung','malah','bisa','dapat','mampu','mau','ingin','tidak','enggak','gak','tak'];
	}
	
	public static function pronoun()
	{
		return ['saya','aku','gw','gua','gue','ane','diriku',
			'kamu','anda','dikau','dirimu','engkau','kau',
			'dia','ia',
			'mereka','mereka semua',
			'kalian','kalian semua',
			'kami','kita','kami semua','kita semua'
		];
	}
	
	/**
	 * Return the list of text in spintax format
	 * @param string $type (e.g: 'pronoun', 'auxialiaries')
	 * @return string result of string
	 */
	public static function spintax($type)
	{
		$list = self::$type();
		return SpinFormat::generate($list);
	}
}
