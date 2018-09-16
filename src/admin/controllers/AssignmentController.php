<?php

namespace yii2lab\rbac\admin\controllers;

use mdm\admin\models\Assignment;
use Yii;
use yii2module\account\domain\v2\entities\LoginEntity;

class AssignmentController extends \mdm\admin\controllers\AssignmentController {
	
	/**
	 * Lists all Assignment models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$dataProvider = \App::$domain->account->login->getDataProvider();
		return $this->render('index', [
			'dataProvider' => $dataProvider,
			//'searchModel' => $searchModel,
			'idField' => 'id',
			'usernameField' => $this->usernameField,
			'extraColumns' => $this->extraColumns,
		]);
	}
	
	/**
	 * Displays a single Assignment model.
	 * @param  integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$user = \App::$domain->account->login->oneById($id);
		$model = new Assignment($id, $user);
		return $this->render('view', [
			'model' => $model,
			'idField' => $this->idField,
			'usernameField' => $this->usernameField,
			'fullnameField' => $this->fullnameField,
		]);
	}
	
	/**
	 * Assign items
	 * @param string $id
	 * @return array
	 */
	public function actionAssign($id)
	{
		$items = Yii::$app->getRequest()->post('items', []);
		foreach($items as $role) {
			Yii::$app->authManager->assign($role, $id);
		}
		$user = \App::$domain->account->login->oneById($id);
		$model = new Assignment($id, $user);
		$success = true;
		Yii::$app->getResponse()->format = 'json';
		return array_merge($model->getItems(), ['success' => $success]);
	}
	
	/**
	 * Assign items
	 * @param string $id
	 * @return array
	 */
	public function actionRevoke($id)
	{
		$items = Yii::$app->getRequest()->post('items', []);
		/** @var LoginEntity $user */
		$user = \App::$domain->account->login->oneById($id);
		if(count($user->roles) > 1) {
			foreach($items as $role) {
				Yii::$app->authManager->revoke($role, $id);
			}
		}
		$model = new Assignment($id, $user);
		$success = true;
		Yii::$app->getResponse()->format = 'json';
		return array_merge($model->getItems(), ['success' => $success]);
	}
	
}

