<?php

namespace yii2lab\rbac\domain\services;

use common\enums\app\AppEnum;
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
	
	public function addRule($rule) {
		return $this->repository->addRule($rule);
	}
	
	public function removeRule($rule) {
		return $this->repository->removeRule($rule);
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

}
