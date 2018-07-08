<?php

namespace yii2lab\rbac\domain\repositories\tps;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\helpers\AssignmentHelper;
use yii2woop\common\domain\account\v1\helpers\UserHelper;
use yii2woop\common\repositories\base\BaseTpsRepository;
use yii2woop\generated\transport\TpsCommands;

class AssignmentRepository extends BaseTpsRepository implements AssignmentInterface {
	
	public function revokeOneRole($userId, $role) {
		prr('// TODO: Implement revokeOneRole() method.',1,1);
	}
	
	public function revokeAllRoles($userId) {
		prr('// TODO: Implement revokeAllRoles() method.',1,1);
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
	
	public function assign($role, $userId) {
		$loginEntity = Yii::$domain->account->login->oneById($userId);
		$request = TpsCommands::assignSubjectRoles($loginEntity->login, [$role]);
		$result = $this->send($request);
	}
	
	public function revokeRole($userId, $role) {
		prr('// TODO: Implement revokeRole() method.',1,1);
	}
	
	public function isHasRole($userId, $role) {
		try {
			$entity = $this->oneAssign($userId, $role);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function getUserIdsByRole($role) {
		prr('// TODO: Implement getUserIdsByRole() method.',1,1);
	}
	
	public function allByRole($role) {
		prr('// TODO: Implement allByRole() method.',1,1);
	}
	
	public function allByUserId($userId) {
		prr('// TODO: Implement allByUserId() method.',1,1);
	}
}
