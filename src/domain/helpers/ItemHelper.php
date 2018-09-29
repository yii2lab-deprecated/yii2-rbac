<?php

namespace yii2lab\rbac\domain\helpers;

use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\base\InvalidArgumentException;

class ItemHelper {
	
	public static function filterByType($items, $type) {
		$itemsResult = [];
		
		foreach ($items as $name => $item) {
			/* @var $item Item */
			if ($item->type == $type) {
				$itemsResult[$name] = $item;
			}
		}
		
		return $itemsResult;
	}
	
	public static function getItemsNameByType(&$items, $type) {
		$names = [];
		foreach ($items as $name => $item) {
			if ($item->type == $type) {
				unset($items[$name]);
				$names[$name] = true;
			}
		}
		return $names;
	}
	
	public static function removeByNames($items, $names) {
		foreach ($items as $name => $children) {
			if (isset($names[$name])) {
				unset($items[$name]);
			} else {
				foreach ($children as $childName => $item) {
					if (isset($names[$childName])) {
						unset($children[$childName]);
					}
				}
				$items[$name] = $children;
			}
		}
		return $items;
	}
	
	public static function updateChildren($name, $item, $children) {
		if ($name !== $item->name) {
			if (isset($children[$name])) {
				$children[$item->name] = $children[$name];
				unset($children[$name]);
			}
			foreach ($children as &$childrenItem) {
				if (isset($childrenItem[$name])) {
					$childrenItem[$item->name] = $childrenItem[$name];
					unset($childrenItem[$name]);
				}
			}
		}
		
		return $children;
	}
	
	public static function updateItems($name, $item, $items/*, $childrens*/) {
		if ($name !== $item->name) {
			if (isset($items[$item->name])) {
				throw new InvalidArgumentException("Unable to change the item name. The name '{$item->name}' is already used by another item.");
			}
			
			// Remove old item in case of renaming
			unset($items[$name]);
		}
		
		$items[$item->name] = $item;
		
		return $items;
	}
	
	public static function lists2tree($items, $children) {
		$tree = [];
		foreach ($items as $name => $item) {
			/* @var $item Item */
			$tree[$name] = array_filter(
				[
					'type' => $item->type,
					'description' => $item->description,
					'ruleName' => $item->ruleName,
					'data' => $item->data,
				]
			);
			if (isset($children[$name])) {
				foreach ($children[$name] as $child) {
					/* @var $child Item */
					$tree[$name]['children'][] = $child->name;
				}
			}
		}
		return $tree;
	}
	
	public static function getChildrenRecursive($name, &$result, $children)
	{
		if (isset($children[$name])) {
			foreach ($children[$name] as $child) {
				$result[$child->name] = true;
				self::getChildrenRecursive($child->name, $result, $children);
			}
		}
	}
	
	public static function allPermissionsByAssignments($items, $assignments) {
		$permissions = [];
		foreach ($assignments as $name => $assignment) {
			$permission = $items[$assignment->roleName];
			if ($permission->type === Item::TYPE_PERMISSION) {
				$permissions[$name] = $permission;
			}
		}
		return $permissions;
	}
	
	public static function tree2items($tree, $time) {
		$items = [];
		foreach ($tree as $name => $item) {
			$class = $item['type'] == Item::TYPE_PERMISSION ? Permission::class : Role::class;
			
			$items[$name] = new $class([
				'name' => $name,
				'description' => isset($item['description']) ? $item['description'] : null,
				'ruleName' => isset($item['ruleName']) ? $item['ruleName'] : null,
				'data' => isset($item['data']) ? $item['data'] : null,
				'createdAt' => $time,
				'updatedAt' => $time,
			]);
		}
		return $items;
	}
	
	public static function tree2children($tree, $items) {
		$children = [];
		foreach ($tree as $name => $item) {
			if (isset($item['children'])) {
				foreach ($item['children'] as $childName) {
					if (isset($items[$childName])) {
						$children[$name][$childName] = $items[$childName];
					}
				}
			}
		}
		return $children;
	}
	
}
