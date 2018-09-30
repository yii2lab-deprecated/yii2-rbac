<?php

namespace yii2lab\rbac\domain\rbac;

use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\rbac\Rule;
use yii2lab\extension\common\exceptions\DeprecatedException;
use yii2lab\extension\develop\helpers\ReleaseHelper;

class PhpManager extends Component implements ManagerInterface
{
	
	/**
	 * {@inheritdoc}
	 */
	public function checkAccess($userId, $permissionName, $params = [])
	{
		return \App::$domain->rbac->manager->checkAccess($userId, $permissionName, $params = []);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignments($userId)
	{
		return \App::$domain->rbac->assignment->getAssignments($userId);
	}
	
	/**
	 * {@inheritdoc}
	 * @since 2.0.8
	 */
	public function canAddChild($parent, $child)
	{
		return \App::$domain->rbac->item->canAddChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function addChild($parent, $child)
	{
		return \App::$domain->rbac->item->addChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChild($parent, $child)
	{
		return \App::$domain->rbac->item->removeChild($parent, $child);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeChildren($parent)
	{
		return \App::$domain->rbac->item->removeChildren($parent);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function hasChild($parent, $child)
	{
		return \App::$domain->rbac->item->hasChild($parent, $child);
	}
	
	/**
	 * @inheritdoc
	 */
	public function assign($role, $userId)
	{
		return \App::$domain->rbac->assignment->assign($role, $userId);
	}
	
	/**
	 * @inheritdoc
	 */
	public function revoke($role, $userId)
	{
		\App::$domain->rbac->assignment->revoke($role, $userId);
	}
	
	/**
	 * @inheritdoc
	 */
	public function revokeAll($userId)
	{
		\App::$domain->rbac->assignment->revokeAll($userId);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAssignment($roleName, $userId)
	{
		return \App::$domain->rbac->assignment->getAssignment($roleName, $userId);
	}
	
	/**
	 * {@inheritdoc}
	 * @deprecated
	 */
	public function getItems($type)
	{
		throw new DeprecatedException(__METHOD__);
		return \App::$domain->rbac->item->getItems($type);
	}
	
	/**
	 * @inheritdoc
	 * @deprecated
	 */
	public function removeItem($item)
	{
		throw new DeprecatedException(__METHOD__);
		return \App::$domain->rbac->item->removeItem($item);
	}
	
	/**
	 * {@inheritdoc}
	 * @deprecated
	 */
	public function getItem($name)
	{
		throw new DeprecatedException(__METHOD__);
		return \App::$domain->rbac->item->getItem($name);
	}
	
	/**
	 * {@inheritdoc}
	 * @deprecated
	 */
	public function updateRule($name, $rule)
	{
		throw new DeprecatedException(__METHOD__);
		return \App::$domain->rbac->rule->updateRule($name, $rule);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRule($name)
	{
		return \App::$domain->rbac->rule->getRule($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRules()
	{
		return \App::$domain->rbac->rule->getRules();
	}
	
	/**
	 * {@inheritdoc}
	 * The roles returned by this method include the roles assigned via [[$defaultRoles]].
	 */
	public function getRolesByUser($userId)
	{
		return \App::$domain->rbac->item->getRolesByUser($userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildRoles($roleName)
	{
		return \App::$domain->rbac->item->getChildRoles($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByRole($roleName)
	{
		return \App::$domain->rbac->item->getPermissionsByRole($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissionsByUser($userId)
	{
		return \App::$domain->rbac->item->getPermissionsByUser($userId);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getChildren($name)
	{
		return \App::$domain->rbac->item->getChildren($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAll()
	{
		$this->removeAllPermissions();
		$this->removeAllRoles();
		$this->removeAllRules();
		$this->removeAllAssignments();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllPermissions()
	{
		return \App::$domain->rbac->item->removeAllPermissions();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRoles()
	{
		return \App::$domain->rbac->item->removeAllRoles();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRules()
	{
		return \App::$domain->rbac->rule->removeAllRules();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllAssignments()
	{
		return \App::$domain->rbac->assignment->removeAllAssignments();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getUserIdsByRole($roleName)
	{
		return \App::$domain->rbac->assignment->getUserIdsByRole($roleName);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function createRole($name) {
		return \App::$domain->rbac->item->createRole($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function createPermission($name) {
		return \App::$domain->rbac->item->createPermission($name);
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
				\App::$domain->rbac->rule->addRule($rule);
			}
			
			return \App::$domain->rbac->item->addItem($object);
		} elseif ($object instanceof Rule) {
			return \App::$domain->rbac->rule->addRule($object);
		}
		
		throw new InvalidArgumentException('Adding unsupported object type.');
	}
	/*public function add($object) {
		return \App::$domain->rbac->item->add($object);
	}*/
	
	/**
	 * {@inheritdoc}
	 */
	public function remove($object)
	{
		if ($object instanceof Item) {
			\App::$domain->rbac->assignment->removeItem($object);
			return \App::$domain->rbac->item->removeItem($object);
		} elseif ($object instanceof Rule) {
			return \App::$domain->rbac->rule->removeRule($object);
		}
		
		throw new InvalidArgumentException('Removing unsupported object type.');
	}
	/*public function remove($object) {
		return \App::$domain->rbac->item->remove($object);
	}*/
	
	/**
	 * {@inheritdoc}
	 */
	public function update($name, $object)
	{
		if ($object instanceof Item) {
			if ($object->ruleName && $this->getRule($object->ruleName) === null) {
				$rule = \Yii::createObject($object->ruleName);
				$rule->name = $object->ruleName;
				\App::$domain->rbac->rule->addRule($rule);
			}
			$updateItem = \App::$domain->rbac->item->updateItem($name, $object);
			\App::$domain->rbac->assignment->updateItem($name, $object);
			return $updateItem;
		} elseif ($object instanceof Rule) {
			return $this->updateRule($name, $object);
		}
		
		throw new InvalidArgumentException('Updating unsupported object type.');
	}
	/*public function update($name, $object) {
		return \App::$domain->rbac->item->update($name, $object);
	}*/
	
	/**
	 * {@inheritdoc}
	 */
	public function getRole($name) {
		return \App::$domain->rbac->item->getRole($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRoles() {
		return \App::$domain->rbac->item->getRoles();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermission($name) {
		return \App::$domain->rbac->item->getPermission($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPermissions() {
		return \App::$domain->rbac->item->getPermissions();
	}
}
