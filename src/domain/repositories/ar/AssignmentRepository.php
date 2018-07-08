<?php

namespace yii2lab\rbac\domain\repositories\ar;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2lab\rbac\domain\repositories\traits\AssignmentTrait;

class AssignmentRepository extends BaseActiveArRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;
	
}