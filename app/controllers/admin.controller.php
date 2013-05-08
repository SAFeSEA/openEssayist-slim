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
	
	public function reset()
	{
		$this->setup(true);
		$this->app->flash("error", "The database have been reset to default values.");
		$this->redirect('admin.home');
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
		$req = $this->app->request();
		$task = Model::factory('Task')->find_one($id);
		
		if ($req && $req->isPost())
		{
			$post = $req->post();
			$task->name = $post['name'];
			$task->assignment = $post['assignment'];
			$task->wordcount = $post['wordcount'];
			$task->deadline = $post['date'];
			$task->save();
		}
		
		$this->render('admin/task.edit',array('task' => $task));
	
	}
	
	public function showEssayData()
	{
		$draft = Model::factory('Draft')->find_one();
		$data = $draft->as_array();
		$analysis = $draft->getAnalysis(true);
		
		$this->render('admin/data.json',array(
				'keys' => array_keys($analysis),
				'json' => $analysis
		));
		var_dump($analysis);
		
	}
	
}