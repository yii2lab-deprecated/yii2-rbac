<?php

namespace yii2lab\rbac\domain\interfaces\services;

interface AssignmentInterface {
	
	public function allByUserId(int $userId);
	public function allRoleNamesByUserId(int $userId);
	public function allAssignments($userId);
	public function assignRole($userId, $role);
	public function revokeRole($userId, $role);
	public function revokeAllRoles($userId);
	public function isHasRole($userId, $roleName);
	public function allUserIdsByRole($roleName);

}