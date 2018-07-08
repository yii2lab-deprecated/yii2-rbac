<?php

namespace yii2lab\rbac\domain;

use yii2lab\domain\enums\Driver;
use yii2lab\rbac\domain\repositories\disc\ItemRepository;
use yii2lab\rbac\domain\repositories\disc\RuleRepository;

/**
 * Class Domain
 *
 * @package yii2lab\rbac\domain
 *
 * @property \yii2lab\rbac\domain\services\RuleService $rule
 * @property \yii2lab\rbac\domain\services\ConstService $const
 * @property \yii2lab\rbac\domain\services\AssignmentService $assignment
 * @property \yii2lab\rbac\domain\services\ManagerService $manager
 * @property \yii2lab\rbac\domain\services\ItemService $item
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'rule' => [
					'class' => RuleRepository::class,
					//'itemFile' => '@common/data/rbac/items.php',
					'ruleFile' => '@common/data/rbac/rules.php',
					//'defaultRoles' => ['rGuest'],
				],
				'const' => Driver::FILE,
				'assignment' => Driver::primary(),
				'manager' => Driver::MEMORY,
				'item' => [
					'class' => ItemRepository::class,
					'itemFile' => '@common/data/rbac/items.php',
					//'ruleFile' => '@common/data/rbac/rules.php',
					'defaultRoles' => ['rGuest'],
				],
				'misc' => Driver::DISC,
			], 
			'services' => [ 
				'rule', 
				'const',
				'assignment',
				'manager',
				'item'/* => [
					'defaultRoles' => ['rGuest'],
				]*/,
				'misc',
			], 
		];
	}
	
}