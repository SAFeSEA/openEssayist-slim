<?php

use Respect\Validation\Validator as v;

/**
 * Controller for the login operations
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class LoginController extends Controller {

	public function consent()
	{
		if ($this->app->request()->isPost()) 
		{
			var_dump($this->post());
			if ($this->post('action') == 'Submit' && 
				$this->post('consent') == 'Accept')
			{
				$u = Model::factory('Users')->find_one($this->user['id']);
				if ($u)
				{
					// Dirty way of dealing with auth data
					$_SESSION['auth_user']['active'] = 1;
					// update user record in database
					$u->active = 1;
					$u->save();
					
					$this->redirect('me.tasks');
					
				}
				// @todo Should NEVER go there but, just in case, need to add proper exception
			}
			$this->redirect('logout');
		}
		else
		{
			$this->render('pages/consent');
		}
	}
	/**
	 * @route "login"
	 */
	public function index()
	{
		if ($this->app->request()->isPost()) {
			if ($this->auth->login($this->post('username'), $this->post('password'))) {
				$this->app->flash('info', 'Your login was successfull');
				
				$user=$this->auth->getUser();
				//var_dump($this->auth->getUser());die();
				//if ($user['active']==1)
					$this->redirect('me.tasks');
				//else
				//	$this->redirect('consent');
			}
			else
				$this->app->flashNow('error', "Username or password is incorrect. Check your details again.");
		
		}
		$this->render('pages/login');
		
		
		/*if ($this->app->request()->isPost()) {
			try {
				$usernameValidator = v::alnum()
				->noWhitespace()
				->notEmpty();
				//->length(4,22);
				 
				$usernameValidator->check($this->post('username'));
	
				try {
					v::alnum()
					->notEmpty()
					//->length(3,11)
					->check($this->post('password'));
	
					if ($this->auth->login($this->post('username'), $this->post('password'))) {
						$this->app->flash('info', 'Your login was successfull');
						$this->redirect('home');
					}
					else 
						$this->app->flashNow('error', "Username or password is incorrect. Check your details again.");
				} catch (\InvalidArgumentException $e) {
					$this->app->flashNow('error', $e->setName('Password')->getMainMessage());
				}
			} catch (\InvalidArgumentException $e) {
				$this->app->flashNow('error', $e->setName('Username')->getMainMessage());
			}
		}
		$this->render('pages/login');*/
	}
	
	
	/**
	 * @route "logout"
	 */
	public function logout()
	{
		$this->app->flash('info', 'Come back sometime soon');
		$this->auth->logout(true);
		$this->redirect('home');
	}
	
}