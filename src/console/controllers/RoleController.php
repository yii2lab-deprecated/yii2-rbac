<?php

namespace yii2lab\rbac\console\controllers;

use yii\web\UnauthorizedHttpException;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\web\helpers\Behavior;
use yii2woop\generated\exception\tps\NotAuthenticatedException;

class RoleController extends Controller {
	
	public function behaviors() {
		return [
			'authenticator' => Behavior::auth(),
		];
	}
	
	/**
	 * Search and add RBAC rules
	 */
	public function actionUpdate() {
		
		try {
			\App::$domain->rbac->role->update();
		} catch(NotAuthenticatedException $e) {
			\App::$domain->account->auth->breakSession();
			echo 'NotAuthenticatedException';
			return;
		} catch(UnauthorizedHttpException $e) {
			\App::$domain->account->auth->breakSession();
			echo 'UnauthorizedHttpException';
			return;
		}
		echo 'success';
	}
	
	
}
