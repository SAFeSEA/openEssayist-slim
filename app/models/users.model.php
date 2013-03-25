<?php

/**
 * Default model for users
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class Users extends Model {
	/**
	 * @key		Users/group_id
	 * @return	ORMWrapper
	 */
	public function group() {
		return $this->belongs_to('Group');
	}
	
	/**
	 * @key		Draft/users_id
	 * @return 	ORMWrapper
	 */
	public function drafts() {
		return $this->has_many('Draft');
	}
	
}

class Group extends Model {
	/**
	 * 
	 * @key		Users/group_id
	 * @return 	ORMWrapper
	 */
	public function users() {
		return $this->has_many('Users');
	}
	
	/**
	 * @key		Task/group_id
	 * @return ORMWrapper
	 */
	public function tasks() {
		return $this->has_many('Task');
	}
	
}

class Task extends Model {
	/**
	 * @key		Draft/task_id
	 * @return 	ORMWrapper
	 */
	public function drafts() {
		return $this->has_many('Draft'); 
	}
	
	/**
	 * @key		Task/group_id
	 * @return 	Group
	 * @see 	ORMWrapper
	 */
	public function group() {
		return $this->belongs_to('Group');
	}
	
}

class Draft extends Model {
	/**
	 * 
	 * @key		Draft/task_id
	 * @return 	Task
	 * @see 	ORMWrapper
	 */
	public function task() {
		return $this->belongs_to('Task');
	}

	/**
	 * @key		Draft/user_id
	 * @return 	Users
	 * @see 	ORMWrapper
	 */
	public function user() {
		return $this->belongs_to('Users');
	}
	
}