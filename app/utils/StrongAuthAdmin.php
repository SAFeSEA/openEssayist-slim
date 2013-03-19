<?php
namespace Slim\Extras\Middleware;

use \Slim\Extras\Middleware\StrongAuth;


/**
 * Redefinition of StrongAuth for admin role in user
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
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
					if (!$auth->loggedIn()) {
						if ($req->getPath() !== $config['login.url']) {
							$app->redirect($config['login.url']);
						}
					}
				}
			}
		});

			$this->next->call();
	}

}