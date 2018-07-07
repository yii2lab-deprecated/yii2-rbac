<?php

namespace yii2lab\rbac\domain\repositories\disc;

use Yii;
use yii\rbac\Assignment;
use yii2lab\domain\repositories\BaseRepository;

class AssignmentRepository extends BaseRepository {

	public function allAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $userId) {
			$roles = Yii::$app->user->identity->roles;
		} else {
			$userEntity = Yii::$domain->account->login->oneById($userId);
			$roles = $userEntity->roles;
		}
		return $this->forgeAssignments($userId, $roles);
	}

    public function allUserIdsByRole($role) {
        return [];
    }
	
	private function forgeAssignment($userId, $roleName) {
		$assignment = new Assignment([
			'userId' => $userId,
			'roleName' => $roleName,
			'createdAt' => 1486774821,
		]);
		return $assignment;
	}
	
	private function forgeAssignments($userId, $roleNames) {
		if(empty($roleNames)) {
			return [];
		}
		$assignments = [];
		foreach($roleNames as $roleName) {
			$assignments[$roleName] = $this->forgeAssignment($userId, $roleName);
		}
		return $assignments;
	}
	
}