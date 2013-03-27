<?php
use Respect\Validation\Validator as v;

class UserController extends Controller
{
	public function me()
	{
		$this->render('user/dashboard');
	}

	/**
	 * 
	 * @param unknown $id
	 */
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
	
	/**
	 * 
	 * @param unknown $taskId
	 * @param string $dradtId
	 */
	public function drafts($taskId,$dradtId=null)
	{
		/* @var $u Users */
		$u = Model::factory('Users')->find_one($this->user['id']);
		if ($u===false)
		{
			$this->app->flash("error", "Cannot find the user data");
			$this->redirect('me.home');
		}
		
		$g = $u->drafts()->where_equal('task_id',$taskId)->find_array();
		/* @var $g Draft */
		var_dump($g);

		
		
				
		
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
					array('timeout' => 30));
				//var_dump($request->status_code);
				if ($request->status_code === 200)
				{
					//var_dump($request->body);
					
					$json = $request->body;
					$ret = json_decode($json,true);
					var_dump(array_keys($ret));
					var_dump($ret['ke_data']['bigram_keyphrases']);
					
					/* @var $draft Draft */
					$draft = Model::factory('Draft')->create();
					$draft->type = 0;
					$draft->analysis = $json;
					$draft->task_id = $taskId;
					$draft->users_id = $this->user['id'];
					$draft->save();
						
				}
			}
			catch (Requests_Exception $e)
			{
				$this->app->flashNow("error", "Time out! Try again later");
				var_dump($e);
			}
			catch (\PDOException  $e) 
			{
				$this->app->flashNow("error", "Time out! Try again later");
				var_dump($e);
				
			}
			//$r= $this->app->urlFor('me.drafts',array("idt" => $taskId));
			//$this->redirect($r,false);
		}
		
		$this->render('user/draft.submit');
	}
	
	public function showDraft($draft)
	{
		/* @var $u Users */
		$u = Model::factory('Users')->find_one($this->user['id']);
		if ($u===false)
		{
			$this->app->flash("error", "Cannot find the user data");
			$this->redirect('me.home');
		}
		
		/* @var $g Draft */
		$g = $u->drafts()->find_one($draft);
		if ($g===false)
		{
			$this->app->flash("error", "Cannot find the user data");
			$this->redirect('me.home');
		}
		$t = $g->as_array();
		
		$tttt= $g->getParasenttok();
		
		
		//$rr = json_decode($t['analysis']);
		//var_dump(array_keys($rr));
		$this->render('drafts/draft.show',array(
				'parasenttok' => $tttt
		));
	}
	
	public function showKeyword($draft)
	{
	}
	
}