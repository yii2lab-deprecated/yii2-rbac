<?php

namespace tests\functional\v1\enums;

use PHPUnit\Framework\Constraint\IsType;
use yii2lab\misc\enums\BaseEnum;

class LoginEnum extends BaseEnum {
	
	const ID_ADMIN = 381949;
	const ID_USER = 381070;
	const ID_USER_2 = 381074;
	const ID_NOT_EXISTS = 1234567;
	
	const LOGIN_ADMIN = '77771111111';
	const LOGIN_NOT_EXISTS = '77771111118';
	
	//const TOKEN_ADMIN = '4f6bbd8ea39e34f2f2d432a961be2a6a';
	const TOKEN_NOT_INCORRECT = '5f6bbd8ea39e34f212d432a968be2abe';
	
	const PASSWORD_HASH = '$2y$13$Xt.Zo0sIeIPi6CvraoUB1eihsZO3pFXx4rcYGWL9jGRr0YybqCqdK';
	
	const PASSWORD = 'Wwwqqq111';
	const PASSWORD_INCORRECT = 'Wwwqqq222';
	
	private static $users = [
		self::ID_ADMIN => [
			'id' => 381949,
			'login' => '77771111111',
			'status' => 1,
			'roles' => [
				'rAdministrator',
			],
		],
		self::ID_USER => [
			'id' => 381070,
			'login' => '77751112233',
			'status' => 1,
			'roles' => [
				'rUnknownUser',
			],
		],
	];
	
	public static function getEntityFormat() {
		return [
			'id' => isType::TYPE_INT,
			'login' => isType::TYPE_STRING,
			'status' => isType::TYPE_INT,
			'roles' => isType::TYPE_ARRAY,
		];
	}
	
	public static function getUser($id, $attribute = null) {
		$user = self::$users[$id];
		if(empty($attribute)) {
			return $user;
		} else {
			return $user[$attribute];
		}
	}
	
}
