<?php

namespace app\themes\lumino\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Default asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/themes/lumino/static';
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        parent::init();

        $this->js = [
            'js/lumino.glyphs.js',
            'js/jquery-1.11.1.min.js',	
        ];
		
		$this->css = [
            'css/bootstrap.min.css',
            'css/styles.css',
            'css/custom.css',
        ];
    }
}
