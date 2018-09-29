<?php

namespace tests\functional\services;

use common\runtime\test\rbac\PermissionEnum;
use common\runtime\test\rbac\RoleEnum;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii2lab\domain\enums\Driver;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2lab\test\helpers\DataHelper;
use yii2lab\test\Test\Unit;

class ItemTest extends Unit {
	
	const PACKAGE = 'yii2lab/yii2-rbac';
	const DATA_ALIAS = '@common/runtime/test/rbac';
	const ADMIN_ID = 381949;
	
	public function testInit() {
		$itemService = $this->forgeDomain()->item;
		
		// --- remove all ---
		
		$itemService->removeAllPermissions();
		$itemService->removeAllRoles();
		
		// --- add roles ---
		
		$rUser = $itemService->createRole('rUser');
		$itemService->addItem($rUser);
		
		$rModerator = $itemService->createRole('rModerator');
		$itemService->addItem($rModerator);
		
		$rAdministrator = $itemService->createRole('rAdministrator');
		$itemService->addItem($rAdministrator);
		
		// --- add permissions ---
		
		$oProfileManage = $itemService->createPermission('oProfileManage');
		$itemService->addItem($oProfileManage);
		
		$oGeoCityManage = $itemService->createPermission('oGeoCityManage');
		$itemService->addItem($oGeoCityManage);
		
		$oGeoCountryManage = $itemService->createPermission('oGeoCountryManage');
		$itemService->addItem($oGeoCountryManage);
		
		$oGeoCurrencyManage = $itemService->createPermission('oGeoCurrencyManage');
		$itemService->addItem($oGeoCurrencyManage);
		
		$oBackendAll = $itemService->createPermission('oBackendAll');
		$itemService->addItem($oBackendAll);
		
		$oRoot = $itemService->createPermission('oRoot');
		$itemService->addItem($oRoot);
		
		// --- add child ---
		
		$itemService->addChild($rUser, $oProfileManage);
		
		$itemService->addChild($rAdministrator, $rUser);
		$itemService->addChild($rAdministrator, $rModerator);
		$itemService->addChild($rAdministrator, $oBackendAll);
		
		$itemService->addChild($rModerator, $rUser);
		$itemService->addChild($rModerator, $oGeoCityManage);
		$itemService->addChild($rModerator, $oGeoCountryManage);
		$itemService->addChild($rModerator, $oGeoCurrencyManage);
	}
	
