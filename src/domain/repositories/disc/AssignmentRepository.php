<?php

namespace yii2lab\rbac\domain\repositories\disc;

use yii\base\InvalidArgumentException;
use yii\rbac\Assignment;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\rbac\domain\helpers\DiscHelper;
use yii2lab\rbac\domain\interfaces\repositories\AssignmentInterface;

/**
 * Class AssignmentRepository
 *
 * @package yii2lab\rbac\domain\repositories\disc
 *
 * @property \yii2lab\rbac\domain\Domain $domain
 */
class AssignmentRepository extends BaseRepository implements AssignmentInterface {
	
	/**
	 * @var string the path of the PHP script that contains the authorization assignments.
	 * This can be either a file path or a [path alias](guide:concept-aliases) to the file.
	 * Make sure this file is writable by the Web server process if the authorization needs to be changed online.
	 */
	public $assignmentFile = '@app/rbac/assignments.php';
	
	/**
	 * @var array
	 */
	protected $assignments = []; // userId, itemName => assignment
	
	public function init()
	{
		parent::init();
		//$this->assignmentFile = Yii::getAlias($this->assignmentFile);
		$this->load();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getAssignments($userId)
	{
		return isset($this->assignments[$userId]) ? $this->assignments[$userId] : [];
	}
	
	public function getUserIdsByRole($roleName) {
		// TODO: Implement getUserIdsByRole() method.
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
	
	public function revokeAllByItemName($itemName)
	{
		$result = false;
		foreach($this->assignments as $userId => $itemName1) {
			if($itemName == $itemName1) {
				unset($this->assignments[$userId][$itemName]);
				$result = true;
			}
		}
		$this->saveAssignments();
		return $result;
	}
	
	public function updateRoleName($itemName, $newItemName)
	{
		$result = false;
		foreach($this->assignments as $userId => $itemName1) {
			if($itemName == $itemName1) {
				/** @var Assignment $assignmentEntity */
				$assignmentEntity = $this->assignments[$userId][$itemName];
				$assignmentEntity->roleName = $newItemName;
				unset($this->assignments[$userId][$itemName]);
				$this->assignments[$userId][$newItemName] = $assignmentEntity;
				$result = true;
			}
		}
		$this->saveAssignments();
		return $result;
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
	/*public function removeItem($item)
	{
		if (isset($this->items[$item->name])) {
			foreach ($this->assignments as &$assignments) {
				unset($assignments[$item->name]);
			}
			$this->saveAssignments();
			return true;
		}
		
		return false;
	}*/
	
	/**
	 * {@inheritdoc}
	 */
	/*public function removeAll()
	{
		$this->assignments = [];
		$this->save();
	}*/
	
	/**
	 * {@inheritdoc}
	 */
	public function removeAllAssignments()
	{
		$this->assignments = [];
		$this->saveAssignments();
	}
	
	public function allRoleNamesByUserId($userId) {
		// TODO: Implement allRoleNamesByUserId() method.
	}
	
	public function isHasRole($userId, $role) {
		// TODO: Implement isHasRole() method.
	}
	
	public function allByRole($role) {
		// TODO: Implement allByRole() method.
	}
	
	/**
	 * Saves authorization data into persistent storage.
	 */
	/*protected function save()
	{
		$this->saveAssignments();
	}*/
	
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
		DiscHelper::saveToFile($assignmentData, $this->assignmentFile);
		
		$this->domain->const->generateAll();
	}
	
	
	
	
	
	
	
	
	
	/**
	 * Loads authorization data from persistent storage.
	 */
	protected function load()
	{
		$this->assignments = [];
		$assignments = DiscHelper::loadFromFile($this->assignmentFile);
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
	
	/*public function getAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $userId) {
			$roles = Yii::$app->user->identity->roles;
		} else {
			$userEntity = \App::$domain->account->login->oneById($userId);
			$roles = $userEntity->roles;
		}
		return $this->forgeAssignments($userId, $roles);
	}

    public function getUserIdsByRole($role) {
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