<?php

namespace yii2lab\rbac\admin\helpers;

use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\rbac\domain\enums\RbacPermissionEnum;

class Menu implements MenuInterface {
	
	public function toArray() {
		return [
			'label' => ['admin', 'rbac'],
			'module' => 'rbac',
			'access' => RbacPermissionEnum::MANAGE,
			'icon' => 'user-o',
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
