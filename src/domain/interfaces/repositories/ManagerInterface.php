<?php

namespace yii2lab\rbac\domain\interfaces\repositories;

interface ManagerInterface {
	
	public function isGuestOnlyAllowed($rule);
	public function isAuthOnlyAllowed($rule);

}