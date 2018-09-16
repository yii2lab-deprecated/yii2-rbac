<?php

namespace yii2lab\rbac\domain\filters;

use Yii;
use yii\helpers\Inflector;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2module\error\domain\helpers\CodeHelper;
use yii2module\error\domain\helpers\OutputHelper;

class PermissionException extends BaseScenario {
	
	const MESSAGE_REGEXP = '#Undefined\sclass\sconstant\s\'([^\']+)\'#';
	const ENUM_REGEXP = '#PermissionEnum::([A-Z_]+)#';
	
	public function run() {
		if(!YII_ENV_DEV) {
			return;
		}
		/** @var \Throwable $exception */
		$exception = $this->getData();
		if (!$exception instanceof \Error) {
			return;
		}
		$constName = $this->isMatch($exception);
		if($constName) {
			$permissionName = $this->normalizePermissionName($constName);
			$this->createPermission($permissionName);
			OutputHelper::criticalError('Permission <b>"' . $permissionName . '"</b> created! <br/>Reload your page!', 'success');
		}
	}
	
	private function isMatch(\Throwable $exception) {
		$isMatch = preg_match(self::MESSAGE_REGEXP, $exception->getMessage(), $matches);
		if($isMatch) {
			$codeLine = CodeHelper::codeLineFromException($exception);
			$isMatch2 = preg_match(self::ENUM_REGEXP, $codeLine, $matches2);
			if($isMatch2) {
				return $matches2[1];
			}
		}
		return false;
	}
	
	private function createPermission($permissionName) {
		$item = \App::$domain->rbac->item->createPermission($permissionName);
		$item->description = '### Это полномочие сгенерированно автоматически, по причине его отсутсвия!';
		\App::$domain->rbac->item->addItem($item);
	}
	
	private function normalizePermissionName($constName) {
		$permissionName = strtolower($constName);
		$permissionName = Inflector::camelize($permissionName);
		$permissionName = 'o' . $permissionName;
		return $permissionName;
	}
}
