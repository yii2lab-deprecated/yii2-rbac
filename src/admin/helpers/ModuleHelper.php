<?php

namespace yii2lab\rbac\admin\helpers;

use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rbac\domain\enums\RbacPermissionEnum;

class ModuleHelper {
	
	public static function config() {
		return [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'yii2lab\rbac\admin\controllers\AssignmentController',
                    'userClassName' => 'yii2module\account\domain\v2\models\User',
                    'usernameField' => 'login',
                ],
            ],
            'as access' => Behavior::access(RbacPermissionEnum::MANAGE),
        ];
	}
	
}
