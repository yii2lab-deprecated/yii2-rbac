<?php

namespace yii2lab\rbac\domain\interfaces\repositories;

interface AssignmentInterface {
	
	public function revokeOneRole($userId, $role);
	
	public function revokeAllRoles($userId);
	
	public function oneAssign($userId, $itemName);
	
	public function allByUserId($userId);
	
	public function allRoleNamesByUserId($userId);
	
	public function allAssignments($userId);
	
	public function assignRole($userId, $role);
	
	public function revokeRole($userId, $role);
	
	public function isHasRole($userId, $role);
	
	public function allUserIdsByRole($role);
	
	public function allByRole($role);
	

}