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
		//$log = $this->app->getLog();
		//$log->debug("SETUP PROCEDURE CALLED");
		
		// Create .logs dir if it does not exists
		if (!is_dir('../.logs')) {
			mkdir('../.logs');
		}
		
		// check DB
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
				  `isdemo` int(11) DEFAULT '0',
				  PRIMARY KEY (`id`),
				  UNIQUE (`username`)
				) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
			");
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
			  `code` varchar(120) DEFAULT NULL,
			  `url` varchar(255) DEFAULT NULL,
			  `email` varchar(220) DEFAULT NULL,
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
			    `code` varchar(120) DEFAULT NULL,
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
				`version` int(11) NOT NULL DEFAULT '1',
				`name` varchar(120) DEFAULT NULL,
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
		
		// Notes Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `note` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`users_id` int(11) NOT NULL,
				`notes` LONGBLOB,
				PRIMARY KEY (`id`)
			)  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
		
		$exist = file_exists('../app/localconfig.php');
		if($exist)
		{
			//$log->debug("LOCAL SETUP INVOKED");
			include '../app/localconfig.php';
			$conf = new OpenEssayistConfigurator();
			$conf->setupDB();
		}
	}
	

	public function run()
	{
		$this->app->run();
	}
}

