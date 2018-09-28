<?php

namespace yii2lab\rbac\domain\services;

use common\enums\app\AppEnum;
use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\Item;
use yii\rbac\Rule;
use yii2lab\domain\interfaces\services\CrudInterface;
use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\interfaces\services\RoleInterface;
use yii2lab\rbac\domain\interfaces\services\RuleInterface;
use yii2lab\rbac\domain\repositories\disc\RuleRepository;
use yii2woop\common\domain\rbac\repositories\tps\RoleRepository;
use yii2woop\generated\transport\TpsCommands;

/**
 * Class RuleService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property RoleRepository $repository
 */
class RoleService extends BaseService implements RoleInterface {
	
	public function update()
	{
		$this->repository->update();
	}
}
