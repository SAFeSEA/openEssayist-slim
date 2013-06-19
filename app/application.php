<?php
/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class Application {
	
	/**
	 * 
	 * @var \Slim\Slim
	 */	
	public $app;

	/**
	 * 
	 * @var PDO
	 */
	public $db;
	
	public function __construct(\Slim\Slim $slim = null)
	{
		$this->app = !empty($slim) ? $slim : \Slim\Slim::getInstance();
		if (!empty($slim)) $this->setup();
	}

	public function setup($reset=false)
	{
		
		/*// Create .logs dir if it does not exists
		$logWriter = $this->app->config('log.writer');
		if (!is_dir('../.logs')) {
			mkdir('../.logs');
		}
		$this->db = ORM::get_db();
		
		if ($reset)
		{
			$this->db->exec('DROP DATABASE `openessayist`');
			$this->db->exec('CREATE DATABASE `openessayist`');
		}
		// Create Users Table
		try {
			$ret = $this->db->exec("
				CREATE TABLE `users` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `username` varchar(120) DEFAULT NULL,
				  `password` varchar(255) NOT NULL,
				  `name` varchar(180) DEFAULT NULL,
				  `email` varchar(220) DEFAULT NULL,
				  `ip_address` varchar(16) NOT NULL,
				  `group_id` int(11) NOT NULL,
				  `active` int(11) DEFAULT '0',
				  `isadmin` int(11) DEFAULT '0',
				  PRIMARY KEY (`id`),
				  UNIQUE (`username`)
				) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
			");
			//
		}
		catch (\PDOException $e)
		{
			// Table exists, assume all the rest is fine
			return;
		}

		// Group Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `group` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(120) DEFAULT NULL,
			  `description` TEXT,
			  PRIMARY KEY (`id`),
			  UNIQUE (`name`)
			) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");

		// Task Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `task` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(120) DEFAULT NULL,
				`assignment` TEXT,
				`deadline` DATE,
				`wordcount` int(11) DEFAULT '0',
				`isopen` int(11) DEFAULT '0',
				`group_id` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			)  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
		//var_dump("Table 'task' created");
		
		// Draft Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `draft` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`task_id` int(11) NOT NULL,
				`users_id` int(11) NOT NULL,
				`type` int(11) DEFAULT '0',
				`processed` int(11) DEFAULT '1',
				`analysis` LONGBLOB,
				`date` DATETIME,
				PRIMARY KEY (`id`)
			)  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");

		// Draft Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `kwcategory` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`draft_id` int(11) NOT NULL,
				`category` LONGBLOB,
				PRIMARY KEY (`id`)
			)  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
		
		$iUser=0;
		//var_dump($ret);
		//$idx = $this->createGroup("H810");
		//$this->createUser($iUser++,$idx);
		
		//$this->createTasks(1,$idx);
		//$this->createTasks(2,$idx);
		//$this->createTasks(3,$idx);
		
		$idx = $this->createGroup("UserTesting H810");
		$this->createUser($iUser++,$idx,true);
		$this->createUser($iUser++,$idx);
		$this->createUser($iUser++,$idx);
		$this->createUser($iUser++,$idx);
		$this->createUser($iUser++,$idx);
		$this->createUser($iUser++,$idx);
		$this->createTasks(1,$idx);
		
		//$idx = $this->createGroup("H100");
		//$this->createUser($iUser++,$idx);
		//$this->createTasks(1,$idx);*/
		$this->createUser(6,1);
	}
	
	private function createUser($id,$gid,$isadmin=false)
	{
		$gs = Model::factory('Group')->find_many();
		
		$u = Model::factory('Users')->create();
		$u->name = ($isadmin) ? "admin" : "user".$id;
		$u->email = "nicolas.vanlabeke@open.ac.uk";
		$u->username = $u->name;
		$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword($u->name . "1");
		$u->ip_address = $this->app->request()->getIp();
		$u->isadmin = ($isadmin)? 1:0;
		$u->group_id = $gid;
		
		try {
			$u->save();
		}
		catch (\PDOException  $e) {
			//var_dump($e->getMessage());
		}
		
	}
	
	private function createGroup($name)
	{
		$gs = Model::factory('Group')->create();
		$gs->name = $name;
		try {
			$gs->save();
		}
		catch (\PDOException  $e) {}
		return $gs->id;
	}
	
	private function createTasks($id,$gid)
	{
		$gs = Model::factory('Group')->find_many();
		
		/* @var $task Task */
		$task = Model::factory('Task')->create();
		$task->name = "TMA0".$id;
		$task->assignment = "";
		$task->group_id = $gid;
		try {
			$task->save();
		}
		catch (\PDOException  $e) {}
	}

	public function run()
	{
		$this->app->run();
	}
}

