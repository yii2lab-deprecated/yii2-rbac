Константы
===

При любом изменении в RBAC, генерируются enum-классы:

* PermissionEnum
* RoleEnum
* RuleEnum

Эти константы нужны для использования их в коде.

Например, назначаем полномочия для контроллера:

```php
return [
	'access' => [
		'class' => AccessControl::class,
		'rules' => [
			[
				'allow' => true,
				'actions'=>['set-device-token'],
				'roles' => [PermissionEnum::SET_DEVICE_TOKEN],
			],
		],
	],
];
```
