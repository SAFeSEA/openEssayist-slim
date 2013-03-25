<?php
use Respect\Validation\Validator as v;

class AdminController extends Controller
{
	/**
	 * @route "admin/"
	 */
	public function index()
	{
		$this->render('admin/dashboard');
	
	}
}