<?php

namespace yii2lab\rbac\domain\services;

use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\interfaces\services\RoleInterface;

/**
 * Class RuleService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property \yii2lab\rbac\domain\interfaces\repositories\RoleInterface $repository
 */
class RoleService extends BaseService implements RoleInterface {
	
	public function update()
	{
		$this->repository->update();
	}
}
