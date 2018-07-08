<?php

namespace yii2lab\rbac\domain\repositories\base;

use yii2lab\domain\repositories\BaseRepository;
use yii\helpers\VarDumper;

class BaseItemRepository extends BaseRepository {
	
	/**
	 * Loads the authorization data from a PHP script file.
	 *
	 * @param string $file the file path.
	 * @return array the authorization data
	 * @see saveToFile()
	 */
	protected function loadFromFile($file)
	{
		if (is_file($file)) {
			return @include($file);
		}
		
		return [];
	}
	
	/**
	 * Saves the authorization data to a PHP script file.
	 *
	 * @param array $data the authorization data
	 * @param string $file the file path.
	 * @see loadFromFile()
	 */
	protected function saveToFile($data, $file)
	{
		file_put_contents($file, "<?php\nreturn " . VarDumper::export($data) . ";\n", LOCK_EX);
		$this->invalidateScriptCache($file);
	}
	
	/**
	 * Invalidates precompiled script cache (such as OPCache or APC) for the given file.
	 * @param string $file the file path.
	 * @since 2.0.9
	 */
	private function invalidateScriptCache($file)
	{
		if (function_exists('opcache_invalidate')) {
			opcache_invalidate($file, true);
		}
		if (function_exists('apc_delete_file')) {
			@apc_delete_file($file);
		}
	}
	
}
