<?php

namespace yii2lab\rbac\domain\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\helpers\Helper;

class RuleService extends ActiveBaseService {

	public function searchInAllApps()
	{
		$appList = Helper::getApps();
		$collection = $this->repository->searchByAppList($appList);
		return $collection;
	}

	public function insertBatch($collection)
	{
		return $this->repository->insertBatch($collection);
	}

}
