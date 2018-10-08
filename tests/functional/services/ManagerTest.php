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
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testCanByAdmin()
	{
		TestAuthHelper::authById(LoginEnum::ID_ADMIN);
		try {
			\App::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}
	
	public function testCanByGuest()
	{
		TestAuthHelper::defineAccountDomain();
		try {
			\App::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testCanByGuest2()
	{
		TestAuthHelper::defineAccountDomain();
		try {
			\App::$domain->rbac->manager->can('@');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	/*public function testCanByGuest3()
	{
		try {
			\App::$domain->rbac->manager->can('?');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}
	
	public function testCanByUser2()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			\App::$domain->rbac->manager->can('?');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}*/
}