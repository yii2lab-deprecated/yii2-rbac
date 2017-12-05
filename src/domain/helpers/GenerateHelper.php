<?php

namespace yii2lab\rbac\domain\helpers;

use yii2lab\helpers\ClassGeneratorHelper;

class GenerateHelper
{

	const TYPE_PERMISSION = 'PermissionEnum';
	const TYPE_ROLE = 'RoleEnum';
	const TYPE_RULE = 'RuleEnum';
	
	public static function getConstListFromCollection($collection, $removePrefix = false) {
		$constList = [];
		foreach($collection as $data) {
			$constList[] = [
				'name' => self::getConstName($data->name, $removePrefix),
				'value' => $data->name,
				'description' => $data->description,
			];
		}
		return $constList;
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
