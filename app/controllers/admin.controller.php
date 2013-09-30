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
			foreach ($users as &$user)
			{
				/* @var $dr Draft */
				$drs = $user->drafts();
				//$dr = $drs->find_many();
				//if ($dr)var_dump($dr[0]->as_array());
				$user->drafts = $drs->count();
			}
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
			$task->code = $post['code'];
			$task->assignment = $post['assignment'];
			$task->wordcount = $post['wordcount'];
			$task->deadline = $post['date'];
			$task->isopen = ($post['isopen']=="Yes");
			$task->save();
			
			$this->app->flash("info", "The assignment has been saved successfully.");
			$r= $this->app->urlFor('admin.task.edit',array('taskid' => $id));
			$this->redirect($r,false);
			
		}
		else
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
	
	public function showFeedback()
	{
		$reports= Model::factory('Feedback')->order_by_desc('id')->find_many();
		
		foreach ($reports as &$feed)
		{
			$user = $feed->user()->find_one();
			
			$feed->user = $user;
			//var_dump($feed->as_array());
			//var_dump($user->as_array());
		}
		
		
		$this->render('admin/reports',array(
				'reports' => $reports
		));
	
	}	
	
	public function getLogs()
	{
		// read all log files in the logs directory
		$logfiles = glob('../.logs/*.log',GLOB_BRACE);
		
		$csvArr = array();
		foreach ($logfiles as $logfile)
		{
			$csvArr = array_merge($csvArr,
				$this->csv_to_array(file_get_contents($logfile)," | ",
						array("level","date","action","user","message")));
		}
		// Filter non-INFO events (usually ERROR)
		$csvArr = array_filter($csvArr,function(&$var)
		{
			//return ($var['level']=='INFO' &&  strpos($var['message'], '/me/draft/')!==FALSE);
			//return ($var['level']=='INFO' &&  strpos($var['action'], 'ACTION.LOGIN')!==FALSE);
			return ($var['level']=='INFO');
		});
		
		$json['items']=array();
		foreach ($csvArr as $var)
		{
			// fix bug with missing user identification in old logs
			if ($var['action'] == 'ACTION.LOGIN' && $var['message']==null)
			{
				$var['message'] = $var['user'];
				$var['user'] = '[admin @ 127.0.0.1]';
			}
			// get path of view, if relevant log event
			if ($var['action'] == 'GET' && strpos($var['message'], '/me/draft/')!==FALSE)
			{
				$keywords = preg_split("/[0-9]+/",$var['message']);
				$var['view'] = array_pop($keywords);
				
			}
				
			//extract username
			$keywords = preg_split("/[\[\@ \]]+/", $var['user'] );
			$var['username'] = $keywords[1];
			$json['items'][] = $var;
		}
			
		$response = $this->app->response();
		$response['Content-Type'] = 'application/x-javascript';
		$response['X-Powered-By'] = 'openEssayist';
		$response->status(200);
		$response->body(json_encode($json));
	}
	
	public function showLogs()
	{
		$this->render('admin/logs');
		
	}
	public function showUserLogs($user)
	{
		$this->render('admin/logs.user',array(
			'userlog' => $user
		));
	}
	
}