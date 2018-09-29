<?php

namespace yii2lab\rbac\domain\repositories\disc;

use yii\rbac\Rule;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\rbac\domain\helpers\DiscHelper;
use yii2lab\rbac\domain\interfaces\repositories\RuleInterface;

/**
 * Class RuleRepository
 *
 * @package yii2lab\rbac\domain\repositories\disc
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 */
class RuleRepository extends BaseRepository implements RuleInterface {
	
	/**
	 * @var string the path of the PHP script that contains the authorization rules.
	 * This can be either a file path or a [path alias](guide:concept-aliases) to the file.
	 * Make sure this file is writable by the Web server process if the authorization needs to be changed online.
	 */
	public $ruleFile = '@app/rbac/rules.php';
	
	/**
	 * @var Rule[]
	 */
	protected $rules = []; // ruleName => rule
	
	public function init()
	{
		parent::init();
		//$this->ruleFile = Yii::getAlias($this->ruleFile);
		$this->load();
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
	 */
	public function removeRule($rule)
	{
		if (isset($this->rules[$rule->name])) {
			unset($this->rules[$rule->name]);
			$this->saveRules();
			return true;
		}
		
		return false;
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
	public function addRule($rule)
	{
		$this->rules[$rule->name] = $rule;
		$this->saveRules();
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	/*public function removeAll()
	{
		//$this->children = [];
		//$this->items = [];
		//$this->assignments = [];
		$this->rules = [];
		$this->save();
	}*/
	
	protected function load()
	{
		$this->rules = [];
		$rules = DiscHelper::loadFromFile($this->ruleFile);
		foreach ($rules as $name => $ruleData) {
			$this->rules[$name] = unserialize($ruleData);
		}
	}
	
	/**
	 * Saves authorization data into persistent storage.
	 */
	protected function save()
	{
		//$this->saveItems();
		//$this->saveAssignments();
		$this->saveRules();
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
		DiscHelper::saveToFile($rules, $this->ruleFile);
		
		$this->domain->const->generateAll();
	}
	
}
