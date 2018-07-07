<?php

namespace yii2lab\rbac\domain\interfaces\services;

interface ManagerInterface {

	public function can($rule, $param = null, $allowCaching = true);

}