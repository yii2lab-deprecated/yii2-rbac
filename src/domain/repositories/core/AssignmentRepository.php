<?php

namespace yii2lab\rbac\domain\repositories\core;

use Yii;
use yii\rbac\Assignment;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2lab\rbac\domain\helpers\AssignmentHelper;

class AssignmentRepository extends BaseRepository implements AssignmentInterface {
	
	public function revoke($role, $userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revoke($role, $userId) method.
	}
	
	public function revokeAll($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revokeAll() method.
	}
	
	public function allRoleNamesByUserId($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allRoleNamesByUserId() method.
	}
	
	public function getAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		/** @var LoginEntity $identity */
		$identity = Yii::$app->user->identity;
		if($identity->id == $userId) {
			return AssignmentHelper::forge($userId, $identity->roles);
		}
	}
	
	public function assign($role, $userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement assignRole() method.
	}
	
	public function isHasRole($userId, $role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement isHasRole() method.
	}
	
	public function getUserIdsByRole($role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement getUserIdsByRole() method.
	}
	
	public function allByRole($role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allByRole() method.
	}
	
	/**
	 * Returns the assignment information regarding a role and a user.
	 *
	 * @param string     $roleName the role name
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 *
	 * @return null|Assignment the assignment information. Null is returned if
	 * the role is not assigned to the user.
	 */
	public function getAssignment($roleName, $userId) {
		// TODO: Implement getAssignment() method.
	}
	
	/**
	 * Removes all role assignments.
	 */
	public function removeAllAssignments() {
		// TODO: Implement removeAllAssignments() method.
	}
	
	/*public function removeAll() {
		// TODO: Implement removeAll() method.
	}*/
	public function revokeAllByItemName($itemName) {
		// TODO: Implement revokeAllByItemName() method.
	}
	
	public function updateRoleName($itemName, $newItemName) {
		// TODO: Implement updateRoleName() method.
	}
}