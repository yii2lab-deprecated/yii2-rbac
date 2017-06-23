<?php

$identity = Yii::$app->user->identity;

$data = [];
if(Yii::$app->user->isGuest) {
	$data[0] = [];
} else {
	$data[$identity->id] = $identity->roles;
}

return $data;
