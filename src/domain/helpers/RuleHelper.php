<?php

namespace yii2lab\rbac\domain\helpers;

use Yii;

class RuleHelper
{

	public static function namespacesFromPathList($pathList)
	{
		$result = [];
		foreach ($pathList as $path) {
			$namespace = self::pathToNamespace($path);
			if($namespace) {
				$result[] = $namespace;
			}
		}
		return $result;
	}

	private static function pathToNamespace($path)
	{
		$namespace = str_replace('.php', '', $path);
		$namespace = str_replace('/', '\\', $namespace);
		if (is_subclass_of($namespace, 'yii\rbac\Rule')) {
			return $namespace;
		}
		return false;
	}

}
