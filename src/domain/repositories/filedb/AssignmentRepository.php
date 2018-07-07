<?php

namespace yii2lab\rbac\domain\repositories\filedb;

use yii2lab\domain\repositories\ActiveFiledbRepository;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;
use yii2lab\rbac\domain\repositories\traits\AssignmentTrait;

class AssignmentRepository extends ActiveFiledbRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;
	
}