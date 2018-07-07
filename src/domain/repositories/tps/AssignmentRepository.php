<?php

namespace yii2lab\rbac\domain\repositories\tps;

use yii\web\NotFoundHttpException;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\helpers\AssignmentHelper;
use yii2woop\common\domain\account\v1\helpers\UserHelper;
use yii2woop\common\repositories\base\BaseTpsRepository;

class AssignmentRepository extends BaseTpsRepository implements AssignmentInterface {
	
	public function revokeOneRole($userId, $role) {
		// TODO: Implement revokeOneRole() method.
	}
	
	public function revokeAllRoles($userId) {
		// TODO: Implement revokeAllRoles() method.
	}
	
	public function oneAssign($userId, $itemName) {
		return [];
	}
	
	public function allRoleNamesByUserId($userId) {
		$loginEntity = UserHelper::oneById($userId);
		return $loginEntity->roles;
	}
	
	public function allAssignments($userId) {
		$loginEntity = UserHelper::oneById($userId);
		$collection = AssignmentHelper::forge($userId, $loginEntity->roles);
		return $collection;
	}
	
	public function assignRole($userId, $role) {
		// TODO: Implement assignRole() method.
	}
	
	public function revokeRole($userId, $role) {
		// TODO: Implement revokeRole() method.
	}
	
	public function isHasRole($userId, $role) {
		try {
			$entity = $this->oneAssign($userId, $role);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function allUserIdsByRole($role) {
		// TODO: Implement allUserIdsByRole() method.
	}
	
	public function allByRole($role) {
		// TODO: Implement allByRole() method.
	}
	
	public function allByUserId($userId) {
		// TODO: Implement allByUserId() method.
	}
}
