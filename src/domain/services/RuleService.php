<?php

namespace yii2lab\rbac\domain\services;

use yii\base\InvalidConfigException;
use yii\rbac\Item;
use yii\rbac\Rule;
use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\interfaces\services\RuleInterface;
use yii2lab\rbac\domain\repositories\disc\RuleRepository;

/**
 * Class RuleService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property RuleRepository $repository
 */
class RuleService extends BaseService implements RuleInterface {
	
	public function updateRule($name, $rule)
	{
		$result = $this->repository->updateRule($name, $rule);
		$this->domain->const->generateRules();
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRule($name)
	{
		return $this->repository->getRule($name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRules()
	{
		return $this->repository->getRules();
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllRules()
	{
		$result = $this->repository->removeAllRules();
		$this->domain->const->generateRules();
		return $result;
	}
	
	public function addRule($rule) {
		$result = $this->repository->addRule($rule);
		$this->domain->const->generateRules();
		return $result;
	}
	
	public function removeRule($rule) {
		$result = $this->repository->removeRule($rule);
		if($result) {
			$this->domain->item->removeRuleFromItems($rule);
		}
		$this->domain->const->generateRules();
		return $result;
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
	public function executeRule($user, $item, $params)
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
	
	
	// =================== old code ==========================
	
	/*public function searchInAllApps()
	{
		$appList = AppEnum::values();
		$collection = $this->repository->searchByAppList($appList);
		return $collection;
	}

	public function insertBatch($collection)
	{
		return $this->repository->insertBatch($collection);
	}*/
	
	/*public function removeAll() {
		// TODO: Implement removeAll() method.
	}*/
	
}
