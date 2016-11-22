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
		if(strpos($_SERVER['HTTP_HOST'],'localhost')!==false){
			return true;
		}
		return false;
	}
}
