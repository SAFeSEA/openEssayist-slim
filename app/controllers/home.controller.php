<?php
use Respect\Validation\Validator as v;

/**
 * Controller for main routes to openEssayist
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class HomeController extends Controller
{
	/**
	 * @route "home"
	 */
	public function index()
	{
		$this->render('pages/welcome');
	}
	
	
	public function testRequest()
	{
		$request = Requests::get('http://localhost:8062/');
		$this->app->flash("info", $request->body);
		$this->redirect('home');
		$this->render('pages/welcome');
		var_dump($request->status_code);
		// int(200)
	
		var_dump($request->headers['content-type']);
		// string(31) "application/json; charset=utf-8"
	
		var_dump($request->body);
	}
	
	
}