<?php

namespace tests\functional\services;

use yii2lab\test\fixtures\UserAssignmentFixture;
use yii2lab\test\fixtures\UserFixture;
use yii2lab\test\Test\Unit;
use yii\web\ForbiddenHttpException;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

class ManagerTest extends Unit
{
	const ID_ADMIN = 381949;
	const ID_USER = 381070;

	public function _before()
	{
		$this->tester->haveFixtures([
			[
				'class' => UserFixture::class,
				'dataFile' => '@vendor/yii2lab/yii2-test/src/fixtures/data/user.php'
			],
			[
				'class' => UserAssignmentFixture::class,
				'dataFile' => '@vendor/yii2lab/yii2-test/src/fixtures/data/user_assignment.php'
			],
		]);
	}

	public function testCanByUser()
	{
		TestAuthHelper::authById(self::ID_USER);
		try {
			\App::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertBad();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testCanByAdmin()
	{
		TestAuthHelper::authById(self::ID_ADMIN);
		try {
			\App::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertNice();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertBad();
		}
	}
	
	public function testCanByGuest()
	{
		TestAuthHelper::defineAccountDomain();
		try {
			\App::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertBad();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testCanByGuest2()
	{
		TestAuthHelper::defineAccountDomain();
		try {
			\App::$domain->rbac->manager->can('@');
			$this->tester->assertBad();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	/*public function testCanByGuest3()
	{
		try {
			\App::$domain->rbac->manager->can('?');
			$this->tester->assertNice();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertBad();
		}
	}
	
	public function testCanByUser2()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			\App::$domain->rbac->manager->can('?');
			$this->tester->assertNice();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertBad();
		}
	}*/
}
