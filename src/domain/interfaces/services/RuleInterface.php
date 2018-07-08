<?php

namespace yii2lab\rbac\domain\interfaces\services;

use yii\base\InvalidConfigException;
use yii\rbac\Item;
use yii\rbac\Rule;

interface RuleInterface {
	
	/**
	 * Returns the rule of the specified name.
	 * @param string $name the rule name
	 * @return null|Rule the rule object, or null if the specified name does not correspond to a rule.
	 */
	public function getRule($name);
	
	/**
	 * Returns all rules available in the system.
	 * @return Rule[] the rules indexed by the rule names
	 */
	public function getRules();
	
	/**
	 * Removes all rules.
	 * All roles and permissions which have rules will be adjusted accordingly.
	 */
	public function removeAllRules();
	
	//public function removeAll();
	
	/**
	 * Adds a rule to the RBAC system.
	 * @param Rule $rule the rule to add
	 * @return bool whether the rule is successfully added to the system
	 * @throws \Exception if data validation or saving fails (such as the name of the rule is not unique)
	 */
	public function addRule($rule);
	
	/**
	 * Removes a rule from the RBAC system.
	 * @param Rule $rule the rule to remove
	 * @return bool whether the rule is successfully removed
	 * @throws \Exception if data validation or saving fails (such as the name of the rule is not unique)
	 */
	public function removeRule($rule);
	
	/**
	 * Updates a rule to the RBAC system.
	 * @param string $name the name of the rule being updated
	 * @param Rule $rule the updated rule
	 * @return bool whether the rule is successfully updated
	 * @throws \Exception if data validation or saving fails (such as the name of the rule is not unique)
	 */
	public function updateRule($name, $rule);
	
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
	public function executeRule($user, $item, $params);
}