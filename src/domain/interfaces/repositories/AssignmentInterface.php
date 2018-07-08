<?php

namespace yii2lab\rbac\domain\interfaces\repositories;

interface AssignmentInterface {
	
	/**
	 * Assigns a role to a user.
	 *
	 * @param Role|Permission $role
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Assignment the role assignment information.
	 * @throws \Exception if the role has already been assigned to the user
	 */
	public function assign($role, $userId);
	
	/**
	 * Revokes a role from a user.
	 * @param Role|Permission $role
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return bool whether the revoking is successful
	 */
	public function revoke($role, $userId);
	
	/**
	 * Revokes all roles from a user.
	 * @param mixed $userId the user ID (see [[\yii\web\User::id]])
	 * @return bool whether the revoking is successful
	 */
	public function revokeAll($userId);
	
	/**
	 * Returns the assignment information regarding a role and a user.
	 * @param string $roleName the role name
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return null|Assignment the assignment information. Null is returned if
	 * the role is not assigned to the user.
	 */
	public function getAssignment($roleName, $userId);
	
	/**
	 * Returns all role assignment information for the specified user.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Assignment[] the assignments indexed by role names. An empty array will be
	 * returned if there is no role assigned to the user.
	 */
	public function getAssignments($userId);
	
	/**
	 * Removes all role assignments.
	 */
	public function removeAllAssignments();
	
	public function removeAll();
	
	/**
	 * Returns all user IDs assigned to the role specified.
	 * @param string $roleName
	 * @return array array of user ID strings
	 * @since 2.0.7
	 */
	public function getUserIdsByRole($roleName);
	
	
	
	/*public function revokeOneRole($userId, $role);
	
	public function revokeAllRoles($userId);
	
	public function oneAssign($userId, $itemName);
	
	public function allByUserId($userId);
	
	public function allRoleNamesByUserId($userId);
	
	public function allAssignments($userId);
	
	public function assign($role, $userId);
	
	public function revokeRole($userId, $role);
	
	public function isHasRole($userId, $role);
	
	public function getUserIdsByRole($role);
	
	public function allByRole($role);*/
	

}