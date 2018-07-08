<?php

namespace yii2lab\rbac\domain\services;

use Yii;
use yii2lab\domain\services\base\BaseService;

/**
 * Class ConstService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\repositories\file\ConstRepository $repository
 */
class ConstService extends BaseService {

	public function generateAll() {
		$result = [];
		$result['Permissions'] = $this->generatePermissions();
		$result['Roles'] = $this->generateRoles();
		$result['Rules'] = $this->generateRules();
		return count($result['Permissions']) + count($result['Roles']) + count($result['Rules']);
	}

	public function generatePermissions() {
		$permissionCollection = Yii::$app->authManager->getPermissions();
		return $this->repository->generatePermissions($permissionCollection);
	}

	public function generateRoles() {
		$roleCollection = Yii::$app->authManager->getRoles();
		return $this->repository->generateRoles($roleCollection);
	}

	public function generateRules() {
		$ruleCollection = Yii::$app->authManager->getRules();
		return $this->repository->generateRules($ruleCollection);
	}

}
