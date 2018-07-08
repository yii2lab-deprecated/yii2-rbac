<?php

namespace yii2lab\rbac\domain\repositories\disc;

use Yii;
use yii\base\InvalidArgumentException;
use yii\rbac\Assignment;
use yii2lab\rbac\domain\repositories\base\BaseItemRepository;

class AssignmentRepository extends BaseItemRepository {
	
	/**
	 * @var string the path of the PHP script that contains the authorization assignments.
	 * This can be either a file path or a [path alias](guide:concept-aliases) to the file.
	 * Make sure this file is writable by the Web server process if the authorization needs to be changed online.
	 * @see loadFromFile()
	 * @see saveToFile()
	 */
	public $assignmentFile = '@app/rbac/assignments.php';
	
	/**
	 * @var array
	 */
	protected $assignments = []; // userId, itemName => assignment
	
	/**
	 * Initializes the application component.
	 * This method overrides parent implementation by loading the authorization data
	 * from PHP script.
	 */
	public function init()
	{
		parent::init();
		$this->assignmentFile = Yii::getAlias($this->assignmentFile);
		$this->load();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getAssignments($userId)
	{
		return isset($this->assignments[$userId]) ? $this->assignments[$userId] : [];
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function assign($role, $userId)
	{
		if (!isset($this->items[$role->name])) {
			throw new InvalidArgumentException("Unknown role '{$role->name}'.");
		} elseif (isset($this->assignments[$userId][$role->name])) {
			throw new InvalidArgumentException("Authorization item '{$role->name}' has already been assigned to user '$userId'.");
		}
		
		$this->assignments[$userId][$role->name] = new Assignment([
			'userId' => $userId,
			'roleName' => $role->name,
			'createdAt' => time(),
		]);
		$this->saveAssignments();
		
		return $this->assignments[$userId][$role->name];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function revoke($role, $userId)
	{
		if (isset($this->assignments[$userId][$role->name])) {
			unset($this->assignments[$userId][$role->name]);
			$this->saveAssignments();
			return true;
		}
		
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function revokeAll($userId)
	{
		if (isset($this->assignments[$userId]) && is_array($this->assignments[$userId])) {
			foreach ($this->assignments[$userId] as $itemName => $value) {
				unset($this->assignments[$userId][$itemName]);
			}
			$this->saveAssignments();
			return true;
		}
		
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getAssignment($roleName, $userId)
	{
		return isset($this->assignments[$userId][$roleName]) ? $this->assignments[$userId][$roleName] : null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeItem($item)
	{
		if (isset($this->items[$item->name])) {
			foreach ($this->assignments as &$assignments) {
				unset($assignments[$item->name]);
			}
			$this->saveAssignments();
			return true;
		}
		
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAll()
	{
		$this->assignments = [];
		$this->save();
	}
	
	/**
	 * Removes all auth items of the specified type.
	 * @param int $type the auth item type (either Item::TYPE_PERMISSION or Item::TYPE_ROLE)
	 */
	protected function removeAllItems($type)
	{
		$names = [];
		/*foreach ($this->items as $name => $item) {
			if ($item->type == $type) {
				unset($this->items[$name]);
				$names[$name] = true;
			}
		}
		if (empty($names)) {
			return;
		}*/
		
		foreach ($this->assignments as $i => $assignments) {
			foreach ($assignments as $n => $assignment) {
				if (isset($names[$assignment->roleName])) {
					unset($this->assignments[$i][$n]);
				}
			}
		}
		/*foreach ($this->children as $name => $children) {
			if (isset($names[$name])) {
				unset($this->children[$name]);
			} else {
				foreach ($children as $childName => $item) {
					if (isset($names[$childName])) {
						unset($children[$childName]);
					}
				}
				$this->children[$name] = $children;
			}
		}*/
		
		//$this->saveItems();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllAssignments()
	{
		$this->assignments = [];
		$this->saveAssignments();
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function updateItem($name, $item)
	{
		if ($name !== $item->name) {
			/*if (isset($this->items[$item->name])) {
				throw new InvalidArgumentException("Unable to change the item name. The name '{$item->name}' is already used by another item.");
			}
			
			// Remove old item in case of renaming
			unset($this->items[$name]);
			
			if (isset($this->children[$name])) {
				$this->children[$item->name] = $this->children[$name];
				unset($this->children[$name]);
			}
			foreach ($this->children as &$children) {
				if (isset($children[$name])) {
					$children[$item->name] = $children[$name];
					unset($children[$name]);
				}
			}*/
			foreach ($this->assignments as &$assignments) {
				if (isset($assignments[$name])) {
					$assignments[$item->name] = $assignments[$name];
					$assignments[$item->name]->roleName = $item->name;
					unset($assignments[$name]);
				}
			}
			$this->saveAssignments();
		}
		
		//$this->items[$item->name] = $item;
		
		//$this->saveItems();
		return true;
	}
	
	/**
	 * Saves authorization data into persistent storage.
	 */
	protected function save()
	{
		$this->saveAssignments();
	}
	
	/**
	 * Saves assignments data into persistent storage.
	 */
	protected function saveAssignments()
	{
		$assignmentData = [];
		foreach ($this->assignments as $userId => $assignments) {
			foreach ($assignments as $name => $assignment) {
				/* @var $assignment Assignment */
				$assignmentData[$userId][] = $assignment->roleName;
			}
		}
		$this->saveToFile($assignmentData, $this->assignmentFile);
	}
	
	
	
	
	
	
	
	
	
	/**
	 * Loads authorization data from persistent storage.
	 */
	protected function load()
	{
		$this->assignments = [];
		$assignments = $this->loadFromFile($this->assignmentFile);
		$assignmentsMtime = @filemtime($this->assignmentFile);
		
		foreach ($assignments as $userId => $roles) {
			foreach ($roles as $role) {
				$this->assignments[$userId][$role] = new Assignment([
					'userId' => $userId,
					'roleName' => $role,
					'createdAt' => $assignmentsMtime,
				]);
			}
		}
	}
	
	/*public function allAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $userId) {
			$roles = Yii::$app->user->identity->roles;
		} else {
			$userEntity = Yii::$domain->account->login->oneById($userId);
			$roles = $userEntity->roles;
		}
		return $this->forgeAssignments($userId, $roles);
	}

    public function allUserIdsByRole($role) {
        return [];
    }
	
	private function forgeAssignment($userId, $roleName) {
		$assignment = new Assignment([
			'userId' => $userId,
			'roleName' => $roleName,
			'createdAt' => 1486774821,
		]);
		return $assignment;
	}
	
	private function forgeAssignments($userId, $roleNames) {
		if(empty($roleNames)) {
			return [];
		}
		$assignments = [];
		foreach($roleNames as $roleName) {
			$assignments[$roleName] = $this->forgeAssignment($userId, $roleName);
		}
		return $assignments;
	}*/
	
}