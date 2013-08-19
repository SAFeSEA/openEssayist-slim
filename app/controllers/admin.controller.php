<?php
use Respect\Validation\Validator as v;

/**
 * 
 * @author "Nicolas Van Labeke (https://github.com/vanch3d)"
 *
 */
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
			$task->isopen = ($post['isopen']=="Yes");
			$task->save();
		}
		
		$this->render('admin/task.edit',array('task' => $task));
	
	}
	
	public function showEssayData()
	{
		$drafts = Model::factory('Draft')->order_by_desc('id')->find_many();
		
		$analysis = $drafts[0]->getAnalysis(true);
		ksort($analysis);

		$analysis['se_sample_graph'] = json_decode($analysis['se_sample_graph'],true);
		$analysis['ke_sample_graph'] = json_decode($analysis['ke_sample_graph'],true);
		//var_dump($analysis['se_sample_graph']);die();
		
		$this->render('admin/data.json',array(
				'keys' => array_keys($analysis),
				'json' => $analysis
		));
		
		
	}
	
	public function showHistory()
	{
		$this->render('admin/history',array(
		));
		
	}
	
	
}