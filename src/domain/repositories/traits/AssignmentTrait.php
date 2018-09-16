<?php

namespace yii2lab\rbac\domain\repositories\traits;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\rbac\domain\helpers\AssignmentHelper;

/**
 * Trait AssignmentTrait
 *
 * @package yii2lab\rbac\domain\repositories\traits
 *
 * @property ActiveRecord $model
 */
trait AssignmentTrait {
	
	public function tableName()
	{
		return 'user_assignment';
	}
	
	public function uniqueFields() {
		return ['user_id', 'item_name'];
	}
	
	
	
	
	
	
	
	
	/**
	 * Returns the assignment information regarding a role and a user.
	 *
	 * @param string     $roleName the role name
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 *
	 * @return null|Assignment the assignment information. Null is returned if
	 * the role is not assigned to the user.
	 */
	public function getAssignment($roleName, $userId) {
		// TODO: Implement getAssignment() method.
	}
	
	/**
	 * Removes all role assignments.
	 */
	public function removeAllAssignments() {
		// TODO: Implement removeAllAssignments() method.
	}
	
	/*public function removeAll() {
		// TODO: Implement removeAll() method.
	}*/
	
	public function revokeAllByItemName($itemName) {
		// TODO: Implement revokeAllByItemName() method.
	}
	
	public function updateRoleName($itemName, $newItemName) {
		// TODO: Implement updateRoleName() method.
	}
	
	
	/**
	 * Revokes all roles from a user.
	 *
	 * @param mixed $userId the user ID (see [[\yii\web\User::id]])
	 *
	 * @return bool whether the revoking is successful
	 */
	public function revokeAll($userId) {
		return $this->model->deleteAll(['user_id' => $userId]);
	}
	
	public function oneAssign($userId, $itemName) {
		$query = Query::forge();
		$query->where('user_id', $userId);
		$query->where('item_name', $itemName);
		return $this->one($query);
	}
	
	private function allByUserId($userId) {
		$query = Query::forge();
		$query->where('user_id', $userId);
		return $this->all($query);
	}
	
	public function allRoleNamesByUserId($userId) {
		if(empty($userId)) {
			return [];
		}
		$roleCollection = $this->allByUserId($userId);
		$roles = ArrayHelper::getColumn($roleCollection, 'item_name');
		return $roles;
	}
	
	/**
	 * Returns all role assignment information for the specified user.
	 *
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 *
	 * @return Assignment[] the assignments indexed by role names. An empty array will be
	 * returned if there is no role assigned to the user.
	 */
	public function getAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		$roles = $this->allRoleNamesByUserId($userId);
		return AssignmentHelper::forge($userId, $roles);
	}
	
	/**
	 * Assigns a role to a user.
	 *
	 * @param Role|Permission $role
	 * @param string|int      $userId the user ID (see [[\yii\web\User::id]])
	 *
	 * @return Assignment the role assignment information.
	 * @throws \Exception if the role has already been assigned to the user
	 */
	public function assign($role, $userId) {
		
		$userId = $this->getId($userId);
		$entity = \App::$domain->account->login->oneById($userId);
		$assignEntity = $this->forgeEntity([
			'user_id' => $userId,
			'item_name' => $role,
		]);
		$this->insert($assignEntity);
		return AssignmentHelper::forge($userId, $role);
	}
	
	/**
	 * Revokes a role from a user.
	 *
	 * @param Role|Permission $role
	 * @param string|int      $userId the user ID (see [[\yii\web\User::id]])
	 *
	 * @return bool whether the revoking is successful
	 */
	public function revoke($role, $userId) {
		$userId = $this->getId($userId);
		$entity = \App::$domain->account->login->oneById($userId);
		$this->model->deleteAll(['user_id' => $userId, 'item_name' => $role]);
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
		$collection = $this->allByRole($role);
		return ArrayHelper::getColumn($collection, 'user_id');
	}
	
	public function allByRole($role) {
		$query = Query::forge();
		$query->where('item_name', $role);
		return $this->all($query);
	}
	
	private function getId($id) {
		return is_object($id) ? $id->id : $id;
	}

}