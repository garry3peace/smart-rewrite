<?php

namespace app\components\word;

/**
 * class Verb. This function will convert all basic form of 
 * verb into certain affix,prefix or suffix fom
 */
class Verb{
	/**
	 * Add the affix "me-" to the verb 
	 */
	public static function me($baseVerb)
	{
		if(in_array($baseVerb[0], ['a','i','u','e','o'])){
			return 'meng'.$baseVerb;
		}
		
		if(in_array($baseVerb[0], ['l','m','n','w','y'])){
			return 'me'.$baseVerb;
		}
		
		if(in_array($baseVerb[0], ['b','f','v'])){
			return 'mem'.$baseVerb;
		}
		
		if(in_array($baseVerb[0], ['c', 'd', 'j', 'z'])){
			return 'men'.$baseVerb;
		}
		
		if(in_array($baseVerb[0], ['t'])){
			return 'men'.substr($baseVerb,1);
		}
		
		if(in_array($baseVerb[0], ['g', 'h'])){
			return 'meng'.$baseVerb;
		}
		
		if(in_array($baseVerb[0], ['k'])){
			return 'meng'.substr($baseVerb,1);
		}
	}
	
}