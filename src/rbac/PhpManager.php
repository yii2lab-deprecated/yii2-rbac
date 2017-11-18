<?php

namespace yii2lab\rbac\rbac;

use Yii;
use yii\rbac\PhpManager as YiiPhpManager;
use yii2lab\helpers\yii\ArrayHelper;

class PhpManager extends YiiPhpManager
{
	
	public $assignmentFile = '@yii2lab/rbac/rbac/assignments.php';
	
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
		$users = Yii::$app->account->login->allByRole($roleName);
		return ArrayHelper::getColumn($users, 'id');
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignments($userId)
	{
		return Yii::$app->account->login->allAssignments($userId);
	}

	/**
	 * @inheritdoc
	 */
	public function assign($role, $userId)
	{
		return Yii::$app->account->login->assignRole($userId);
	}

	/**
	 * @inheritdoc
	 */
	public function revoke($role, $userId)
	{
		Yii::$app->account->login->revokeRole($userId, $role);
	}

	/**
	 * @inheritdoc
	 */
	public function revokeAll($userId)
	{
		Yii::$app->account->login->revokeAllRoles($userId);
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
			$users = Yii::$app->account->login->allByRole($item->name);
			foreach ($users as $user) {
				Yii::$app->account->login->revokeRole($user, $item->name);
			}
			unset($this->items[$item->name]);
			$this->saveItems();
			return true;
		} else {
			return false;
		}
	}

}
