<?php

namespace yii2lab\rbac\domain\services;

use common\enums\app\AppEnum;
use Yii;
use yii2lab\domain\services\base\BaseService;
use yii2lab\rbac\domain\repositories\disc\RuleRepository;

/**
 * Class RuleService
 *
 * @package yii2lab\rbac\domain\services
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 * @property RuleRepository $repository
 */
class RuleService extends BaseService {
	/*
	public function init()
	{
		parent::init();
		$this->repository = Yii::createObject([
			'class' => RuleRepository::class,
			'itemFile' => '@common/data/rbac/items.php',
			'ruleFile' => '@common/data/rbac/rules.php',
			'defaultRoles' => ['rGuest'],
		]);
	}
	*/
	public function updateRule($name, $rule)
	{
		return $this->repository->updateRule($name, $rule);
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
		return $this->repository->removeAllRules();
	}
	
	
	
	
	
	
	
	// =================== old code ==========================
	
	public function searchInAllApps()
	{
		$appList = AppEnum::values();
		$collection = $this->repository->searchByAppList($appList);
		return $collection;
	}

	public function insertBatch($collection)
	{
		return $this->repository->insertBatch($collection);
	}

}
