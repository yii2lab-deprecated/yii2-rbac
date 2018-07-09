<?php

namespace yii2lab\rbac\domain\helpers;

use yii\rbac\Assignment;

class AssignmentHelper {
	
	public static function forge($userId, $roleName) {
		if(empty($roleName) || empty($userId)) {
			return [];
		}
		if(is_array($roleName)) {
			return self::forgeAssignments($userId, $roleName);
		} else {
			return self::forgeAssignment($userId, $roleName);
		}
	}
	
	private static function forgeAssignment($userId, $roleName) {
		$assignment = new Assignment([
			'userId' => $userId,
			'roleName' => $roleName,
			'createdAt' => 1486774821,
		]);
		return $assignment;
	}
	
	private static function forgeAssignments($userId, $roleNames) {
		
		$assignments = [];
		foreach($roleNames as $roleName) {
			$assignments[$roleName] = self::forgeAssignment($userId, $roleName);
		}
		return $assignments;
	}
}
