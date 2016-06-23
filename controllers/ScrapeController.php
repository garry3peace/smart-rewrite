<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\scrapper\ScrapperPage;
use app\components\WordpressPoster;

class ScrapeController extends Controller
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
		$content = '';
		
		if(isset($_POST['Scrape'])){
			$content = $_POST['Scrape']['list'];
			$list = explode(PHP_EOL, $content);
			foreach($list as $link){
				$data = ScrapperPage::get($link);
				$wp = new WordpressPoster($data['title'], $data['content']);
				$wp->post();
			}
		}
		return $this->render('index',['content'=>$content]);
	}
	
	public function actionTest()
	{
		$list = [
			'menganulirkan','mengandalkan',
			'mengabarkan',
			'membacakan',
			'mencucikan',
			'mendakwakan',
			'mengejankan','mengerjakan',
			'memfitnahkan',
			'menggunakan',
			'mengharuskan',
			'mengizinkan',
			'menjajankan',
			'menguncikan',
			'melambangkan',
			'menaikkan',
			'mengoperasikan',
			'memperhatikan',
			'merasakan',
			'menyampaikan',
			'menandakan',
			'mengudarakan',
			'memvetokan',
			'mewakilkan',
			'meyakinkan',
			'menobatkan',
			'menyelesaikan',
			'menyusahkan',
		];
		foreach($list as $affix){
			echo \app\components\sentence\Passive::toPassiveMekan($affix).'<br/>';
		}
	}
}
