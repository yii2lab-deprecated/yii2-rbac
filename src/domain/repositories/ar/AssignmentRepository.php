<?php

namespace yii2lab\rbac\domain\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2lab\rbac\domain\repositories\traits\AssignmentTrait;

class AssignmentRepository extends ActiveArRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;
	
}