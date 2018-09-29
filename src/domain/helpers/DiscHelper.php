<?php

namespace yii2lab\rbac\domain\helpers;

use Yii;
use yii\helpers\VarDumper;

class DiscHelper {
	
	/**
	 * Saves the authorization data to a PHP script file.
	 *
	 * @param array $data the authorization data
	 * @param string $file the file path.
	 */
	public static function saveToFile($data, $file) {
		$file = Yii::getAlias($file);
		file_put_contents($file, "<?php\nreturn " . VarDumper::export($data) . ";\n", LOCK_EX);
		self::invalidateScriptCache($file);
	}
	
	/**
	 * Loads the authorization data from a PHP script file.
	 *
	 * @param string $file the file path.
	 * @return array the authorization data
	 */
	public static function loadFromFile($file)
	{
		$file = Yii::getAlias($file);
		if (is_file($file)) {
			return @include($file);
		}
		
		return [];
	}
	
	/**
	 * Invalidates precompiled script cache (such as OPCache or APC) for the given file.
	 * @param string $file the file path.
	 * @since 2.0.9
	 */
	private static function invalidateScriptCache($file)
	{
		if (function_exists('opcache_invalidate')) {
			opcache_invalidate($file, true);
		}
		if (function_exists('apc_delete_file')) {
			@apc_delete_file($file);
		}
	}
	
}
