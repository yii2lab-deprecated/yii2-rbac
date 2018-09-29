<?php

namespace yii2lab\rbac\domain\repositories\base;

use yii2lab\domain\repositories\BaseRepository;
use yii\rbac\Item;
use yii2lab\rbac\domain\helpers\ItemHelper;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii2lab\rbac\domain\repositories\traits\ItemTrait;

/**
 * @property \yii2lab\rbac\domain\Domain $domain
 */
abstract class BaseItemRepository extends BaseRepository {
	
	use ItemTrait;
	
	/**
	 * Loads authorization data from persistent storage.
	 */
	abstract protected function load();
	
	/**
	 * Saves items data into persistent storage.
	 */
	abstract protected function saveItems();
	
	/**
	 * @var string the path of the PHP script that contains the authorization items.
	 * This can be either a file path or a [path alias](guide:concept-aliases) to the file.
	 * Make sure this file is writable by the Web server process if the authorization needs to be changed online.
	 */
	public $itemFile = '@app/rbac/items.php';
	
	/**
	 * @var Item[]
	 */
	protected $items = []; // itemName => item
	/**
	 * @var array
	 */
	protected $children = []; // itemName, childName => child
	
	/**
	 * Initializes the application component.
	 * This method overrides parent implementation by loading the authorization data
	 * from PHP script.
	 */
	public function init()
	{
		parent::init();
		//$this->itemFile = Yii::getAlias($this->itemFile);
		$this->load();
	}
	
	/**
	 * {@inheritdoc}
	 */
	/*public function removeAll()
	{
		$this->children = [];
		$this->items = [];
		$this->save();
	}*/
	
	public function getAllChildren()
	{
		return $this->children;
	}
	
	public function getAllItems()
	{
		return $this->items;
	}
	
