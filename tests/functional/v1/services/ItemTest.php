<?php

namespace tests\functional\v1\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2lab\test\helpers\DataHelper;
use yii2lab\test\Test\Unit;

class ItemTest extends Unit {
	
	const PACKAGE = 'yii2lab/yii2-rbac';
	const DATA_ALIAS = '@common/runtime/test/rbac';
	
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
			],
		];
		
		/** @var \yii2lab\rbac\domain\Domain $domainInstance */
		$domainInstance = DomainHelper::createDomain('rbac', $definition);
		return $domainInstance;
	}
	
}
