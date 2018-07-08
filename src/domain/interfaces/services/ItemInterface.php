<?php

namespace yii2lab\rbac\domain\interfaces\services;

interface ItemInterface {
	
	public function allByUserId(int $userId);
	public function allRoleNamesByUserId(int $userId);
	public function allAssignments($userId);
	public function assign($role, $userId);
	public function revokeRole($userId, $role);
	public function revokeAllRoles($userId);
	public function isHasRole($userId, $roleName);
	public function getUserIdsByRole($roleName);

}