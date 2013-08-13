<?php
namespace Slim\Extras\Middleware;

use \Slim\Extras\Middleware\StrongAuth;


/**
 * Redefinition of StrongAuth for admin role in user
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 * the configuration array for each 'security.urls' path contains an extra parameter, 'admin', to 
 * restrict access to these routes to user that have admin rights
 */
class StrongAuthAdmin extends StrongAuth
{

	public function call()
	{
		$req = $this->app->request();

		// Authentication Initialised
		switch ($this->config['auth.type']) {
			case 'form':
				$this->formAuthAdmin($this->auth, $req);
				break;
			default:
				$this->httpAuth($this->auth, $req);
				break;
		}
	}

	/**
	 * Form based authentication
	 * Added verification of user admin rights against admin parameter of 'security.urls' paths
	 *
	 * @param \Strong\Strong $auth
	 * @param object $req
	 */
	private function formAuthAdmin($auth, $req)
	{
		$app = $this->app;
		$config = $this->config;
		$this->app->hook('slim.before.router', function () use ($app, $auth, $req, $config) {
			$secured_urls = isset($config['security.urls']) && is_array($config['security.urls']) ? $config['security.urls'] : array();
			
			

			
			foreach ($secured_urls as $surl) {
				$patternAsRegex = $surl['path'];
				if (substr($surl['path'], -1) === '/') {
					$patternAsRegex = $patternAsRegex . '?';
				}
				$patternAsRegex = '@^' . $patternAsRegex . '$@';

				if (preg_match($patternAsRegex, $req->getPathInfo())) {
					
					// FIRST THING: if logged in, check for activation
					if (isset($config['consent.url']) && $auth->loggedIn())
					{
						$user = $auth->getUser();
						$isactive = $user['active']?: false;
						$path = $req->getPathInfo() == $config['consent.url'];
						
					
						if (!$isactive)
						{
							
							$app->flashNow("error", "You need to sign the consent form to access these pages");
							if (!$path) $app->redirect($app->request()->getRootUri() . $config['consent.url']);
						}
					}
					
					
					if (!$auth->loggedIn()) {
						// User is not logged in; redirect
						if ($req->getPath() !== $config['login.url']) {
							$app->flash("error", "You need to log in to access these pages");
							$app->redirect($app->request()->getRootUri() . $config['login.url']);
						}
					}
					else {
						// User is logged in; check for admin rights and path priviledge
						$user = $auth->getUser();
						$isuseradmin = $user['isadmin']?: false;
						$ispathadmin = $surl['admin']?: false;
						if ($ispathadmin && !$isuseradmin)
						{
							// route is admin-only but user is not admon; redirect
							$app->flash("error", "You don't have the rights to access these pages");
							$app->redirect($app->request()->getRootUri() . $config['login.url']);
						}
					}
				}
			}
		});

		$this->next->call();
	}

}