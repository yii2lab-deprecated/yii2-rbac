<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;

$config = [
	'components' => [
		'filedb' => [
			'class' => 'yii2tech\filedb\Connection',
			'path' => '@yii2module/account/domain/v2/fixtures/data',
		],
	],
];

$baseConfig = TestHelper::loadConfig('common/config/main.php');
return ArrayHelper::merge($baseConfig, $config);
