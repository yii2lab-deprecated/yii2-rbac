<?php

namespace yii2lab\rbac\domain;

use yii2lab\domain\enums\Driver;
use yii2lab\rbac\domain\repositories\disc\ItemRepository;
use yii2lab\rbac\domain\repositories\disc\RuleRepository;

/**
 * Class Domain
 * 
 * @package yii2lab\rbac\domain
 * @property-read \yii2lab\rbac\domain\interfaces\services\AssignmentInterface $assignment
 * @property-read \yii2lab\rbac\domain\interfaces\services\ItemInterface $item
 * @property-read \yii2lab\rbac\domain\interfaces\services\ManagerInterface $manager
 * @property-read \yii2lab\rbac\domain\interfaces\services\RuleInterface $rule
 * @property-read \yii2lab\rbac\domain\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2lab\rbac\domain\interfaces\services\RoleInterface $role
 * @property-read \yii2lab\rbac\domain\interfaces\services\ConstInterface $const
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
				'role' => Driver::TPS,
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
				'role',
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