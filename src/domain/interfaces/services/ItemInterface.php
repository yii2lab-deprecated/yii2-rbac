<?php

namespace yii2lab\rbac\domain\interfaces\services;

interface ItemInterface {
	
	public function allByUserId(int $userId);
	public function allRoleNamesByUserId(int $userId);
	public function getAssignments($userId);
	public function assign($role, $userId);
	public function revoke($role, $userId);
	public function revokeAll($userId);
	public function isHasRole($userId, $roleName);
	public function getUserIdsByRole($roleName);

}