<?php
namespace Slim\Middleware;

/**
 * Simple Slim middleware for logging requests and other client-side events
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class LoggerMiddleWare extends \Slim\Middleware 
{
	/**
	 * (non-PHPdoc)
	 * @see \Slim\Middleware::call()
	 */
	public function call()
	{
		$log = $this->app->getLog();
		$req = $this->app->request();
		$response = $this->app->response();
		
		//var_dump($response->isOk());die();
		
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
		
		$this->next->call();

		// Hack to prevent the logging of the logging event :-) 
		$hack= $this->app->urlFor("ajax.log.activity");
		if ($path==$hack) return;
		
		if ($response->isOk())
			$log->info($message);
		else
			$log->warn($message);
		
		
	}	
}