<?php

namespace yii2lab\rbac\console\controllers;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\web\helpers\Behavior;

class RoleController extends Controller
{
	
	public function behaviors()
	{
		return [
			'authenticator' => Behavior::auth(),
		];
	}
	/**
	 * Search and add RBAC rules
	 */
	public function actionUpdate()
	{
		try{
			\App::$domain->rbac->role->update();
		}catch(UnauthorizedHttpException $e){
			Yii::$app->cache->set('identity', null);
			\App::$domain->account->auth->breakSession();
		}
		echo 'success';
	}

	
}
