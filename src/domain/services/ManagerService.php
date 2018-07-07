<?php

namespace yii2lab\rbac\domain\services;

use Yii;
use yii\web\ForbiddenHttpException;
use yii2lab\domain\services\BaseService;
use yii2lab\rbac\domain\interfaces\services\ManagerInterface;

/**
 * Class RbacService
 *
 * @package yii2lab\rbac\domain\services
 * @property \yii2lab\rbac\domain\interfaces\repositories\ManagerInterface $repository
 */
class ManagerService extends BaseService implements ManagerInterface {
	
	public function can($rule, $param = null, $allowCaching = true) {
		if($this->repository->isGuestOnlyAllowed($rule)) {
			throw new ForbiddenHttpException();
		}
		if($this->repository->isAuthOnlyAllowed($rule)) {
			Yii::$domain->account->auth->breakSession();
		}
		if(!Yii::$app->user->can($rule, $param, $allowCaching)) {
			if(Yii::$app->user->isGuest) {
				Yii::$domain->account->auth->breakSession();
			}
			throw new ForbiddenHttpException();
		}
	}

}
