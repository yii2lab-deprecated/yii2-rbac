<?php

namespace yii2lab\rbac\domain\services;

use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\repositories\disc\ItemRepository;

/**
 * Class ItemService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property ItemRepository $repository
 */
class ItemService extends BaseService {
	
	public function getAllChildren()
	{
		return $this->repository->getAllChildren();
	}
	
	public function getAllItems()
	{
		return $this->repository->getAllItems();
	}
	
	public function getItems($type)
	{
		return $this->repository->getItems($type);
	}
	
	public function addItem($item) {
		return $this->repository->addItem($item);
	}
	
	public function updateItem($name, $item) {
		return $this->repository->updateItem($name, $item);
	}
	
	/**
	 * @inheritdoc
	 */
	public function removeItem($item)
	{
		return $this->repository->removeItem($item);
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
		return $this->repository->createRole($name);
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
		return $this->repository->createPermission($name);
	}
	
	/**
	 * Adds a role, permission or rule to the RBAC system.
	 *
	 * @param Role|Permission|Rule $object
	 *
	 * @return bool whether the role, permission or rule is successfully added to the system
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function add($object) {
		return $this->repository->add($object);
	}
	
	/**
	 * Removes a role, permission or rule from the RBAC system.
	 *
	 * @param Role|Permission|Rule $object
	 *
	 * @return bool whether the role, permission or rule is successfully removed
	 */
	public function remove($object) {
		return $this->repository->remove($object);
	}
	
	/**
	 * Updates the specified role, permission or rule in the system.
	 *
	 * @param string               $name the old name of the role, permission or rule
	 * @param Role|Permission|Rule $object
	 *
	 * @return bool whether the update is successful
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function update($name, $object) {
		return $this->repository->update($name, $object);
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
		return $this->repository->removeAllPermissions();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRoles()
	{
		return $this->repository->removeAllRoles();
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
		return $this->repository->addChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChild($parent, $child)
	{
		return $this->repository->removeChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChildren($parent)
	{
		return $this->repository->removeChildren($parent);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function hasChild($parent, $child)
	{
		return $this->repository->hasChild($parent, $child);
	}
	
}
