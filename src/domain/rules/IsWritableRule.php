<?php

namespace yii2lab\rbac\domain\rules;

use yii\rbac\Rule;
use yii2lab\domain\BaseEntity;
use yii2lab\extension\common\exceptions\InvalidMethodParameterException;
use yii2mod\helpers\ArrayHelper;

class IsWritableRule  extends Rule
{
	public $name = 'isWritable';
	public $attributes = [
		'readonly' => true,
		'is_readonly' => true,
		'writable' => false,
		'is_writable' => false,
	];

	/**
	 * @param string|integer $user ID пользователя.
	 * @param \yii\rbac\Item $item роль или разрешение с которым это правило ассоциировано
	 * @param array $params параметры, переданные в ManagerInterface::checkAccess(), например при вызове проверки
	 * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
	 */
	public function execute($user, $item, $params)
	{
		if(empty($params)) {
			return true;
		}
		$params = $this->toArray($params);
		return $this->isAllow($params);
	}

	/**
	 * @param array|BaseEntity $params
	 *
	 * @return array
	 * @throws InvalidMethodParameterException
	 */
	private function toArray($params) {
		if(is_array($params)) {
			return $params;
		}
		if(is_object($params)) {
			return ArrayHelper::toArray($params);
		}
		throw new InvalidMethodParameterException;
	}

	private function isAllow($params) {
		foreach($this->attributes as $attributeName => $isNegative) {
			if($this->isAllowAttribute($params, $attributeName, $isNegative)) {
				return true;
			}
		}
		return false;
	}

	private function isAllowAttribute($params, $name, $isNegative = false) {
		if(ArrayHelper::has($params, $name)) {
			$isAllow = (bool) ArrayHelper::getValue($params, $name);
			return $isNegative ? ! $isAllow : $isAllow;
		}
		return false;
	}
}
