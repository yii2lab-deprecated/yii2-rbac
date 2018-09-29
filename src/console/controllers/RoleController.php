<?php

namespace yii2lab\rbac\console\controllers;

use Yii;
use yii2lab\extension\console\base\Controller;
use yii2lab\helpers\Behavior;
use yii2woop\generated\exception\tps\NotAuthenticatedException;

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
		}catch(NotAuthenticatedException $e){
			Yii::$app->cache->set('identity', null);
			\App::$domain->account->auth->breakSession();
		}
		echo 'success';
	}

	
}
