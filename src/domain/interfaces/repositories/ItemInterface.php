<?php

namespace yii2lab\rbac\domain\interfaces\repositories;

use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;

interface ItemInterface {
	
	
	/**
	 * Returns the named auth item.
	 * @param string $name the auth item name.
	 * @return Item the auth item corresponding to the specified name. Null is returned if no such item.
	 */
	public function getItem($name);
	
	/**
	 * Returns the items of the specified type.
	 * @param int $type the auth item type (either [[Item::TYPE_ROLE]] or [[Item::TYPE_PERMISSION]]
	 * @return Item[] the auth items of the specified type.
	 */
	public function getItems($type);
	
	/**
	 * Adds an auth item to the RBAC system.
	 * @param Item $item the item to add
	 * @return bool whether the auth item is successfully added to the system
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function addItem($item);
	
	/**
	 * Removes an auth item from the RBAC system.
	 * @param Item $item the item to remove
	 * @return bool whether the role or permission is successfully removed
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function removeItem($item);
	
	/**
	 * Updates an auth item in the RBAC system.
	 * @param string $name the name of the item being updated
	 * @param Item $item the updated item
	 * @return bool whether the auth item is successfully updated
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function updateItem($name, $item);
	
	
	/**
	 * Creates a new Role object.
	 * Note that the newly created role is not added to the RBAC system yet.
	 * You must fill in the needed data and call [[add()]] to add it to the system.
	 * @param string $name the role name
	 * @return Role the new Role object
	 */
	public function createRole($name);
	
	/**
	 * Creates a new Permission object.
	 * Note that the newly created permission is not added to the RBAC system yet.
	 * You must fill in the needed data and call [[add()]] to add it to the system.
	 * @param string $name the permission name
	 * @return Permission the new Permission object
	 */
	public function createPermission($name);
	
	/**
	 * Returns the named role.
	 * @param string $name the role name.
	 * @return null|Role the role corresponding to the specified name. Null is returned if no such role.
	 */
	public function getRole($name);
	
	/**
	 * Returns all roles in the system.
	 * @return Role[] all roles in the system. The array is indexed by the role names.
	 */
	public function getRoles();
	
	/**
	 * Returns the roles that are assigned to the user via [[assign()]].
	 * Note that child roles that are not assigned directly to the user will not be returned.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Role[] all roles directly assigned to the user. The array is indexed by the role names.
	 */
	public function getRolesByUser($userId);
	
	/**
	 * Returns child roles of the role specified. Depth isn't limited.
	 * @param string $roleName name of the role to file child roles for
	 * @return Role[] Child roles. The array is indexed by the role names.
	 * First element is an instance of the parent Role itself.
	 * @throws \yii\base\InvalidParamException if Role was not found that are getting by $roleName
	 * @since 2.0.10
	 */
	public function getChildRoles($roleName);
	
	/**
	 * Returns the named permission.
	 * @param string $name the permission name.
	 * @return null|Permission the permission corresponding to the specified name. Null is returned if no such permission.
	 */
	public function getPermission($name);
	
	/**
	 * Returns all permissions in the system.
	 * @return Permission[] all permissions in the system. The array is indexed by the permission names.
	 */
	public function getPermissions();
	
	/**
	 * Returns all permissions that the specified role represents.
	 * @param string $roleName the role name
	 * @return Permission[] all permissions that the role represents. The array is indexed by the permission names.
	 */
	public function getPermissionsByRole($roleName);
	
	/**
	 * Returns all permissions that the user has.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Permission[] all permissions that the user has. The array is indexed by the permission names.
	 */
	public function getPermissionsByUser($userId);
	
	/**
	 * Checks the possibility of adding a child to parent.
	 * @param Item $parent the parent item
	 * @param Item $child the child item to be added to the hierarchy
	 * @return bool possibility of adding
	 *
	 * @since 2.0.8
	 */
	public function canAddChild($parent, $child);
	
	/**
	 * Adds an item as a child of another item.
	 * @param Item $parent
	 * @param Item $child
	 * @return bool whether the child successfully added
	 * @throws \yii\base\Exception if the parent-child relationship already exists or if a loop has been detected.
	 */
	public function addChild($parent, $child);
	
	/**
	 * Removes a child from its parent.
	 * Note, the child item is not deleted. Only the parent-child relationship is removed.
	 * @param Item $parent
	 * @param Item $child
	 * @return bool whether the removal is successful
	 */
	public function removeChild($parent, $child);
	
	/**
	 * Removed all children form their parent.
	 * Note, the children items are not deleted. Only the parent-child relationships are removed.
	 * @param Item $parent
	 * @return bool whether the removal is successful
	 */
	public function removeChildren($parent);
	
	/**
	 * Returns a value indicating whether the child already exists for the parent.
	 * @param Item $parent
	 * @param Item $child
	 * @return bool whether `$child` is already a child of `$parent`
	 */
	public function hasChild($parent, $child);
	
	/**
	 * Returns the child permissions and/or roles.
	 * @param string $name the parent name
	 * @return Item[] the child permissions and/or roles
	 */
	public function getChildren($name);
	
	/**
	 * Removes all authorization data, including roles, permissions, rules, and assignments.
	 */
	//public function removeAll();
	
	/**
	 * Removes all permissions.
	 * All parent child relations will be adjusted accordingly.
	 */
	public function removeAllPermissions();
	
	/**
	 * Removes all roles.
	 * All parent child relations will be adjusted accordingly.
	 */
	public function removeAllRoles();
	
	public function getAllChildren();
	
	public function getAllItems();
	
	
	public function removeRuleFromItems($rule);
	
}