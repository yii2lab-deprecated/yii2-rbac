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
 * @property \yii2lab\rbac\domain\services\AssignmentService $assignment
 * @property \yii2lab\rbac\domain\services\ManagerService $manager
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'rule' => Driver::FILE, 
				'const' => Driver::FILE,
				'assignment' => Driver::primary(),
				'manager' => Driver::MEMORY,
			], 
			'services' => [ 
				'rule', 
				'const',
				'assignment',
				'manager',
			], 
		];
	}
	
}