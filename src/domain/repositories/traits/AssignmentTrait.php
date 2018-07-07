<?php

namespace yii2lab\rbac\domain\repositories\traits;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2module\account\domain\v2\helpers\AssignmentHelper;

trait AssignmentTrait {
	
	public function tableName()
	{
		return 'user_assignment';
	}
	
	public function uniqueFields() {
		return ['user_id', 'item_name'];
	}
	
	public function revokeOneRole($userId, $role) {
		$this->model->deleteAll(['user_id' => $userId, 'item_name' => $role]);
	}
	
	public function revokeAllRoles($userId) {
		$this->model->deleteAll(['user_id' => $userId]);
	}
	
	public function oneAssign($userId, $itemName) {
		$query = Query::forge();
		$query->where('user_id', $userId);
		$query->where('item_name', $itemName);
		return $this->one($query);
	}
	
	public function allByUserId($userId) {
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
	
	public function allAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		$roles = $this->allRoleNamesByUserId($userId);
		return AssignmentHelper::forge($userId, $roles);
	}
	
	public function assignRole($userId, $role) {
		
		$userId = $this->getId($userId);
		$entity = Yii::$domain->account->login->oneById($userId);
		$assignEntity = $this->forgeEntity([
			'user_id' => $userId,
			'item_name' => $role,
		]);
		$this->insert($assignEntity);
		return AssignmentHelper::forge($userId, $role);
	}
	
	public function revokeRole($userId, $role) {
		$userId = $this->getId($userId);
		$entity = Yii::$domain->account->login->oneById($userId);
		$this->revokeOneRole($userId, $role);
	}
	
	public function isHasRole($userId, $role) {
		try {
			$entity = $this->oneAssign($userId, $role);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function allUserIdsByRole($role) {
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