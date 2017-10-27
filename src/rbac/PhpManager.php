<?php

namespace yii2lab\rbac\rbac;

use Yii;
use yii\rbac\PhpManager as YiiPhpManager;
use yii\rbac\Assignment;

class PhpManager extends YiiPhpManager
{
	
	public $assignmentFile = '@yii2lab/rbac/rbac/assignments.php';
	
	private $user;
	
	public function init() {
		parent::init();
		$this->user = config('components.user.identityClass');
	}
	
	/**
	 * Saves assignments data into persistent storage.
	 */
	protected function saveAssignments()
	{
		$userClass = $this->user;
		foreach ($this->assignments as $userId => $assignments) {
			$user = $userClass::getOne($userId);
			foreach ($assignments as $name => $assignment) {
				$user->role = $assignment->roleName;
				$user->save();
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getUserIdsByRole($roleName)
	{
		$userClass = $this->user;
		$users = $userClass::find()
			->where(['role' => $roleName])
			->all();
		$result = [];
		foreach ($users as $user) {
			$result[] = (string)$user->id;
		}
		return $result;
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignments($userId)
	{
		$user = $this->getUser($userId);
		if(empty($user)) {
			return [];
		}
		return $this->forgeAssignments($userId, $user->roles);
	}

	/**
	 * @inheritdoc
	 */
	public function assign($role, $userId)
	{
		$user = $this->getUser($userId);
		if($user && $role->type == 1) {
			$user->role = $role->name;
			$user->save();
		}
		return $this->forgeAssignment($userId, $role->name);
	}

	/**
	 * @inheritdoc
	 */
	public function revoke($role, $userId)
	{
		$user = $this->getUser($userId, $role->name);
		if(empty($user)) {
			return false;
		}
		$user->role = '';
		$user->save();
	}

	/**
	 * @inheritdoc
	 */
	public function revokeAll($userId)
	{
		$user = $this->getUser($userId);
		if(empty($user)) {
			return false;
		}
		$user->role = '';
		$user->save();
	}

	/**
	 * @inheritdoc
	 */
	public function getAssignment($roleName, $userId)
	{
		$user = $this->getUser($userId, $roleName);
		if(empty($user)) {
			return null;
		}
		return $this->forgeAssignment($userId, $roleName);
	}

	protected function saveItems()
	{
		parent::saveItems();
		Yii::$app->rbac->const->generatePermissions();
		Yii::$app->rbac->const->generateRoles();
	}

	/**
	 * @inheritdoc
	 */
	public function removeItem($item)
	{
		if (isset($this->items[$item->name])) {
			foreach ($this->children as &$children) {
				unset($children[$item->name]);
			}
			$users = Yii::$app->account->login->allByRole($item->name);
			foreach ($users as $user) {
				$user->role = '';
				$user->save();
			}
			unset($this->items[$item->name]);
			$this->saveItems();
			return true;
		} else {
			return false;
		}
	}

	private function getUser($userId, $roleName = null) {
		$userClass = $this->user;
		if(Yii::$app->user->isGuest) {
			return null;
		}
		$identity = Yii::$app->user->identity;
		if($identity->id == $userId) {
			if(empty($roleName) || $identity->role == $roleName) {
				return $identity;
			}
		}
		if(!empty($userId)) {
			$where['id'] = $userId;
		}
		if(!empty($roleName)) {
			$where['role'] = $roleName;
		}
		$user = $userClass::find()
			->where($where)
			->one();
		return $user;
	}
	
	private function forgeAssignment($userId, $roleName) {
		$assignment = new Assignment([
			'userId' => $userId,
			'roleName' => $roleName,
			'createdAt' => 1486774821,
		]);
		return $assignment;
	}
	
	private function forgeAssignments($userId, $roleNames) {
		if(empty($roleNames)) {
			return [];
		}
		foreach($roleNames as $roleName) {
			$assignments[$roleName] = $this->forgeAssignment($userId, $roleName);
		}
		return $assignments;
	}
	
}
