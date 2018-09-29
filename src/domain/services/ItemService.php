<?php

namespace yii2lab\rbac\domain\services;

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\interfaces\services\ItemInterface;
use yii2lab\rbac\domain\repositories\disc\ItemRepository;

/**
 * Class ItemService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property ItemRepository $repository
 * @property-read array $defaultRoles
 */
class ItemService extends BaseService implements ItemInterface {
	
	public function getDefaultRoles()
	{
		return $this->repository->getDefaultRoles();
	}
	
	public function getItems($type)
	{
		return $this->repository->getItems($type);
	}
	
	public function addItem($item) {
		$result = $this->repository->addItem($item);
		$this->domain->const->generateAll();
		return $result;
	}
	
	public function updateItem($name, $item) {
		$this->repository->updateItem($name, $item);
		if ($name !== $item->name) {
			$this->domain->assignment->updateRoleName($name, $item->name);
		}
		$this->domain->const->generateAll();
	}
	
	/**
	 * @inheritdoc
	 */
	public function removeItem($item)
	{
		$result = $this->repository->removeItem($item);
		//if($result) {
			$this->domain->assignment->revokeAllByItemName($item->name);
		//}
		$this->domain->const->generateAll();
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getItem($name)
	{
		return $this->repository->getItem($name);
	}
	
	public function getRolesByUser($userId)
	{
		return $this->repository->getRolesByUser($userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildRoles($roleName)
	{
		return $this->repository->getChildRoles($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByRole($roleName)
	{
		return $this->repository->getPermissionsByRole($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByUser($userId)
	{
		return $this->repository->getPermissionsByUser($userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildren($name)
	{
		return $this->repository->getChildren($name);
	}
	
	
	/**
	 * Creates a new Role object.
	 * Note that the newly created role is not added to the RBAC system yet.
	 * You must fill in the needed data and call [[add()]] to add it to the system.
	 *
	 * @param string $name the role name
	 *
	 * @return Role the new Role object
	 */
	public function createRole($name) {
		$result = $this->repository->createRole($name);
		$this->domain->const->generateRoles();
		return $result;
	}
	
	/**
	 * Creates a new Permission object.
	 * Note that the newly created permission is not added to the RBAC system yet.
	 * You must fill in the needed data and call [[add()]] to add it to the system.
	 *
	 * @param string $name the permission name
	 *
	 * @return Permission the new Permission object
	 */
	public function createPermission($name) {
		$result = $this->repository->createPermission($name);
		$this->domain->const->generatePermissions();
		return $result;
	}
	
	/**
	 * Returns the named role.
	 *
	 * @param string $name the role name.
	 *
	 * @return null|Role the role corresponding to the specified name. Null is returned if no such role.
	 */
	public function getRole($name) {
		return $this->repository->getRole($name);
	}
	
	/**
	 * Returns all roles in the system.
	 *
	 * @return Role[] all roles in the system. The array is indexed by the role names.
	 */
	public function getRoles() {
		return $this->repository->getRoles();
	}
	
	/**
	 * Returns the named permission.
	 *
	 * @param string $name the permission name.
	 *
	 * @return null|Permission the permission corresponding to the specified name. Null is returned if no such permission.
	 */
	public function getPermission($name) {
		return $this->repository->getPermission($name);
	}
	
	/**
	 * Returns all permissions in the system.
	 *
	 * @return Permission[] all permissions in the system. The array is indexed by the permission names.
	 */
	public function getPermissions() {
		return $this->repository->getPermissions();
	}
	
	public function removeAllPermissions()
	{
		$result = $this->repository->removeAllPermissions();
		$this->domain->const->generatePermissions();
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRoles()
	{
		$result = $this->repository->removeAllRoles();
		$this->domain->const->generateRoles();
		return $result;
	}
	
	
	/**
	 * {@inheritdoc}
	 * @since 2.0.8
	 */
	public function canAddChild($parent, $child)
	{
		return $this->repository->canAddChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function addChild($parent, $child)
	{
		$result = $this->repository->addChild($parent, $child);
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChild($parent, $child)
	{
		$result = $this->repository->removeChild($parent, $child);
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChildren($parent)
	{
		$result = $this->repository->removeChildren($parent);
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function hasChild($parent, $child)
	{
		return $this->repository->hasChild($parent, $child);
	}
	
	/*
	 * Removes all authorization data, including roles, permissions, rules, and assignments.
	 */
	/*public function removeAll() {
		// TODO: Implement removeAll() method.
	}*/
	
	public function removeRuleFromItems($rule) {
		$result = $this->repository->removeRuleFromItems($rule);
		$this->domain->const->generateAll();
		return $result;
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
	public function checkAccessRecursive($user, $itemName, $params, $assignments)
	{
		//$items = $this->domain->item->getAllItems();
		//$children = $this->domain->item->getAllChildren();
		
		$items = $this->repository->getAllItems();
		$children = $this->repository->getAllChildren();
		
		if (!isset($items[$itemName])) {
			return false;
		}
		
		/* @var $item Item */
		$item = $items[$itemName];
		Yii::debug($item instanceof Role ? "Checking role: $itemName" : "Checking permission : $itemName", __METHOD__);
		
		if (!$this->domain->rule->executeRule($user, $item, $params)) {
			return false;
		}
		
		if (isset($assignments[$itemName]) || in_array($itemName, $this->domain->item->defaultRoles)) {
			return true;
		}
		foreach ($children as $parentName => $childrenItem) {
			if (isset($childrenItem[$itemName]) && $this->checkAccessRecursive($user, $parentName, $params, $assignments)) {
				return true;
			}
		}
		
		return false;
	}
	
}