	public function testAddItem() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser1 = $itemService->createRole('rUser1');
		$isAdded = $itemService->addItem($rUser1);
		$this->tester->assertTrue($isAdded);
	}
	
	public function testGetItem() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getItem('rUser1');
		$this->assertEntity(__METHOD__, $actual);
	}
	
	public function testUpdateItem() {
		$itemService = $this->forgeDomain()->item;
		
		$item = $itemService->getItem('rUser1');
		$item->description = 'description text';
		
		$newItem = $itemService->updateItem('rUser1', $item);
		
		$actual = $itemService->getItem('rUser1');
		$this->assertEntity(__METHOD__, $actual);
	}
	
	public function testRemoveItem() {
		$itemService = $this->forgeDomain()->item;
		
		$item = $itemService->getItem('rUser1');
		$isRemoved = $itemService->removeItem($item);
		$this->tester->assertTrue($isRemoved);
	}
	
	public function testGetRole() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getRole('rUser');
		$this->assertEntity(__METHOD__, $actual);
	}
	
	public function testGetRoles() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getRoles();
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetRolesByUser() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getRolesByUser(self::ADMIN_ID);
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetChildRoles() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getChildRoles('rModerator');
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetPermission() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getPermission('oGeoCityManage');
		$this->assertEntity(__METHOD__, $actual);
	}
	
	public function testGetPermissions() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getPermissions();
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetPermissionsByRole() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getPermissionsByRole('rModerator');
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetPermissionsByUser() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getPermissionsByUser(self::ADMIN_ID);
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testCanAddChild() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		$oGeoCountryManage = $itemService->getPermission('oGeoCountryManage');
		
		$actual = $itemService->canAddChild($rUser, $oGeoCountryManage);
		$this->tester->assertTrue($actual);
	}
	
	/*public function testCanAddChildNegative() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		$oProfileManage = $itemService->getPermission('oProfileManage');
		
		$actual = $itemService->canAddChild($rUser, $oProfileManage);
		$this->tester->assertFalse($actual);
	}*/
	
	public function testAddChild() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		$oGeoCountryManage = $itemService->getPermission('oGeoCountryManage');
		
		$actual = $itemService->addChild($rUser, $oGeoCountryManage);
		$this->tester->assertTrue($actual);
		
		$actualPermissions = $itemService->getPermissionsByRole('rUser');
		$this->assertCollection(__METHOD__, $actualPermissions);
	}
	
	public function testRemoveChild() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		$oGeoCountryManage = $itemService->getPermission('oGeoCountryManage');
		
		$actual = $itemService->removeChild($rUser, $oGeoCountryManage);
		$this->tester->assertTrue($actual);
		
		$actualPermissions = $itemService->getPermissionsByRole('rUser');
		$this->assertCollection(__METHOD__, $actualPermissions);
	}
	
	public function testRemoveChildren() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		
		$actual = $itemService->removeChildren($rUser);
		$this->tester->assertTrue($actual);
		
		$actualPermissions = $itemService->getPermissionsByRole('rUser');
		$this->assertCollection(__METHOD__, $actualPermissions);
		
		$oProfileManage = $itemService->getPermission('oProfileManage');
		$itemService->addChild($rUser, $oProfileManage);
	}
	
	public function testHasChild() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		$oProfileManage = $itemService->getPermission('oProfileManage');
		
		$actual = $itemService->hasChild($rUser, $oProfileManage);
		$this->tester->assertTrue($actual);
	}
	
	public function testHasChildNegative() {
		$itemService = $this->forgeDomain()->item;
		
		$rUser = $itemService->getRole('rUser');
		$oGeoCityManage = $itemService->getPermission('oGeoCityManage');
		
		$actual = $itemService->hasChild($rUser, $oGeoCityManage);
		$this->tester->assertFalse($actual);
	}
	
	public function testGetChildren() {
		$itemService = $this->forgeDomain()->item;
		
		$actual = $itemService->getChildren('rModerator');
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testCheckAccessRecursive() {
		$itemService = $this->forgeDomain()->item;
		
		$assignments = $this->forgeDomain()->assignment->getAssignments(self::ADMIN_ID);
		
		$isAllow = $itemService->checkAccessRecursive(self::ADMIN_ID, 'oBackendAll', [], $assignments);
		$this->tester->assertTrue($isAllow);
	}
	
	public function testCheckAccessRecursiveNegative() {
		$itemService = $this->forgeDomain()->item;
		
		$assignments = $this->forgeDomain()->assignment->getAssignments(self::ADMIN_ID);
		
		$isAllow = $itemService->checkAccessRecursive(self::ADMIN_ID, 'oRoot', [], $assignments);
		$this->tester->assertFalse($isAllow);
	}
	
	public function testGetRoleItems() {
		$itemService = $this->forgeDomain()->item;
		$actual = $itemService->getItems(Item::TYPE_ROLE);
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetPermissionItems() {
		$itemService = $this->forgeDomain()->item;
		$actual = $itemService->getItems(Item::TYPE_PERMISSION);
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testGetRoleConst() {
		$actual = RoleEnum::all();
		$expect = DataHelper::loadForTest(self::PACKAGE, __METHOD__, $actual);
		$this->tester->assertEquals($expect, $actual, true);
	}
	
	public function testGetPermissionConst() {
		$actual = PermissionEnum::all();
		$expect = DataHelper::loadForTest(self::PACKAGE, __METHOD__, $actual);
		$this->tester->assertEquals($expect, $actual, true);
	}
	
	public function testRemoveAllPermissions() {
		$itemService = $this->forgeDomain()->item;
		$itemService->removeAllPermissions();
		
		$actual = $itemService->getItems(Item::TYPE_PERMISSION);
		$this->assertCollection(__METHOD__, $actual);
	}
	
	public function testRemoveAllRoles() {
		$itemService = $this->forgeDomain()->item;
		$itemService->removeAllRoles();
		
		$actual = $itemService->getItems(Item::TYPE_ROLE);
		$this->assertCollection(__METHOD__, $actual);
	}
	
	private function assertCollection($method, $actual) {
		$actual = $this->prepareData($actual);
		
		$expect = DataHelper::loadForTest(self::PACKAGE, $method, $actual);
		$this->tester->assertEquals($expect, $actual, true);
	}
	
	private function assertEntity($method, Item $actual) {
		$actual = $this->prepareItem($actual);
		
		$expect = DataHelper::loadForTest(self::PACKAGE, $method, $actual);
		$this->tester->assertEquals($expect, $actual, true);
	}
	
	private function prepareItem($item) {
		$item = ArrayHelper::toArray($item);
		$item['createdAt'] = 1538227504;
		$item['updatedAt'] = 1538227504;
		return $item;
	}
	
	private function prepareData($actual) {
		$actual = ArrayHelper::toArray($actual);
		foreach($actual as &$item) {
			$item = $this->prepareItem($item);
		}
		return $actual;
	}
	
	private function forgeDomain() {
		$dir = Yii::getAlias(self::DATA_ALIAS);
		FileHelper::createDirectory($dir);
		
		$definition = [
			'class' => 'yii2lab\rbac\domain\Domain',
			'repositories' => [
				'rule' => [
					'ruleFile' => self::DATA_ALIAS . SL . 'rules.php',
				],
				'item' => [
					'itemFile' => self::DATA_ALIAS . SL . 'items.php',
				],
				'const' => [
					'driver' => Driver::FILE,
					'dirAlias' => self::DATA_ALIAS,
				],
			],
		];
		
		/** @var \yii2lab\rbac\domain\Domain $domainInstance */
		$domainInstance = DomainHelper::createDomain('rbac', $definition);
		return $domainInstance;
	}
	
}
