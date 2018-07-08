<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
* Handles the creation of table `user_assignment`.
*/
class m171122_135428_create_user_assignment_table extends Migration
{
	public $table = '{{%user_assignment}}';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'user_id' => $this->integer(),
			'item_name' => $this->string(64),
		];

	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['user_id', 'item_name']);
	}

}
