<?php

namespace yii2lab\rbac\domain\entities;

use yii2lab\domain\BaseEntity;

class RuleEntity extends BaseEntity {

	protected $name;
	protected $class;

	public function getName() {
		if(!empty($this->name)) {
			return $this->name;
		}
		return basename(get_class($this));
	}

}