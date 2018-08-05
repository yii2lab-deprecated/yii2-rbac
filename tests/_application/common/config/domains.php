<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;
use yii2lab\domain\enums\Driver;
use yii2lab\rbac\domain\repositories\disc\ItemRepository;
use yii2lab\rbac\domain\repositories\disc\RuleRepository;

$config = [
    'rbac' => [
	    'class' => 'yii2lab\rbac\domain\Domain',
        'repositories' => [
            'rule' => [
                'ruleFile' => '@vendor/yii2lab/yii2-test/src/base/_application/common/data/rbac/rules.php',
            ],
            'item' => [
                'itemFile' => '@vendor/yii2lab/yii2-test/src/base/_application/common/data/rbac/items.php',
            ],
        ],
    ],
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
