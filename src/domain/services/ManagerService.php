<?php

namespace yii2lab\rbac\domain\services;

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii\web\ForbiddenHttpException;
use yii2lab\domain\services\BaseService;
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
	
	/**
	 * @var \yii2lab\rbac\domain\rbac\custom\PhpManager
	 */
	protected $instance;
	
	/**
	 * @var Item[]
	 */
	protected $items = []; // itemName => item
	/**
	 * @var array
	 */
	protected $children = []; // itemName, childName => child
	/**
	 * @var array
	 */
	//protected $assignments = []; // userId, itemName => assignment
	/**
	 * @var Rule[]
	 */
	protected $rules = []; // ruleName => rule
	
	/**
	 * @var array a list of role names that are assigned to every user automatically without calling [[assign()]].
	 * Note that these roles are applied to users, regardless of their state of authentication.
	 */
	protected $defaultRoles = [];
	
	public function checkAccess($userId, $permissionName, $params = [])
	{
		$assignments = Yii::$domain->rbac->assignment->allAssignments($userId);
		
		if ($this->hasNoAssignments($assignments)) {
			return false;
		}
		
		return $this->checkAccessRecursive($userId, $permissionName, $params, $assignments);
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
		return empty($assignments) && empty($this->defaultRoles);
	}
	
	/**
	 * Performs access check for the specified user.
	 * This method is internally called by [[checkAccess()]].
	 *
	 * @param string|int $user the user ID. This should can be either an integer or a string representing
	 * the unique identifier of a user. See [[\yii\web\User::id]].
	 * @param string $itemName the name of the operation that need access check
	 * @param array $params name-value pairs that would be passed to rules associated
	 * with the tasks and roles assigned to the user. A param with name 'user' is added to this array,
	 * which holds the value of `$userId`.
	 * @param Assignment[] $assignments the assignments to the specified user
	 * @return bool whether the operations can be performed by the user.
	 * @throws InvalidConfigException
	 */
	protected function checkAccessRecursive($user, $itemName, $params, $assignments)
	{
		if (!isset($this->items[$itemName])) {
			return false;
		}
		
		/* @var $item Item */
		$item = $this->items[$itemName];
		Yii::debug($item instanceof Role ? "Checking role: $itemName" : "Checking permission : $itemName", __METHOD__);
		
		if (!$this->executeRule($user, $item, $params)) {
			return false;
		}
		
		if (isset($assignments[$itemName]) || in_array($itemName, $this->defaultRoles)) {
			return true;
		}
		foreach ($this->children as $parentName => $children) {
			if (isset($children[$itemName]) && $this->checkAccessRecursive($user, $parentName, $params, $assignments)) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Executes the rule associated with the specified auth item.
	 *
	 * If the item does not specify a rule, this method will return true. Otherwise, it will
	 * return the value of [[Rule::execute()]].
	 *
	 * @param string|int $user the user ID. This should be either an integer or a string representing
	 * the unique identifier of a user. See [[\yii\web\User::id]].
	 * @param Item $item the auth item that needs to execute its rule
	 * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]] and will be passed to the rule
	 * @return bool the return value of [[Rule::execute()]]. If the auth item does not specify a rule, true will be returned.
	 * @throws InvalidConfigException if the auth item has an invalid rule.
	 */
	protected function executeRule($user, $item, $params)
	{
		if ($item->ruleName === null) {
			return true;
		}
		$rule = $this->domain->rule->getRule($item->ruleName);
		if ($rule instanceof Rule) {
			return $rule->execute($user, $item, $params);
		}
		
		throw new InvalidConfigException("Rule not found: {$item->ruleName}");
	}
	
	public function init()
	{
		parent::init();
		/*$this->instance = Yii::createObject([
			'class' => 'yii2lab\rbac\domain\rbac\custom\PhpManager',
			'itemFile' => '@common/data/rbac/items.php',
			'ruleFile' => '@common/data/rbac/rules.php',
			'defaultRoles' => ['rGuest'],
		]);*/
		$this->items = $this->domain->item->getAllItems();
		$this->children = $this->domain->item->getAllChildren();
		// todo: hardcode
		$this->defaultRoles = ['rGuest'];
	}
	
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

}
