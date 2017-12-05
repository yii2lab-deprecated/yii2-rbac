<?php

namespace yii2lab\rbac\console\controllers;

use Yii;
use yii2lab\console\yii\console\Controller;
use yii2lab\console\helpers\input\Question;
use yii2lab\console\helpers\Output;

class RuleController extends Controller
{
	
	public function actionAdd()
	{
		Question::confirm(null, 1);
		$collection = Yii::$app->rbac->rule->searchInAllApps();
		$rules = Yii::$app->rbac->rule->insertBatch($collection);
		if($rules) {
			Output::items($rules, "Added " . count($rules) . " rules");
		} else {
			Output::block("All rules exists!");
		}
	}
	
}
