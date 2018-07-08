<?php

namespace yii2lab\rbac\domain\services;

use Yii;
use yii\rbac\Assignment;
use yii\web\ForbiddenHttpException;
use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\interfaces\services\ManagerInterface;

/**
 * Class RbacService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property \yii2lab\rbac\domain\interfaces\repositories\ManagerInterface $repository
 */
class ManagerService extends BaseService implements ManagerInterface {
	
	public function can($rule, $param = null, $allowCaching = true) {
		if($this->repository->isGuestOnlyAllowed($rule)) {
			throw new ForbiddenHttpException();
		}
		if($this->repository->isAuthOnlyAllowed($rule)) {
			Yii::$domain->account->auth->breakSession();
		}
		if(!Yii::$app->user->can($rule, $param, $allowCaching)) {
			if(Yii::$app->user->isGuest) {
				Yii::$domain->account->auth->breakSession();
			}
			throw new ForbiddenHttpException();
		}
	}
	
	
	public function checkAccess($userId, $permissionName, $params = [])
	{
		$assignments = Yii::$domain->rbac->assignment->getAssignments($userId);
		
		if ($this->hasNoAssignments($assignments)) {
			return false;
		}
		
		return $this->domain->item->checkAccessRecursive($userId, $permissionName, $params, $assignments);
	}
	
	/**
	 * Checks whether array of $assignments is empty and [[defaultRoles]] property is empty as well.
	 *
	 * @param Assignment[] $assignments array of user's assignments
	 * @return bool whether array of $assignments is empty and [[defaultRoles]] property is empty as well
	 * @since 2.0.11
	 */
	protected function hasNoAssignments(array $assignments)
	{
		return empty($assignments) && empty($this->domain->item->defaultRoles);
	}
	
}
