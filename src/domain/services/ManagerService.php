<?php

namespace yii2lab\rbac\domain\services;

use Yii;
use yii\rbac\Assignment;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\services\base\BaseService;
use yii2lab\extension\yii\helpers\ArrayHelper;
use yii2lab\rbac\domain\enums\RbacPermissionEnum;
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
	
	public function can($permissions, $param = null, $allowCaching = true) {
		if(empty($permissions)) {
			return;
		}
		$permissions = ArrayHelper::toArray($permissions);
		foreach($permissions as $rule) {
			$this->canItem($rule, $param, $allowCaching);
		}
	}
	
	public function isAllow($permissions, $params = [], $userId = null) {
		if(empty($permissions)) {
			return false;
		}
		if(empty($userId)) {
			try {
                if(!\Yii::$app->user->isGuest) {
                    $identity = \App::$domain->account->auth->identity;
                    if(is_object($identity)) {
                        $userId = $identity->id;
                    }
                }
			} catch(ForbiddenHttpException $e) {}
		}
		$permissions = ArrayHelper::toArray($permissions);
		foreach($permissions as $permission) {
			$isAllow = $this->isAllowItem($permission, $params, $userId);
			if($isAllow) {
				return true;
			}
		}
		return false;
	}
	
	public function checkAccess($userId, $permissionName, $params = [])
	{
		$assignments = $this->domain->assignment->getAssignments($userId);
		
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
	
	private function canItem($rule, $param = null, $allowCaching = true) {
		if($this->repository->isGuestOnlyAllowed($rule)) {
			throw new ForbiddenHttpException();
		}
		if($this->repository->isAuthOnlyAllowed($rule)) {
			\App::$domain->account->auth->breakSession();
		}
		if(!Yii::$app->user->can($rule, $param, $allowCaching)) {
			if(Yii::$app->user->isGuest) {
				\App::$domain->account->auth->breakSession();
			}
			throw new ForbiddenHttpException();
		}
	}
	
	private function isAllowItem($permission, $params = [], $userId = null) {
	    if(empty($userId)) {
	        return RbacPermissionEnum::GUEST == $permission;
        }

		try {
			$isAllow = $this->checkAccess($userId, $permission, $params);
		} catch(ForbiddenHttpException $e) {
			$isAllow = false;
		} catch(UnauthorizedHttpException $e) {
			$isAllow = false;
		}
		return $isAllow;
	}
	
}
