<?php

namespace yii2lab\rbac\domain\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use yii2lab\rbac\domain\interfaces\repositories\Assignment;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2lab\rbac\domain\interfaces\repositories\Permission;
use yii2lab\rbac\domain\interfaces\repositories\Role;
use yii2lab\rbac\domain\repositories\traits\AssignmentTrait;

class AssignmentRepository extends ActiveArRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;

}