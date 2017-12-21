<?php

namespace yii2lab\rbac\domain\services;

use common\enums\app\AppEnum;
use yii2lab\domain\services\ActiveBaseService;

class RuleService extends ActiveBaseService {

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
