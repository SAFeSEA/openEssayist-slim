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
		// read all log files in the logs (or remotelogs!) directory
		$logfiles = glob('../remotelogs/*.log',GLOB_BRACE);
		
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
		foreach ($csvArr as $key=>$var)
		{
			// fix bug with missing user identification in old logs
			if ($var['action'] == 'ACTION.LOGIN' && $var['message']==null)
			{
				$nextevent = $csvArr[$key+1];
				$var['message'] = $var['user'];
				$var['user'] = ($nextevent)? $nextevent['user'] : '[admin @ 127.0.0.1]';
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
	
	public function getLogsCSV()
	{
		// read all log files in the logs (or remotelogs!) directory
		$logfiles = glob('../remotelogs/*.log',GLOB_BRACE);
	
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
		// identifier of "session", per users
		$session = array();
		// placeholder for last event, per user
		$lastevent = array();
		
		foreach ($csvArr as $key=>$var)
		{
			// filter out logactivity msgs
			if ($var['action'] == 'POST' && strpos($var['message'], '/tutor/logactivity')!==FALSE) continue;
			
			// fix bug with missing user identification in old logs
			if ($var['action'] == 'ACTION.LOGIN' && $var['message']==null)
			{
				$nextevent = $csvArr[$key+1];
				$var['message'] = $var['user'];
				$var['user'] = ($nextevent)? $nextevent['user'] : '[admin @ 127.0.0.1]';
			}
			// reformat user_agent
			$ua = null;
			if ($var['action'] == 'ACTION.LOGIN')
			{
				$msg = json_decode($var['message'],true);
				$var['message'] = $msg['user_agent'];
				$ua = $this->getUserAgent($var['message']);
			}
			
			//extract username and IP address
			$keywords = preg_split("/[\[\@ \]]+/", $var['user'] );
			$var['user'] = $keywords[1]."\t".$keywords[2];
			$username = $keywords[1];
			
			// build an incremental index for "session" (based on successive login per user)
			$var['SESSION']="";
			if ($session[$username])
			{
				$ses = $session[$username];
				if ($var['action'] == 'ACTION.LOGIN')
					$ses++;
				$session[$username]=$ses;
			
			}
			else 
				$session[$username] = 1;
			$var['SESSION'] =$session[$username];
				
			// format date for windows (excel!)
			$date = new DateTime($var['date']);
			$var['date'] = $date->format('Y/m/d H:i:s');
			
			// ASSUMPTION: ALL EVENTS ARE INITIALLY INSTANT
			$var['ENDDATE']=$var['date'];
			$var['DURATION']=0;
			
			// add information parsed from draft/task URLs (if applicable) 			
			$var['TASKID']="";
			$var['DRAFTID']="";
			$var['RES']="";
			// get path of view, if relevant log event
			if ($var['action'] == 'GET' && strpos($var['message'], '/me/draft/')!==FALSE)
			{
				preg_match("/(\/me\/.*\/)([0-9]+)(.*)/i",$var['message'],$keywords);
				//var_dump($var['message'],$keywords);
				if ($res)
				{
					$var['DRAFTID'] = $keywords[2];
					$var['RES'] = $keywords[3	];
				}
					
			}
			// get path of view, if relevant log event
			else if ($var['action'] == 'GET' && strpos($var['message'], '/me/essay/')!==FALSE)
			{
				$res = preg_match("/(\/me\/.*\/)([0-9]+)(.*)/i",$var['message'],$keywords);
				//var_dump($var['message'],$keywords);
				if ($res)
				{
					$var['TASKID'] = $keywords[2];
					$var['RES'] = $keywords[3];
				}
				
			}
						
			// add UA fields, if applicable
			$var['UA_BROWSER']="";
			$var['UA_OS']="";
			$var['UA_DEVICE']="";
			if ($ua)
			{
				$var['UA_BROWSER'] = $ua['ua_name'];
				$var['UA_OS'] = $ua['os_name'];
				$var['UA_DEVICE'] = $ua['device_type'];
			}
			
			// compute end-date and duration of last event
			if (in_array($var['action'],array("GET","POST")))
			{
				// check if GET/POST is ajax-based (ie still within current access)
				// event is instant and ignored for duration of last event
				if (preg_match("/(\/)(profile|ajax|admin\/data)/", $var['message']))
				{
					$var['ENDDATE']=$var['date'];
					$var['DURATION']=0;
				}
				else
				{
						
					if ($lastevent[$username])
					{
						$tt = $json['items'][$lastevent[$username]];
						
						// ASSUMPTION: if end of session (logout, login), last event of session is instant
						if (preg_match("/(\/)(logout)/", $tt['message']) ||
							$tt['SESSION']!=$var['SESSION'])
						{
							$tt['ENDDATE']=$tt['date'];
							$tt['DURATION']=0;
								
						}
						else 
						{
							$tt['ENDDATE']=$var['date'];
							$date1 = new DateTime($tt['date']);
							$date2 = new DateTime($tt['ENDDATE']);
							$diffInSeconds = $date2->getTimestamp() - $date1->getTimestamp();
							//var_dump($diffInSeconds);
							$tt['DURATION']=$diffInSeconds;
						}
						$json['items'][$lastevent[$username]] = $tt;
						$lastevent[$username] = count($json['items']);
					}
					else $lastevent[$username] = count($json['items']);
				}
			}
			else
			{
				// all other request
				$var['ENDDATE']=$var['date'];
				$var['DURATION']=0;
			}
				
		
			$json['items'][] = $var;
		}
		
		$str="";
		$headers= array(
				"LEVEL","DATE","ACTION", "USER","IP","MSG",
				"SESSION","ENDDATE","DURATION",
				"TASKID","DRAFTID","RES",
				"BROWSER","OS","DEVICE"
			);
		$str =$str."".join("\t", $headers)."\n";
		foreach ($json['items'] as $key=>$var)
		{
			$str =$str."".join("\t", $var)."\n";
		}
		
		//die();
		$response = $this->app->response();
		$response['Content-Type'] = 'text/tab-separated-values';
		//$response['Content-Type'] = 'text/plain';
		$response['X-Powered-By'] = 'openEssayist';
		$response->status(200);
		$response->body($str);
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