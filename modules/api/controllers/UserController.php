<?php

namespace app\modules\api\controllers;

use app\models\LoginForm;
use app\modules\api\components\BaseActiveController;
use Yii;
use yii\web\MethodNotAllowedHttpException;

/**
 * 
 * @author Garry
 */
class UserController extends BaseActiveController
{
    public $modelClass = '';
    
    public function actions() 
    {
        $actions = parent::actions();
        
        /** hapus default action bawaan dari ActiveController */
        unset($actions['index']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        
        return $actions;
    }
    
    public function actionLogin()
    {
        /** action ini hanya boleh diakses oleh method POST */
        if(!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException('this method is can only POST');
        }
        
        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();
        
        // jika validasi gagal, maka return model (getErrors)
        if (!$model->validate()) {
            return $model;
        }
        
        if ($model->login()) {
            return [
                'name' => 'Login Success',
                'message' => 'This is successfull message',
                'status' => 200,
                'accessToken' => $model->getUser()->access_token,
            ];
        }
        
        return [
            'name' => 'Login Fail',
            'message' => 'This is failure message',
            'status' => 99,
        ];
    }
}
