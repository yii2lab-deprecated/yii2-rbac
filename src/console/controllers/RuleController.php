<?php

namespace yii2lab\rbac\console\controllers;

use Yii;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\input\Question;
use yii2lab\extension\console\helpers\Output;

class RuleController extends Controller
{
	
	/**
	 * Search and add RBAC rules
	 */
	public function actionAdd()
	{
		Question::confirm(null, 1);
		$collection = Yii::$domain->rbac->rule->searchInAllApps();
		$rules = Yii::$domain->rbac->rule->insertBatch($collection);
		if($rules) {
			Output::items($rules, "Added " . count($rules) . " rules");
		} else {
			Output::block("All rules exists!");
		}
	}
	
}
