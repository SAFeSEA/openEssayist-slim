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
		//$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		
		
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
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
			");
			//var_dump("Table 'users' created");
		}
		catch (\PDOException $e)
		{
			//var_dump($e->errorInfo[2]);
			$this->app->flash("error", $e->errorInfo[2]);
			$this->app->flashNow("error", $e->errorInfo[2]);
			$this->app->flashKeep();
			//$this->app->flashKeep();
			//throw new Exception("FGFDGFG");
		}
		
		// Users Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `group` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(120) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
		//var_dump($ret);
		
		$gs = Model::factory('Group')->where('name', 'H810')->find_one();
		if ($gs===false)
		{
			/* @var $g Group */
			$gs = Model::factory('Group')->create();
			$gs->name = "H810";
			$gs->save();
			
		}
		
		//var_dump("Group:",$gs->as_array());
		
		
		$u = Model::factory('Users')->create();
		$u->name = "admin";
		$u->email = "nicolas.vanlabeke@open.ac.uk";
		$u->username = "admin";
		$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("admin1");
		$u->ip_address = $this->app->request()->getIp();
		$u->isadmin = 1;
		$u->group_id = $gs->id;
		
		try {
			$u->save();
		}
		catch (\PDOException  $e) {
			//$this->app->flashNow('error', $e->getMessage());
			//$this->app->redirect('config');
			//var_dump($e->getMessage()); 
		}
		
		$gs = Model::factory('Users')
			->where('name', 'admin')
			->find_many();
			
			//var_dump($gs[0]->as_array());
		$tt = $gs[0]->group()->find_one();
		//var_dump($tt->as_array());
	}

	public function run()
	{
		$this->app->run();
	}
}

