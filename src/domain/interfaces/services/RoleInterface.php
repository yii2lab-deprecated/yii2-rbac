<?php

namespace yii2lab\rbac\domain\interfaces\services;

use yii\base\InvalidConfigException;
use yii\rbac\Item;
use yii\rbac\Rule;

interface RoleInterface {
	
	public function update();
}