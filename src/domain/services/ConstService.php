<?php

namespace yii2lab\rbac\domain\services;

use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\interfaces\services\ConstInterface;

/**
 * Class ConstService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\repositories\file\ConstRepository $repository
 * @property \yii2lab\rbac\domain\Domain $domain
 */
class ConstService extends BaseService implements ConstInterface {

	public function generateAll() {
		$result = [];
		$result['Permissions'] = $this->generatePermissions();
		$result['Roles'] = $this->generateRoles();
		//$result['Rules'] = $this->generateRules();
		return count($result['Permissions']) + count($result['Roles'])/* + count($result['Rules'])*/;
	}

	public function generatePermissions() {
		$permissionCollection = $this->domain->item->getPermissions();
		return $this->repository->generatePermissions($permissionCollection);
	}

	public function generateRoles() {
		$roleCollection = $this->domain->item->getRoles();
		return $this->repository->generateRoles($roleCollection);
	}

	public function generateRules() {
		$ruleCollection = $this->domain->rule->getRules();
		return $this->repository->generateRules($ruleCollection);
	}

}
