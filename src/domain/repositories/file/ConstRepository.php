<?php

namespace yii2lab\rbac\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2lab\helpers\generator\EnumGeneratorHelper;
use yii2lab\rbac\domain\helpers\GenerateHelper;

class ConstRepository extends BaseRepository {

	public $dirAlias = '@common/enums/rbac';

	public function generatePermissions($collection)
	{
		$constList = GenerateHelper::getConstListFromCollection($collection, GenerateHelper::PREFIX_PERMISSION);
		EnumGeneratorHelper::generateClass($this->dirAlias . SL . GenerateHelper::TYPE_PERMISSION, $constList);
		return $constList;
	}

	public function generateRoles($collection)
	{
		$constList = GenerateHelper::getConstListFromCollection($collection, GenerateHelper::PREFIX_ROLE);
		EnumGeneratorHelper::generateClass($this->dirAlias . SL . GenerateHelper::TYPE_ROLE, $constList);
		return $constList;
	}

	public function generateRules($collection)
	{
		$constList = GenerateHelper::getConstListFromCollection($collection);
		EnumGeneratorHelper::generateClass($this->dirAlias . SL . GenerateHelper::TYPE_RULE, $constList);
		return $constList;
	}

}
