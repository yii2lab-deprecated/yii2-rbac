<?php

namespace yii2lab\rbac\admin;

use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rbac\domain\enums\RbacPermissionEnum;

class Module extends \mdm\admin\Module
{

    var $controllerNamespace = 'mdm\admin\controllers';
    var $viewPath = '//vendor/mdmsoft/yii2-admin/views';
    public $controllerMap = [
        'assignment' => [
            'class' => 'yii2lab\rbac\admin\controllers\AssignmentController',
            'userClassName' => 'yii2module\account\domain\v2\models\User',
            'usernameField' => 'login',
        ],
    ];

    public function behaviors()
    {
        return [
            'access' => Behavior::access(RbacPermissionEnum::MANAGE),
        ];
    }

}
