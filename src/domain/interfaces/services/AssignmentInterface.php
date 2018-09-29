<?php

namespace yii2lab\rbac\domain\interfaces\services;

interface AssignmentInterface {
	
	//public function allByUserId(int $userId);
	public function allRoleNamesByUserId(int $userId);
	public function getAssignments($userId);
	public function getAssignment($roleName, $userId);
	public function assign($role, $userId);
	public function revoke($role, $userId);
	public function revokeAll($userId);
	public function isHasRole($userId, $roleName);
	public function getUserIdsByRole($roleName);
	public function revokeAllByItemName($itemName);
	public function updateRoleName($itemName, $newItemName);
}