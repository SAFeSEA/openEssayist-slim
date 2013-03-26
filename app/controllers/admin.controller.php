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
	
	public function allUsers()
	{
		$data = array();
		$groups = Model::factory('Group')->find_many();
		foreach ($groups as $gr)
		{
			$users = $gr->users()->find_many();
			$data[] = array(
					'group' => $gr,
					'users' => $users
			);
		}
		$this->render('admin/users.all',array('data' => $data));
	
	}
	
	public function allTasks()
	{
		$data = array();
		$groups = Model::factory('Group')->find_many();
		foreach ($groups as $gr)
		{
			$tasks = $gr->tasks()->find_many();
			$data[] = array(
					'group' => $gr,
					'tasks' => $tasks
			);
		}
		$this->render('admin/tasks.all',array('data' => $data));
			
	}

	public function editTask($id)
	{
		$this->render('admin/dashboard');
	
	}
}