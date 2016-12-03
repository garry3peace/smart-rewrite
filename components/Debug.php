<?php

namespace app\components;

/**
 * Fungsi umum yang digunakan untuk debugging
 *
 * @author garry
 */
class Debug {
	/**
	 * Mengecek apakah saat ini adalah localhost
	 */
	public static function isLocal()
	{
		//Tanpa adanya HTTP_HOST hanya terjadi saat akses via command line
		//atau testing codeception. 
		if(!isset($_SERVER['HTTP_HOST'])){
			return true;
		}
		
		if(strpos($_SERVER['HTTP_HOST'],'localhost')!==false){
			return true;
		}
		return false;
	}
}
