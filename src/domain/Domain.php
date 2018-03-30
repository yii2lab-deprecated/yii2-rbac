<?php

namespace yii2lab\rbac\domain;

use yii2lab\domain\enums\Driver;

/**
 * Class Domain
 *
 * @package yii2lab\rbac\domain
 *
 * @property \yii2lab\rbac\domain\services\RuleService $rule
 * @property \yii2lab\rbac\domain\services\ConstService $const
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [ 
				'rule' => Driver::FILE, 
				'const' => Driver::FILE, 
			], 
			'services' => [ 
				'rule', 
				'const', 
			], 
		];
	}
	
}