	/**
	 * {@inheritdoc}
	 * @since 2.0.8
	 */
	public function canAddChild($parent, $child)
	{
		return !$this->detectLoop($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function addChild($parent, $child)
	{
		if (!isset($this->items[$parent->name], $this->items[$child->name])) {
			throw new InvalidArgumentException("Either '{$parent->name}' or '{$child->name}' does not exist.");
		}
		
		if ($parent->name === $child->name) {
			throw new InvalidArgumentException("Cannot add '{$parent->name} ' as a child of itself.");
		}
		if ($parent instanceof Permission && $child instanceof Role) {
			throw new InvalidArgumentException('Cannot add a role as a child of a permission.');
		}
		
		if ($this->detectLoop($parent, $child)) {
			throw new InvalidCallException("Cannot add '{$child->name}' as a child of '{$parent->name}'. A loop has been detected.");
		}
		if (isset($this->children[$parent->name][$child->name])) {
			throw new InvalidCallException("The item '{$parent->name}' already has a child '{$child->name}'.");
		}
		$this->children[$parent->name][$child->name] = $this->items[$child->name];
		$this->saveItems();
		
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChild($parent, $child)
	{
		if (isset($this->children[$parent->name][$child->name])) {
			unset($this->children[$parent->name][$child->name]);
			$this->saveItems();
			return true;
		}
		
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChildren($parent)
	{
		if (isset($this->children[$parent->name])) {
			unset($this->children[$parent->name]);
			$this->saveItems();
			return true;
		}
		
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function hasChild($parent, $child)
	{
		return isset($this->children[$parent->name][$child->name]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getItems($type)
	{
		return ItemHelper::filterByType($this->items, $type);
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
	
	/**
	 * {@inheritdoc}
	 */
	public function getItem($name)
	{
		return isset($this->items[$name]) ? $this->items[$name] : null;
	}
	
	
	
	/**
	 * {@inheritdoc}
	 * The roles returned by this method include the roles assigned via [[$defaultRoles]].
	 */
	public function getRolesByUser($userId)
	{
		$roles = $this->getDefaultRoleInstances();
		foreach ($this->domain->assignment->getAssignments($userId) as $name => $assignment) {
			$role = $this->items[$assignment->roleName];
			if ($role->type === Item::TYPE_ROLE) {
				$roles[$name] = $role;
			}
		}
		
		return $roles;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildRoles($roleName)
	{
		$role = $this->getRole($roleName);
		
		if ($role === null) {
			throw new InvalidArgumentException("Role \"$roleName\" not found.");
		}
		
		$result = [];
		$this->getChildrenRecursive($roleName, $result);
		
		$roles = [$roleName => $role];
		
		$roles += array_filter($this->getRoles(), function (Role $roleItem) use ($result) {
			return array_key_exists($roleItem->name, $result);
		});
		
		return $roles;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByRole($roleName)
	{
		$result = [];
		$this->getChildrenRecursive($roleName, $result);
		if (empty($result)) {
			return [];
		}
		$permissions = [];
		foreach (array_keys($result) as $itemName) {
			if (isset($this->items[$itemName]) && $this->items[$itemName] instanceof Permission) {
				$permissions[$itemName] = $this->items[$itemName];
			}
		}
		
		return $permissions;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByUser($userId)
	{
		$directPermission = $this->getDirectPermissionsByUser($userId);
		$inheritedPermission = $this->getInheritedPermissionsByUser($userId);
		
		return array_merge($directPermission, $inheritedPermission);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildren($name)
	{
		return isset($this->children[$name]) ? $this->children[$name] : [];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllPermissions()
	{
		return $this->removeAllItems(Item::TYPE_PERMISSION);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRoles()
	{
		return $this->removeAllItems(Item::TYPE_ROLE);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function addItem($item)
	{
		$time = time();
		if ($item->createdAt === null) {
			$item->createdAt = $time;
		}
		if ($item->updatedAt === null) {
			$item->updatedAt = $time;
		}
		
		$this->items[$item->name] = $item;
		
		$this->saveItems();
		
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function createRole($name)
	{
		$role = new Role();
		$role->name = $name;
		return $role;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function createPermission($name)
	{
		$permission = new Permission();
		$permission->name = $name;
		return $permission;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRole($name)
	{
		$item = $this->getItem($name);
		return $item instanceof Item && $item->type == Item::TYPE_ROLE ? $item : null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermission($name)
	{
		$item = $this->getItem($name);
		return $item instanceof Item && $item->type == Item::TYPE_PERMISSION ? $item : null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRoles()
	{
		return $this->getItems(Item::TYPE_ROLE);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissions()
	{
		return $this->getItems(Item::TYPE_PERMISSION);
	}
	
	public function removeRuleFromItems($rule) {
		$items = $this->getAllItems();
		foreach ($items as $item) {
			if ($item->ruleName === $rule->name) {
				$item->ruleName = null;
			}
			$this->updateItem($item->name, $item);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function updateItem($name, $item)
	{
		$this->items = ItemHelper::updateItems($name, $item, $this->items);
		$this->children = ItemHelper::updateChildren($name, $item, $this->children);
		
		$this->saveItems();
		return true;
	}
	
	/**
	 * Recursively finds all children and grand children of the specified item.
	 *
	 * @param string $name the name of the item whose children are to be looked for.
	 * @param array $result the children and grand children (in array keys)
	 */
	protected function getChildrenRecursive($name, &$result)
	{
		ItemHelper::getChildrenRecursive($name, $result, $this->children);
	}
	
	/**
	 * Returns all permissions that are directly assigned to user.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Permission[] all direct permissions that the user has. The array is indexed by the permission names.
	 * @since 2.0.7
	 */
	protected function getDirectPermissionsByUser($userId)
	{
		$assignments = $this->domain->assignment->getAssignments($userId);
		return ItemHelper::allPermissionsByAssignments($this->items, $assignments);
	}
	
	/**
	 * Returns all permissions that the user inherits from the roles assigned to him.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Permission[] all inherited permissions that the user has. The array is indexed by the permission names.
	 * @since 2.0.7
	 */
	protected function getInheritedPermissionsByUser($userId)
	{
		$assignments = $this->domain->assignment->getAssignments($userId);
		$result = [];
		foreach (array_keys($assignments) as $roleName) {
			$this->getChildrenRecursive($roleName, $result);
		}
		
		if (empty($result)) {
			return [];
		}
		
		$permissions = [];
		foreach (array_keys($result) as $itemName) {
			if (isset($this->items[$itemName]) && $this->items[$itemName] instanceof Permission) {
				$permissions[$itemName] = $this->items[$itemName];
			}
		}
		
		return $permissions;
	}
	
	/**
	 * Saves authorization data into persistent storage.
	 */
	protected function save()
	{
		$this->saveItems();
		//$this->saveAssignments();
		//$this->saveRules();
	}
	
	private function removeItemRevoke($role) {
		$ids = $this->domain->assignment->getUserIdsByRole($role);
		if(empty($ids)) {
			return;
		}
		foreach ($ids as $id) {
			$this->domain->assignment->revoke($role, $id);
		}
	}
	
	/**
	 * Removes all auth items of the specified type.
	 * @param int $type the auth item type (either Item::TYPE_PERMISSION or Item::TYPE_ROLE)
	 */
	protected function removeAllItems($type)
	{
		$names = ItemHelper::getItemsNameByType($this->items, $type);
		if (empty($names)) {
			return;
		}
		
		foreach ($names as $n => $hardTrue) {
			$this->domain->assignment->revokeAllByItemName($n);
		}
		
		/*foreach ($this->domain->assignment->all() as $i => $assignments) {
			foreach ($assignments as $n => $assignment) {
				if (isset($names[$assignment->roleName])) {
					unset($this->assignments[$i][$n]);
				}
			}
		}*/
		
		$this->children = ItemHelper::removeByNames($this->children, $names);
		
		$this->saveItems();
	}
	
}
