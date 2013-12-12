<?php


/**
 * Controller for Group-based content
 * @author "Nicolas Van Labeke (https://github.com/vanch3d)"
 *
 */
class GroupController extends Controller
{

	/**
	 * Make sure that access to Group pages are restricted to Group role only (isgroup)
	 */
	private function checkRole()
	{
		
		if ($this->user['isgroup']!=="1")
		{
			// route is admin-only but user is not admon; redirect
			$this->app->flash("error", "You don't have the rights to access these pages");
			$this->redirect('me.home');
		}
	}
	
	/**
	 * @route "/group/"
	 */
	public function index()
	{
		$this->checkRole();
		
		/* @var $u Users */
		$u = Model::factory('Users')->find_one($this->user['id']);
		
		/* @var $g Group */
		$g = $u->group()->find_one();
		
		$tasks = $g->tasks()->find_array();
		
		$all = $g->users()->count();
		$act = $g->users()->where_equal('active', true)->count();
		$allusers = $g->users()->where_equal('active', true)->find_many();
		
		$items = array();
		foreach ($allusers as $user)
		{
				$newitem = array();
				$newitem['username'] = $user->username;
				$newitem['userid'] = $user->id;
				
				$drs = $user->drafts()->find_many();
				foreach ($drs as $dr)
				{
					/* @var $dr Draft */
					$dg = $dr->getAnalysis();
					
					/* @var $dr Draft */
					$tk = $dr->task()->find_one();
					
					// format date for windows (excel!)
					$date = new DateTime($dr->date);
					$date = $date->format('Y-m-d\TH:i:s');
						
					
					$tt = array(
						'taskid' => $dr->task_id,
						'task' => 	$tk->name,
						'draftid' => $dr->id,
						'version' => $dr->version,
						'date' => $date,
						'name' =>$dr->name,
						'words' => $dg->se_stats->number_of_words
					);
					
					$newitem = array_merge($newitem,$tt);
					$items[] = $newitem;
				}
		}
		
		
		$this->render('group/dashboard',array(
				'group' => $g->as_array(),
				'tasks' => $tasks,
				'items' => $items,
				'metrics' => array(
					'users' => $all,
					'active' => $act,
					)
		));
	}
	
	/**
	 * @route "/group/tasks"
	 */
	public function showTasks()
	{
		$this->checkRole();
		
		/* @var $u Users */
		$u = Model::factory('Users')->find_one($this->user['id']);
		/* @var $g Group */
		$g = $u->group()->find_one();

		$tasks = $g->tasks()->find_array();
		
		$this->render('group/tasks',array(
				'group' => $g->as_array(),
				'tasks' => $tasks,
			));
	}
}