<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\scrapper\Scrapper;

/**
 * Coding untuk menarik konten dari website dan simpan ke CSV
 */
class RetrieverController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
		ini_set('max_execution_time',0);
		$output = fopen("result.csv","w");
		$input = fopen('input.csv', "r");
		while($data = fgetcsv($input)){
//			var_dump($data);
			
			$result = $data;
			if(!empty($data[2])){
				$text = Scrapper::get($data[2]);
				$result['longtext'] = $text['longtext'];
				$result['shorttext'] = $text['shorttext'];
			}
			
			fputcsv($output,$result);
		}
		fclose($input);
		fclose($output);
	}
	
	public function action1()
	{
		//print_r(Scrapper::get('http://www.anastasiabeverlyhills.com/precision-tweezers.html'));
		//print_r(Scrapper::get('http://www.amazon.com/Anastasia-Beverly-Palette-Limited-Edition/dp/B015N8YM9S'));
//		print_r(Scrapper::get('http://www.sephora.com/powermud-dualcleanse-treatment-P387067'));
//		print_r(Scrapper::get('https://www.bobbibrowncosmetics.com/product/14008/12762/Skincare/Eye-Moisturizer/Hydrating-Eye-Cream/FH10'));
//		print_r(Scrapper::get('http://manentail.com/products/the-original-mane-n-tail-shampoo/'));
//		print_r(Scrapper::get('http://www.beautyblender.com/shop/category/cleansers/blendercleanser-solid.html'));
//		print_r(Scrapper::get('http://www.bhcosmetics.com/products/11-pcs-pink-a-dot-brush-set'));
//		print_r(Scrapper::get('http://www.clarisonic.com/brush-heads/luxe-cashmere-CL307.html'));
//		print_r(Scrapper::get('http://www.drugstore.com/clearasil-ultra-rapid-action-pads/qxp139461'));
//		print_r(Scrapper::get('http://www.paulaschoice.com/shop/Clear-Acne-Treatments/_/Clear-Regular-Strength-Two-Week-Trial-Kit/#ctl00_ctl00_ctl00_ctl00_BodyContent_BodyContent_BodyContent_BodyContent_ctl00_pdctProductDetailContentTabs_pnlTabBodyDescription'));
//		print_r(Scrapper::get('http://www.urbandecay.com/naked-basics-eyeshadow-by-urban-decay/355.html'));
		print_r(Scrapper::get('http://www.wowkeren.com/berita/tampil/00108776.html'));
	}
	
	public function action2()
	{
//		echo \app\components\Number::toNumeric('lima ratus dua puluh');
//		echo \app\components\Number::toNumeric('dua puluh');
//		echo \app\components\Number::toNumeric('dua belas');
//		echo \app\components\Number::toNumeric('dua');
	}
}