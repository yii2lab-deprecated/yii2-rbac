<?php

namespace yii2lab\rbac\domain\repositories\base;

use yii2lab\domain\repositories\BaseRepository;
use Yii;
use yii\rbac\Item;
//use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\helpers\VarDumper;
use yii\base\InvalidValueException;

class BaseItemRepository extends BaseRepository/* implements ManagerInterface*/ {
	
	/**
	 * @var string the path of the PHP script that contains the authorization items.
	 * This can be either a file path or a [path alias](guide:concept-aliases) to the file.
	 * Make sure this file is writable by the Web server process if the authorization needs to be changed online.
	 * @see loadFromFile()
	 * @see saveToFile()
	 */
	public $itemFile = '@app/rbac/items.php';
	
	/**
	 * @var string the path of the PHP script that contains the authorization rules.
	 * This can be either a file path or a [path alias](guide:concept-aliases) to the file.
	 * Make sure this file is writable by the Web server process if the authorization needs to be changed online.
	 * @see loadFromFile()
	 * @see saveToFile()
	 */
	public $ruleFile = '@app/rbac/rules.php';
	
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
	protected $assignments = []; // userId, itemName => assignment
	/**
	 * @var Rule[]
	 */
	protected $rules = []; // ruleName => rule
	
	
	/**
	 * Initializes the application component.
	 * This method overrides parent implementation by loading the authorization data
	 * from PHP script.
	 */
	public function init()
	{
		parent::init();
		$this->itemFile = Yii::getAlias($this->itemFile);
		$this->ruleFile = Yii::getAlias($this->ruleFile);
		$this->load();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function checkAccess($userId, $permissionName, $params = [])
	{
	
	}
	
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
	 * Checks whether there is a loop in the authorization item hierarchy.
	 *
	 * @param Item $parent parent item
	 * @param Item $child the child item that is to be added to the hierarchy
	 * @return bool whether a loop exists
	 */
	protected function detectLoop($parent, $child)
	{
		if ($child->name === $parent->name) {
			return true;
		}
		if (!isset($this->children[$child->name], $this->items[$parent->name])) {
			return false;
		}
		foreach ($this->children[$child->name] as $grandchild) {
			/* @var $grandchild Item */
			if ($this->detectLoop($parent, $grandchild)) {
				return true;
			}
		}
		
		return false;
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
		$items = [];
		
		foreach ($this->items as $name => $item) {
			/* @var $item Item */
			if ($item->type == $type) {
				$items[$name] = $item;
			}
		}
		
		return $items;
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
	 */
	public function updateRule($name, $rule)
	{
		if ($rule->name !== $name) {
			unset($this->rules[$name]);
		}
		$this->rules[$rule->name] = $rule;
		$this->saveRules();
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRule($name)
	{
		return isset($this->rules[$name]) ? $this->rules[$name] : null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRules()
	{
		return $this->rules;
	}
	
	/**
	 * {@inheritdoc}
	 * The roles returned by this method include the roles assigned via [[$defaultRoles]].
	 */
	public function getRolesByUser($userId)
	{
		$roles = $this->getDefaultRoleInstances();
		foreach (Yii::$domain->rbac->assignment->allAssignments($userId) as $name => $assignment) {
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
	 * Recursively finds all children and grand children of the specified item.
	 *
	 * @param string $name the name of the item whose children are to be looked for.
	 * @param array $result the children and grand children (in array keys)
	 */
	protected function getChildrenRecursive($name, &$result)
	{
		if (isset($this->children[$name])) {
			foreach ($this->children[$name] as $child) {
				$result[$child->name] = true;
				$this->getChildrenRecursive($child->name, $result);
			}
		}
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
	 * Returns all permissions that are directly assigned to user.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Permission[] all direct permissions that the user has. The array is indexed by the permission names.
	 * @since 2.0.7
	 */
	protected function getDirectPermissionsByUser($userId)
	{
		$permissions = [];
		foreach (Yii::$domain->rbac->assignment->allAssignments($userId) as $name => $assignment) {
			$permission = $this->items[$assignment->roleName];
			if ($permission->type === Item::TYPE_PERMISSION) {
				$permissions[$name] = $permission;
			}
		}
		
		return $permissions;
	}
	
	/**
	 * Returns all permissions that the user inherits from the roles assigned to him.
	 * @param string|int $userId the user ID (see [[\yii\web\User::id]])
	 * @return Permission[] all inherited permissions that the user has. The array is indexed by the permission names.
	 * @since 2.0.7
	 */
	protected function getInheritedPermissionsByUser($userId)
	{
		$assignments = Yii::$domain->rbac->assignment->allAssignments($userId);
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
	 * {@inheritdoc}
	 */
	public function getChildren($name)
	{
		return isset($this->children[$name]) ? $this->children[$name] : [];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAll()
	{
		$this->children = [];
		$this->items = [];
		//$this->assignments = [];
		$this->rules = [];
		$this->save();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllPermissions()
	{
		$this->removeAllItems(Item::TYPE_PERMISSION);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRoles()
	{
		$this->removeAllItems(Item::TYPE_ROLE);
	}
	
	/**
	 * Removes all auth items of the specified type.
	 * @param int $type the auth item type (either Item::TYPE_PERMISSION or Item::TYPE_ROLE)
	 */
	protected function removeAllItems($type)
	{
		$names = [];
		foreach ($this->items as $name => $item) {
			if ($item->type == $type) {
				unset($this->items[$name]);
				$names[$name] = true;
			}
		}
		if (empty($names)) {
			return;
		}
		
		foreach ($this->assignments as $i => $assignments) {
			foreach ($assignments as $n => $assignment) {
				if (isset($names[$assignment->roleName])) {
					unset($this->assignments[$i][$n]);
				}
			}
		}
		foreach ($this->children as $name => $children) {
			if (isset($names[$name])) {
				unset($this->children[$name]);
			} else {
				foreach ($children as $childName => $item) {
					if (isset($names[$childName])) {
						unset($children[$childName]);
					}
				}
				$this->children[$name] = $children;
			}
		}
		
		$this->saveItems();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRules()
	{
		foreach ($this->items as $item) {
			$item->ruleName = null;
		}
		$this->rules = [];
		$this->saveRules();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllAssignments()
	{
		//$this->assignments = [];
		$this->saveAssignments();
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function removeRule($rule)
	{
		if (isset($this->rules[$rule->name])) {
			unset($this->rules[$rule->name]);
			foreach ($this->items as $item) {
				if ($item->ruleName === $rule->name) {
					$item->ruleName = null;
				}
			}
			$this->saveRules();
			return true;
		}
		
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function addRule($rule)
	{
		$this->rules[$rule->name] = $rule;
		$this->saveRules();
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function updateItem($name, $item)
	{
		if ($name !== $item->name) {
			if (isset($this->items[$item->name])) {
				throw new InvalidArgumentException("Unable to change the item name. The name '{$item->name}' is already used by another item.");
			}
			
			// Remove old item in case of renaming
			unset($this->items[$name]);
			
			if (isset($this->children[$name])) {
				$this->children[$item->name] = $this->children[$name];
				unset($this->children[$name]);
			}
			foreach ($this->children as &$children) {
				if (isset($children[$name])) {
					$children[$item->name] = $children[$name];
					unset($children[$name]);
				}
			}
			foreach ($this->assignments as &$assignments) {
				if (isset($assignments[$name])) {
					$assignments[$item->name] = $assignments[$name];
					$assignments[$item->name]->roleName = $item->name;
					unset($assignments[$name]);
				}
			}
			$this->saveAssignments();
		}
		
		$this->items[$item->name] = $item;
		
		$this->saveItems();
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function addItem($item)
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
	 * Loads authorization data from persistent storage.
	 */
	protected function load()
	{
		$this->children = [];
		$this->rules = [];
		//$this->assignments = [];
		$this->items = [];
		
		$items = $this->loadFromFile($this->itemFile);
		$itemsMtime = @filemtime($this->itemFile);
		//$assignments = $this->loadFromFile($this->assignmentFile);
		//$assignmentsMtime = @filemtime($this->assignmentFile);
		$rules = $this->loadFromFile($this->ruleFile);
		
		foreach ($items as $name => $item) {
			$class = $item['type'] == Item::TYPE_PERMISSION ? Permission::class : Role::class;
			
			$this->items[$name] = new $class([
				'name' => $name,
				'description' => isset($item['description']) ? $item['description'] : null,
				'ruleName' => isset($item['ruleName']) ? $item['ruleName'] : null,
				'data' => isset($item['data']) ? $item['data'] : null,
				'createdAt' => $itemsMtime,
				'updatedAt' => $itemsMtime,
			]);
		}
		
		foreach ($items as $name => $item) {
			if (isset($item['children'])) {
				foreach ($item['children'] as $childName) {
					if (isset($this->items[$childName])) {
						$this->children[$name][$childName] = $this->items[$childName];
					}
				}
			}
		}
		
		/*foreach ($assignments as $userId => $roles) {
			foreach ($roles as $role) {
				$this->assignments[$userId][$role] = new Assignment([
					'userId' => $userId,
					'roleName' => $role,
					'createdAt' => $assignmentsMtime,
				]);
			}
		}*/
		
		foreach ($rules as $name => $ruleData) {
			$this->rules[$name] = unserialize($ruleData);
		}
	}
	
	/**
	 * Saves authorization data into persistent storage.
	 */
	protected function save()
	{
		$this->saveItems();
		$this->saveAssignments();
		$this->saveRules();
	}
	
	/**
	 * Loads the authorization data from a PHP script file.
	 *
	 * @param string $file the file path.
	 * @return array the authorization data
	 * @see saveToFile()
	 */
	protected function loadFromFile($file)
	{
		if (is_file($file)) {
			return @include($file);
		}
		
		return [];
	}
	
	/**
	 * Saves the authorization data to a PHP script file.
	 *
	 * @param array $data the authorization data
	 * @param string $file the file path.
	 * @see loadFromFile()
	 */
	protected function saveToFile($data, $file)
	{
		file_put_contents($file, "<?php\nreturn " . VarDumper::export($data) . ";\n", LOCK_EX);
		$this->invalidateScriptCache($file);
	}
	
	/**
	 * Invalidates precompiled script cache (such as OPCache or APC) for the given file.
	 * @param string $file the file path.
	 * @since 2.0.9
	 */
	protected function invalidateScriptCache($file)
	{
		if (function_exists('opcache_invalidate')) {
			opcache_invalidate($file, true);
		}
		if (function_exists('apc_delete_file')) {
			@apc_delete_file($file);
		}
	}
	
	/**
	 * Saves items data into persistent storage.
	 */
	protected function saveItems()
	{
		$items = [];
		foreach ($this->items as $name => $item) {
			/* @var $item Item */
			$items[$name] = array_filter(
				[
					'type' => $item->type,
					'description' => $item->description,
					'ruleName' => $item->ruleName,
					'data' => $item->data,
				]
			);
			if (isset($this->children[$name])) {
				foreach ($this->children[$name] as $child) {
					/* @var $child Item */
					$items[$name]['children'][] = $child->name;
				}
			}
		}
		$this->saveToFile($items, $this->itemFile);
		
		Yii::$domain->rbac->const->generateAll();
	}
	
	/**
	 * Saves assignments data into persistent storage.
	 */
	protected function saveAssignments()
	{
		return;
	}
	
	/**
	 * Saves rules data into persistent storage.
	 */
	protected function saveRules()
	{
		$rules = [];
		foreach ($this->rules as $name => $rule) {
			$rules[$name] = serialize($rule);
		}
		$this->saveToFile($rules, $this->ruleFile);
	}
	
	private function removeItemRevoke($role) {
		$ids = Yii::$domain->rbac->assignment->allUserIdsByRole($role);
		if(empty($ids)) {
			return;
		}
		foreach ($ids as $id) {
			Yii::$domain->rbac->assignment->revokeRole($id, $role);
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	// ====================== BaseManager =================================
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * @var array a list of role names that are assigned to every user automatically without calling [[assign()]].
	 * Note that these roles are applied to users, regardless of their state of authentication.
	 */
	protected $defaultRoles = [];
	
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
	public function add($object)
	{
		if ($object instanceof Item) {
			if ($object->ruleName && $this->getRule($object->ruleName) === null) {
				$rule = \Yii::createObject($object->ruleName);
				$rule->name = $object->ruleName;
				$this->addRule($rule);
			}
			
			return $this->addItem($object);
		} elseif ($object instanceof Rule) {
			return $this->addRule($object);
		}
		
		throw new InvalidArgumentException('Adding unsupported object type.');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function remove($object)
	{
		if ($object instanceof Item) {
			return $this->removeItem($object);
		} elseif ($object instanceof Rule) {
			return $this->removeRule($object);
		}
		
		throw new InvalidArgumentException('Removing unsupported object type.');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function update($name, $object)
	{
		if ($object instanceof Item) {
			if ($object->ruleName && $this->getRule($object->ruleName) === null) {
				$rule = \Yii::createObject($object->ruleName);
				$rule->name = $object->ruleName;
				$this->addRule($rule);
			}
			
			return $this->updateItem($name, $object);
		} elseif ($object instanceof Rule) {
			return $this->updateRule($name, $object);
		}
		
		throw new InvalidArgumentException('Updating unsupported object type.');
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
	 * Set default roles
	 * @param string[]|\Closure $roles either array of roles or a callable returning it
	 * @throws InvalidArgumentException when $roles is neither array nor Closure
	 * @throws InvalidValueException when Closure return is not an array
	 * @since 2.0.14
	 */
	public function setDefaultRoles($roles)
	{
		if (is_array($roles)) {
			$this->defaultRoles = $roles;
		} elseif ($roles instanceof \Closure) {
			$roles = call_user_func($roles);
			if (!is_array($roles)) {
				throw new InvalidValueException('Default roles closure must return an array');
			}
			$this->defaultRoles = $roles;
		} else {
			throw new InvalidArgumentException('Default roles must be either an array or a callable');
		}
	}
	
	/**
	 * Get default roles
	 * @return string[] default roles
	 * @since 2.0.14
	 */
	public function getDefaultRoles()
	{
		return $this->defaultRoles;
	}
	
	/**
	 * Returns defaultRoles as array of Role objects.
	 * @since 2.0.12
	 * @return Role[] default roles. The array is indexed by the role names
	 */
	public function getDefaultRoleInstances()
	{
		$result = [];
		foreach ($this->defaultRoles as $roleName) {
			$result[$roleName] = $this->createRole($roleName);
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissions()
	{
		return $this->getItems(Item::TYPE_PERMISSION);
	}
	
	
}
