<?php

namespace yii2lab\rbac\admin\helpers;

// todo: отрефакторить - сделать нормальный интерфейс и родителя

class Menu {
	
	static function getMenu() {
		return [
			'label' => t('admin', 'rbac'),
			'icon' => 'users',
			'items' => [
				[
					'label' => ['admin', 'rbac_permission'],
					'url' => 'rbac/permission',
				],
				[
					'label' => ['admin', 'rbac_role'],
					'url' => 'rbac/role',
				],
				[
					'label' => ['admin', 'rbac_rule'],
					'url' => 'rbac/rule',
				],
				[
					'label' => ['admin', 'rbac_assignment'],
					'url' => 'rbac/assignment',
				],
			],
		];
	}
	
}
