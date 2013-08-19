<?php
namespace Slim\Middleware;

class LoggerMiddleWare extends \Slim\Middleware 
{
	public function call()
	{
		$log = $this->app->getLog();
		$req = $this->app->request();
		
		$auth = \Strong\Strong::getInstance();
		$usr = $auth->getUser();
		
		$env = $this->app->environment();
		$path = $req->getPathInfo(); 
		if (!empty($env['QUERY_STRING']))
			$path = $path . "?" .  $env['QUERY_STRING'];
		
		
		
		$msg = '%method% | [%user% @ %IP%] | %message%';
		$message = str_replace(array(
				'%method%',
				'%user%',
				'%IP%',
				'%message%'
		), array(
				$req->getMethod(),
				$usr['username']?:"anon",
				$req->getIp(),
				$path
		), $msg);
		
		$log->info($message);
		
		$this->next->call();
	}	
}