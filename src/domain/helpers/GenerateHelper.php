<?php

namespace yii2lab\rbac\domain\helpers;

use yii2lab\extension\code\helpers\generator\ClassGeneratorHelper;
use yii2lab\extension\code\helpers\generator\EnumGeneratorHelper;

class GenerateHelper
{

	const TYPE_PERMISSION = 'PermissionEnum';
	const TYPE_ROLE = 'RoleEnum';
	const TYPE_RULE = 'RuleEnum';
	
	const PREFIX_PERMISSION = 'O';
	const PREFIX_ROLE = 'R';
	
	private static $doc = [
		'Этот класс был сгенерирован автоматически.',
		'Не вносите в данный файл изменения, они затрутся при очередной генерации.',
		'Изменить набор констант можно через управление RBAC в админке.',
	];
	
	public static function getConstListFromCollection($collection, $removePrefix = false) {
		$constList = [];
		foreach($collection as $data) {
			$description = property_exists($data, 'description') ? $data->description : '';
			$constList[] = [
				'name' => self::getConstName($data->name, $removePrefix),
				'value' => $data->name,
				'description' => $description,
			];
		}
		return $constList;
	}
	
	public static function generateEnum($className, $constList) {
		EnumGeneratorHelper::generate([
			'className' => $className,
			'const' => $constList,
			'doc' => self::$doc,
		]);
	}
	
	private static function deletePrefix($name, $prefix = false) {
		if($prefix) {
			$name = preg_replace('~(^' . $prefix . '_)~', '', $name);
		}
		return $name;
	}
	
	private static function getConstName($name, $removePrefix = false) {
		$constName = ClassGeneratorHelper::toConstName($name);
		$constName = str_replace('*', 'ALL', $constName);
		$constName = self::deletePrefix($constName, $removePrefix);
		return $constName;
	}
	
}
