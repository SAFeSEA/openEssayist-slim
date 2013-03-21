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

	public function setup()
	{
		/*
		 * ORM
		* initialize connection and database name
		*/
		$this->db = ORM::get_db();
		
		
		// Users Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `users` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `username` varchar(120) DEFAULT NULL,
			  `password` varchar(255) NOT NULL,
			  `name` varchar(180) DEFAULT NULL,
			  `email` varchar(220) DEFAULT NULL,
			  `ip_address` varchar(16) NOT NULL,
			  `active` int(11) DEFAULT '0',
			  `isadmin` int(11) DEFAULT '0',
			  PRIMARY KEY (`id`),
			  UNIQUE (`username`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
		
		$u = Model::factory('Users')->create();
		$u->name = "admin";
		$u->email = "nicolas.vanlabeke@open.ac.uk";
		$u->username = "admin";
		$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("admin1");
		$u->ip_address = $this->app->request()->getIp();
		$u->isadmin = 1;	
		try {
			$u->save();
		}
		catch (\PDOException  $e) {
			//$this->app->flashNow('error', $e->getMessage());
			//$this->app->redirect('config');
			//var_dump($e->getMessage()); 
		}
		
	}

	public function run()
	{
		$this->app->run();
	}
}

