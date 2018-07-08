<?php

namespace yii2lab\rbac\domain\repositories\core;

use Yii;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\AssignmentHelper;

class AssignmentRepository extends BaseRepository implements AssignmentInterface {
	
	public function revokeOneRole($userId, $role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revokeOneRole() method.
	}
	
	public function revokeAll($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revokeAll() method.
	}
	
	public function oneAssign($userId, $itemName) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement oneAssign() method.
	}
	
	public function allByUserId($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allByUserId() method.
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
	
	public function revoke($role, $userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revoke($role, $userId) method.
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
	
}