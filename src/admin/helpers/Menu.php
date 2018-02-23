<?php

namespace yii2lab\rbac\admin\helpers;

use common\enums\rbac\PermissionEnum;
use yii2lab\helpers\interfaces\MenuInterface;

class Menu implements MenuInterface {
	
	public function toArray() {
		return [
			'label' => ['admin', 'rbac'],
			'module' => 'rbac',
			'access' => PermissionEnum::RBAC_MANAGE,
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
