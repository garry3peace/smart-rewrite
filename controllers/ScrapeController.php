<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\scrapper\Scrapper;
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
		$list = ['http://www.wowkeren.com/berita/tampil/00109982.html',
			'http://www.wowkeren.com/berita/tampil/00109860.html'
			];
		
		foreach($list as $link){
			$data = Scrapper::get($link);
			$wp = new WordpressPoster($data['title'], $data['content']);
			$wp->post();
		}
		 		
		//$wp = new WordpressPoster('judul', 'isi');
		//$wp->post();
	}
}
