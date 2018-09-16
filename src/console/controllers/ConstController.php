<?php

namespace yii2lab\rbac\console\controllers;

use Yii;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\input\Question;
use yii2lab\extension\console\helpers\Output;

class ConstController extends Controller
{
	
	/**
	 * Generating enums for RBAC roles, permissions and rules
	 */
	public function actionGenerate()
	{
		Question::confirm(null, 1);
		$count = \App::$domain->rbac->const->generateAll();
		if($count) {
			Output::block("Generated {$count} constants");
		} else {
			Output::block("All rules exists!");
		}
	}
	
}
