<?php

namespace yii2lab\rbac\domain\rbac;

use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii2lab\rbac\domain\services\AssignmentService;
use yii2lab\rbac\domain\services\ItemService;
use yii2lab\rbac\domain\services\ManagerService;
use yii2lab\rbac\domain\services\RuleService;

class PhpManager extends Component implements ManagerInterface
{
	
	public $itemFile;
	public $ruleFile;
	public $defaultRoles;
	
	/**
	 * @var \yii2lab\rbac\domain\rbac\custom\PhpManager
	 */
	protected $instance;
	
	/**
	 * @var AssignmentService
	 */
	protected $assignmentService;
	
	/**
	 * @var ItemService
	 */
	protected $itemService;
	
	/**
	 * @var ManagerService
	 */
	protected $managerService;
	
	/**
	 * @var RuleService
	 */
	protected $ruleService;
	
	/**
	 * Initializes the application component.
	 * This method overrides parent implementation by loading the authorization data
	 * from PHP script.
	 */
	public function init()
	{
		parent::init();
		/*$this->instance = Yii::createObject([
			'class' => 'yii2lab\rbac\domain\rbac\custom\PhpManager',
			'itemFile' => '@common/data/rbac/items.php',
			'ruleFile' => '@common/data/rbac/rules.php',
			'defaultRoles' => ['rGuest'],
		]);*/
		//$this->assignmentService = Yii::$domain->rbac->assignment;
		//$this->managerService = Yii::$domain->rbac->manager;
		//$this->itemService = Yii::$domain->rbac->item;
		//$this->ruleService = Yii::$domain->rbac->rule;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function checkAccess($userId, $permissionName, $params = [])
	{
		return Yii::$domain->rbac->manager->checkAccess($userId, $permissionName, $params = []);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignments($userId)
	{
		return Yii::$domain->rbac->assignment->allAssignments($userId);
	}
	
	/**
	 * {@inheritdoc}
	 * @since 2.0.8
	 */
	public function canAddChild($parent, $child)
	{
		return Yii::$domain->rbac->item->canAddChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function addChild($parent, $child)
	{
		return Yii::$domain->rbac->item->addChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChild($parent, $child)
	{
		return Yii::$domain->rbac->item->removeChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChildren($parent)
	{
		return Yii::$domain->rbac->item->removeChildren($parent);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function hasChild($parent, $child)
	{
		return Yii::$domain->rbac->item->hasChild($parent, $child);
	}
	
	/**
	 * @inheritdoc
	 */
	public function assign($role, $userId)
	{
		return Yii::$domain->rbac->assignment->assignRole($userId, $role);
	}
	
	/**
	 * @inheritdoc
	 */
	public function revoke($role, $userId)
	{
		Yii::$domain->rbac->assignment->revokeRole($userId, $role);
	}
	
	/**
	 * @inheritdoc
	 */
	public function revokeAll($userId)
	{
		Yii::$domain->rbac->assignment->revokeAllRoles($userId);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignment($roleName, $userId)
	{
		return Yii::$domain->rbac->assignment->getAssignment($roleName, $userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getItems($type)
	{
		return Yii::$domain->rbac->item->getItems($type);
	}
	
	/**
	 * @inheritdoc
	 */
	public function removeItem($item)
	{
		return Yii::$domain->rbac->item->removeItem($item);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getItem($name)
	{
		return Yii::$domain->rbac->item->getItem($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function updateRule($name, $rule)
	{
		return Yii::$domain->rbac->rule->updateRule($name, $rule);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRule($name)
	{
		return Yii::$domain->rbac->rule->getRule($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRules()
	{
		return Yii::$domain->rbac->rule->getRules();
	}
	
	/**
	 * {@inheritdoc}
	 * The roles returned by this method include the roles assigned via [[$defaultRoles]].
	 */
	public function getRolesByUser($userId)
	{
		return Yii::$domain->rbac->item->getRolesByUser($userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildRoles($roleName)
	{
		return Yii::$domain->rbac->item->getChildRoles($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByRole($roleName)
	{
		return Yii::$domain->rbac->item->getPermissionsByRole($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByUser($userId)
	{
		return Yii::$domain->rbac->item->getPermissionsByUser($userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildren($name)
	{
		return Yii::$domain->rbac->item->getChildren($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAll()
	{
		Yii::$domain->rbac->item->removeAllPermissions();
		Yii::$domain->rbac->item->removeAllRoles();
		Yii::$domain->rbac->rule->removeAllRules();
		Yii::$domain->rbac->assignment->removeAll();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllPermissions()
	{
		return Yii::$domain->rbac->item->removeAllPermissions();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRoles()
	{
		return Yii::$domain->rbac->item->removeAllRoles();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRules()
	{
		return Yii::$domain->rbac->rule->removeAllRules();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllAssignments()
	{
		return Yii::$domain->rbac->assignment->removeAllAssignments();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getUserIdsByRole($roleName)
	{
		return Yii::$domain->rbac->assignment->allUserIdsByRole($roleName);
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
		return Yii::$domain->rbac->item->createRole($name);
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
		return Yii::$domain->rbac->item->createPermission($name);
	}
	
	/**
	 * Adds a role, permission or rule to the RBAC system.
	 *
	 * @param Role|Permission|Rule $object
	 *
	 * @return bool whether the role, permission or rule is successfully added to the system
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function add($object)
	{
		if ($object instanceof Item) {
			if ($object->ruleName && $this->getRule($object->ruleName) === null) {
				$rule = \Yii::createObject($object->ruleName);
				$rule->name = $object->ruleName;
				Yii::$domain->rbac->rule->addRule($rule);
			}
			
			return Yii::$domain->rbac->item->addItem($object);
		} elseif ($object instanceof Rule) {
			return Yii::$domain->rbac->rule->addRule($object);
		}
		
		throw new InvalidArgumentException('Adding unsupported object type.');
	}
	/*public function add($object) {
		return Yii::$domain->rbac->item->add($object);
	}*/
	
	
	/**
	 * Removes a role, permission or rule from the RBAC system.
	 *
	 * @param Role|Permission|Rule $object
	 *
	 * @return bool whether the role, permission or rule is successfully removed
	 */
	public function remove($object)
	{
		if ($object instanceof Item) {
			Yii::$domain->rbac->assignment->removeItem($object);
			return Yii::$domain->rbac->item->removeItem($object);
		} elseif ($object instanceof Rule) {
			return Yii::$domain->rbac->rule->removeRule($object);
		}
		
		throw new InvalidArgumentException('Removing unsupported object type.');
	}
	/*public function remove($object) {
		return Yii::$domain->rbac->item->remove($object);
	}*/
	
	/**
	 * Updates the specified role, permission or rule in the system.
	 *
	 * @param string               $name the old name of the role, permission or rule
	 * @param Role|Permission|Rule $object
	 *
	 * @return bool whether the update is successful
	 * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
	 */
	public function update($name, $object)
	{
		if ($object instanceof Item) {
			if ($object->ruleName && $this->getRule($object->ruleName) === null) {
				$rule = \Yii::createObject($object->ruleName);
				$rule->name = $object->ruleName;
				Yii::$domain->rbac->rule->addRule($rule);
			}
			$updateItem = Yii::$domain->rbac->item->updateItem($name, $object);
			Yii::$domain->rbac->assignment->updateItem($name, $object);
			return $updateItem;
		} elseif ($object instanceof Rule) {
			return $this->updateRule($name, $object);
		}
		
		throw new InvalidArgumentException('Updating unsupported object type.');
	}
	/*public function update($name, $object) {
		return Yii::$domain->rbac->item->update($name, $object);
	}*/
	
	/**
	 * Returns the named role.
	 *
	 * @param string $name the role name.
	 *
	 * @return null|Role the role corresponding to the specified name. Null is returned if no such role.
	 */
	public function getRole($name) {
		return Yii::$domain->rbac->item->getRole($name);
	}
	
	/**
	 * Returns all roles in the system.
	 *
	 * @return Role[] all roles in the system. The array is indexed by the role names.
	 */
	public function getRoles() {
		return Yii::$domain->rbac->item->getRoles();
	}
	
	/**
	 * Returns the named permission.
	 *
	 * @param string $name the permission name.
	 *
	 * @return null|Permission the permission corresponding to the specified name. Null is returned if no such permission.
	 */
	public function getPermission($name) {
		return Yii::$domain->rbac->item->getPermission($name);
	}
	
	/**
	 * Returns all permissions in the system.
	 *
	 * @return Permission[] all permissions in the system. The array is indexed by the permission names.
	 */
	public function getPermissions() {
		return Yii::$domain->rbac->item->getPermissions();
	}
}
