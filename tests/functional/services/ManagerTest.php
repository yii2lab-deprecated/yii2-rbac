<?php

namespace tests\functional\services;

use yii2lab\test\Test\Unit;
use tests\functional\enums\LoginEnum;
use yii\web\ForbiddenHttpException;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

class ManagerTest extends Unit
{
	
	public function testCanByUser()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			\App::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertBad();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testCanByAdmin()
	{
		TestAuthHelper::authById(LoginEnum::ID_ADMIN);
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
