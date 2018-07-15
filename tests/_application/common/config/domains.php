<?php

use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\domain\enums\Driver;
use yii2lab\test\helpers\TestHelper;

$config = [
	'rbac' => 'yii2lab\rbac\domain\Domain',
	'account' => [
		'class' => Domain::class,
		'path' => 'yii2module\account\domain\v2',
		'repositories' => [
			'auth' => Driver::FILEDB,
			'login' => Driver::FILEDB,
			'registration' => Driver::FILEDB,
			'temp' => Driver::ACTIVE_RECORD,
			'restorePassword' => Driver::FILEDB,
			'security' => Driver::FILEDB,
			'test' => Driver::DISC,
			'rbac' => Driver::MEMORY,
			'confirm' => Driver::ACTIVE_RECORD,
			'assignment' => Driver::FILEDB,
			'token' => Driver::ACTIVE_RECORD,
		],
		'services' => [
			'auth',
			'login' => [
				'prefixList' => ['B', 'BS', 'R', 'QRS'],
				'defaultRole' => null,
				'defaultStatus' => 1,
				'forbiddenStatusList' => [0],
			],
			'registration',
			'temp',
			'restorePassword',
			'security',
			'test',
			'balance',
			'rbac',
			'confirm',
			'assignment',
			'token',
		],
	],
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
