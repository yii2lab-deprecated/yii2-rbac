<?php

namespace yii2lab\rbac\rbac;

use Yii;
use yii\rbac\PhpManager as YiiPhpManager;

class PhpManager extends YiiPhpManager
{
	
	public $assignmentFile = '@yii2lab/rbac/rbac/assignments.php';
	
	/**
	 * Saves assignments data into persistent storage.
	 */
	protected function saveAssignments()
	{
		return;
	}

	/**
	 * @inheritdoc
	 */
	public function getUserIdsByRole($roleName)
	{
		return Yii::$app->account->login->allUserIdsByRole($roleName);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignments($userId)
	{
		return Yii::$app->account->assignment->allAssignments($userId);
	}

	/**
	 * @inheritdoc
	 */
	public function assign($role, $userId)
	{
		return Yii::$app->account->assignment->assignRole($userId, $role);
	}

	/**
	 * @inheritdoc
	 */
	public function revoke($role, $userId)
	{
		Yii::$app->account->assignment->revokeRole($userId, $role);
	}

	/**
	 * @inheritdoc
	 */
	public function revokeAll($userId)
	{
		Yii::$app->account->assignment->revokeAllRoles($userId);
	}

	/**
	 * @inheritdoc
	 */
	public function getAssignment($roleName, $userId)
	{
		return Yii::$app->account->login->isHasRole($userId, $roleName);
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
			$this->removeItemRevoke($item->name);
			unset($this->items[$item->name]);
			$this->saveItems();
			return true;
		} else {
			return false;
		}
	}

	private function removeItemRevoke($role) {
		$ids = Yii::$app->account->login->allUserIdsByRole($role);
		if(empty($ids)) {
			return;
		}
		foreach ($ids as $id) {
			Yii::$app->account->assignment->revokeRole($id, $role);
		}
	}
	
}
