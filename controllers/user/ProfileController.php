<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\controllers\user;

use dektrium\user\Finder;
use yii\filters\AccessControl;
use dektrium\user\controllers\ProfileController as Controller;
use yii\web\NotFoundHttpException;

/**
 * ProfileController from Dektrium. 
 * Being upgraded to allowed it show more detail information and 
 * only allowing own user check the result.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Garry Bernardy
 */
class ProfileController extends Controller
{
    /**
     * Redirects to current user's profile.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
		
        $profile = $this->finder->findProfileById(\Yii::$app->user->getId());

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('index', [
            'profile' => $profile,
        ]);
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
		//disable function from yii2-user
		throw new NotFoundHttpException();
    }
}
