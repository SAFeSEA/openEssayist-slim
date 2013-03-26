<?php
use Respect\Validation\Validator as v;

class UserController extends Controller
{
	public function me()
	{
		$this->render('user/dashboard');
	}

	public function task($id=null)
	{
		if (empty($id))
			$this->app->flashNow("info", "This is the page for all your assignments");
		else
			$this->app->flashNow("info", "This is the page for your <b>" . $id . "</b> assignment");
		$this->render('user/dashboard');
	}
	
}