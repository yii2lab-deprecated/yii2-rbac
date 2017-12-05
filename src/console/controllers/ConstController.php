<?php

namespace yii2lab\rbac\console\controllers;

use Yii;
use yii2lab\console\yii\console\Controller;
use yii2lab\console\helpers\input\Question;
use yii2lab\console\helpers\Output;

class ConstController extends Controller
{
	
	public function actionGenerate()
	{
		Question::confirm(null, 1);
		$count = Yii::$app->rbac->const->generateAll();
		if($count) {
			Output::block("Generated {$count} constants");
		} else {
			Output::block("All rules exists!");
		}
	}
	
}
