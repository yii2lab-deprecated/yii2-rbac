<?php

namespace yii2lab\rbac\rbac;

use Yii;
use yii\rbac\PhpManager as YiiPhpManager;
use yii\web\NotFoundHttpException;

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
		return Yii::$app->account->assignment->allUserIdsByRole($roleName);
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
		try {
			return Yii::$app->account->assignment->allAssignments($userId, $roleName);
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}

	protected function saveItems()
	{
		parent::saveItems();
		Yii::$app->rbac->const->generateAll();
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
		$ids = Yii::$app->account->assignment->allUserIdsByRole($role);
		if(empty($ids)) {
			return;
		}
		foreach ($ids as $id) {
			Yii::$app->account->assignment->revokeRole($id, $role);
		}
	}
	
}
