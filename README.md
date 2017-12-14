[![Latest Stable Version](https://poser.pugx.org/yii2lab/yii2-rbac/v/stable.png)](https://packagist.org/packages/yii2lab/yii2-rbac)
[![Total Downloads](https://poser.pugx.org/yii2lab/yii2-rbac/downloads.png)](https://packagist.org/packages/yii2lab/yii2-rbac)

## Описание

Модуль для управления ролями пользователей RBAC.

## Ссылки

* [Руководство](guide/ru/README.md)
* [Установка](guide/ru/install.md)

## Установка

Устанавливаем зависимость:

```
composer require yii2module/yii2-rbac
```

Объявляем модуль:

```php
return [
	'modules' => [
		// ...
		'fixtures' => 'yii2module\rbac\Module',
		// ...
	],
];
```

## Документация

### Поиск и добавление правил

Команда поиска правил и добавления в RBAC

```
php yii rbac/rule/add
```

Сканируется файловая система сайта на наличие правил для RBAC.
Затем в RBAC добавляются те правила, которых еще нет.
Существующие правила в RBAC не затираются.

### Генерация констант

Команда генерации констант для ролей, правил и полномочий

```
php yii rbac/const/generate
```
