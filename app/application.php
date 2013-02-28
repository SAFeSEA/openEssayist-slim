<?php

class Application {
	public $app;

	public function __construct(\Slim\Slim $slim = null)
	{
		$this->app = !empty($slim) ? $slim : \Slim\Slim::getInstance();

		/*
		 * ORM
		* initialize connection and database name
		*/
		//$this->db = ORM::get_db();
		//var_dump($this->db);
		//$this->setup();
	}

	public function setup()
	{

	}

	public function run()
	{
		$this->app->run();
	}
}