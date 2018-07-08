<?php

namespace yii2lab\rbac\domain\repositories\memory;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii2lab\domain\repositories\BaseRepository;
use Yii;
use yii2lab\rbac\domain\interfaces\repositories\ManagerInterface;

/**
 * Class ManagerRepository
 *
 * @package yii2lab\rbac\domain\repositories\memory
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 */
class ManagerRepository extends BaseRepository implements ManagerInterface {
	
	
	
	public function isGuestOnlyAllowed($rule) {
		return $this->isInRules('?', $rule) && !Yii::$app->user->isGuest;
	}

	public function isAuthOnlyAllowed($rule) {
		return $this->isInRules('@', $rule) && Yii::$app->user->isGuest;
	}

	private function isInRules($name, $rules) {
		return in_array($name, ArrayHelper::toArray($rules));
	}
	
}