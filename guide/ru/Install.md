Установка
===

Устанавливаем зависимость:

```
composer require yii2module/yii2-rbac
```

Объявляем консольный модуль:

```php
return [
	'modules' => [
		// ...
		'fixtures' => 'yii2lab\rbac\console\Module',
		// ...
	],
];
```

Объявляем домен:

```php
return [
	'components' => [
		// ...
		'rbac' => [
			'class' => 'yii2lab\domain\Domain',
			'path' => 'yii2lab\rbac\domain',
			'repositories' => [
				'rule' => Driver::FILE,
				'const' => Driver::FILE,
			],
			'services' => [
				'rule',
				'const',
			],
		],
		// ...
	],
];
```
