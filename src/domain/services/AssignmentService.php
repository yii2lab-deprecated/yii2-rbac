<?php

namespace yii2lab\rbac\domain\services;

use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\rbac\domain\interfaces\services\AssignmentInterface;
use yii2lab\rbac\domain\repositories\tps\AssignmentRepository;

/**
 * Class AssignmentService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property AssignmentRepository $repository
 */
class AssignmentService extends BaseActiveService implements AssignmentInterface {

	public function allByUserId(int $userId) {
		return $this->repository->allByUserId($userId);
	}
	
	public function roleNamesByUserId(int $userId) {
		return $this->repository->allRoleNamesByUserId($userId);
	}
	
	public function allAssignments($userId) {
		return $this->repository->allAssignments($userId);
	}
	
	public function assignRole($userId, $role) {
		return $this->repository->assignRole($userId, $role);
	}
	
	public function revokeRole($userId, $role) {
		return $this->repository->revokeRole($userId, $role);
	}
	
	public function revokeAllRoles($userId) {
		return $this->repository->revokeAllRoles($userId);
	}
	
	public function isHasRole($userId, $roleName) {
		return $this->repository->isHasRole($userId, $roleName);
	}
	
	public function allUserIdsByRole($roleName) {
		return $this->repository->allUserIdsByRole($roleName);
	}
}

