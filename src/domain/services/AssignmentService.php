<?php

namespace yii2lab\rbac\domain\services;

use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\rbac\domain\interfaces\services\AssignmentInterface;
use yii2lab\rbac\domain\repositories\disc\AssignmentRepository;

/**
 * Class AssignmentService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property AssignmentRepository $repository
 */
class AssignmentService extends BaseActiveService implements AssignmentInterface {
	
	/**
	 * @var array
	 */
	protected $assignments = []; // userId, itemName => assignment
	
	/*public function allByUserId(int $userId) {
		return $this->repository->allByUserId($userId);
	}*/
	
	public function allRoleNamesByUserId(int $userId) {
		return $this->repository->allRoleNamesByUserId($userId);
	}
	
	public function allAssignments($userId) {
		return $this->repository->allAssignments($userId);
	}
	
	public function getAssignment($roleName, $userId) {
		return $this->repository->getAssignment($roleName, $userId);
	}
	
	public function assign($role, $userId) {
		return $this->repository->assign($role, $userId);
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
	
	public function getUserIdsByRole($roleName) {
		return $this->repository->getUserIdsByRole($roleName);
	}
	
	public function updateItem($name, $item) {
		return $this->repository->updateItem($name, $item);
	}
	
	/**
	 * @inheritdoc
	 */
	public function removeItem($item)
	{
		//return $this->repository->removeItem($item);
	}
}

