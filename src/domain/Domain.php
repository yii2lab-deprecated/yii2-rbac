<?php

namespace yii2lab\rbac\domain;

use yii2lab\domain\enums\Driver;

class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [ 
				'rule' => Driver::FILE, 
				'const' => Driver::FILE, 
			], 
			'services' => [ 
				'rule', 
				'const', 
			], 
		];
	}
	
}