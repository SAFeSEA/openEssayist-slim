<?php
use Respect\Validation\Validator as v;

class UserController extends Controller
{
	public function me()
	{
		$this->render('user/dashboard');
	}

	public function tasks($id=-1)
	{
		/* @var $u Users */
		$u = Model::factory('Users')->find_one($this->user['id']);
		if ($u===false)
		{
			$this->app->flash("error", "Cannot find the user data");
			$this->redirect('me.home');
		}
		
		/* @var $g Group */
		$g = $u->group()->find_one();	
		if ($g===false)
		{
			$this->app->flash("error", "Cannot find the group data");
			$this->redirect('me.home');
		}
		
		if ($id===-1)
			$t = $g->tasks()->find_array();
		else 
		{
			$ap = $g->tasks()->find_one($id);
			if ($ap===false)
			{
				$this->app->flash("error", "Cannot find the task data");
				$this->app->notFound();
			}
			$t[] = $ap->as_array();
		}
		if ($id===-1)
			$this->app->flashNow("info", "This is the page for all your assignments");
		else
			$this->app->flashNow("info", "This is the page for your <b>" . $t[0]['name'] . "</b> assignment");
		
		$this->render('user/tasks',array(
				'group' => $g->as_array(),
				'tasks' => $t
		));
		
		var_dump($u->as_array(),$g->as_array());
		var_dump($t);
	}
	
	
	public function drafts($taskId,$dradtId=null)
	{
		$this->render('user/dashboard');
	}
	
	public function submitDraft($taskId)
	{
		$req = $this->app->request();
		
		
		if ($req && $req->isPost())
		{
			$post = $req->post();
			var_dump($post);
			
			try {
				$request = Requests::post('http://localhost:8062/api/analysis',
					array(),
					array('text' => $post["text"]),
					array('timeout' => 1));
				var_dump($request->status_code);
			}
			catch (Requests_Exception $e)
			{
				$this->app->flashNow("error", "Time out! Try again later");
				var_dump($e);
			}
			//$r= $this->app->urlFor('me.drafts',array("idt" => $taskId));
			//$this->redirect($r,false);
		}
		
		$this->render('user/draft.submit');
	}
}