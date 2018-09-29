<?php

namespace yii2lab\rbac\domain\repositories\traits;

use yii\rbac\Item;
use yii\rbac\Role;
use yii\base\InvalidArgumentException;
use yii\base\InvalidValueException;

trait ItemTrait {
	
	/**
	 * @var array a list of role names that are assigned to every user automatically without calling [[assign()]].
	 * Note that these roles are applied to users, regardless of their state of authentication.
	 */
	protected $defaultRoles = [];
	
	/**
	 * Returns defaultRoles as array of Role objects.
	 * @since 2.0.12
	 * @return Role[] default roles. The array is indexed by the role names
	 */
	private function getDefaultRoleInstances()
	{
		$result = [];
		foreach ($this->defaultRoles as $roleName) {
			$result[$roleName] = $this->createRole($roleName);
		}
		
		return $result;
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
	
}