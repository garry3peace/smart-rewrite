<?php

namespace app\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
	public $sourcePath = '@bower/'; 
	public $js = [
		'angular/angular.js',
		'clipboard/dist/clipboard.min.js',
		'/js/mel-spintax.js',
		'/js/app.js',
	];
    
	public function init()
	{
		if (\app\components\Debug::isLocal()){
			$this->js[] = '/js/dev-params.js';
		}else{
			$this->js[] = '/js/prod-params.js';
		}
		
		//we must include the params first before include other js
		$this->js[] = '/js/services.js';
		
		return parent::init();
	}
}
