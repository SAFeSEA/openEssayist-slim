<?php


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
			$newusers = array();
			$users = $gr->users()->find_many();
			
			foreach ($users as $user)
			{
				/* @var $dr Draft */
				$drs = $user->drafts();
				$tt = $user->as_array();
				$tt['drafts'] = $drs->count();
				$newusers[]=$tt;
			}
			
			$data[] = array(
					'group' => $gr->as_array(),
					'users' => $newusers
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
			$tasks = $gr->tasks()->find_array();
			
			$data[] = array(
					'group' => $gr->as_array(),
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
		{
			$this->render('admin/task.edit',array('task' => $task->as_array()));
		}
		
	
	}
	
	/**
	 * Very basic password generator
	 * @return string
	 */
	private function generatePWD()
	{
		$letters="abcdefghijklmnopqrstuvwxyz";
		$numbers ="0123456789";
		
		$randomstring = '';
		for ($i=0;$i<1;$i++)
		{
			$rlet = rand(0,strlen($letters));
			$randomstring = $randomstring . $letters[$rlet];
		}
		for ($i=0;$i<2;$i++)
		{
			$rlet = rand(0,strlen($numbers));
			$randomstring = $randomstring . $numbers[$rlet];
		}
		for ($i=0;$i<3;$i++)
		{
			$rlet = rand(0,strlen($letters));
			$randomstring = $randomstring . $letters[$rlet];
		}
		for ($i=0;$i<2;$i++)
		{
			$rlet = rand(0,strlen($numbers));
			$randomstring = $randomstring . $numbers[$rlet];
		}
		
		return $randomstring;
	}

	
	public function addUsersToGroup($id=null,$nb=null,$pattern=null)
	{
		$response = $this->app->response();
		$req = $this->app->request();
		if ($req && $req->isPost())
		{
			$post = $req->post();
				
			$id = $post['id'];
			$nb = $post['nb'];
			$pattern = $post['pattern'];
		}
		
		/* @var $group Group */
		$group = Model::factory('Group')->find_one($id);
		$users = $group->users()->find_array();
		
		// get a list of username "stubs", with their last ID
		$stubs=array();
		foreach ($users as $u){
			
			$result = preg_split('/(\d+)/', $u['username'],-1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			$stubs[$result[0]]=intval($result[1]);
		}
		
		
		$nb = (intval($nb))?:20;
		
		$tmp = array_keys($stubs);
		$pattern = ($pattern)?:$tmp[0];
		$lastid = intval($stubs[$pattern]);
		
		$mydata=array();
		$mydata['groupname'] = $group->name;
		$mydata['groupid'] = $id;
		$mydata['countall'] = count($users);
		$mydata['added'] = $nb;
		$mydata['stub'] = $pattern;
		$mydata['lastuser'] = $lastid;
		for ($i=0;$i<$nb;$i++)
		{
			$newuser = array();
			$ii = str_pad($lastid+$i+1, 3, '0', STR_PAD_LEFT);
			$username = $pattern . $ii;
			$pwd = $this->generatePWD();
			
			$u = Model::factory('Users')->create();
			$u->name = $username;
			$u->username = $username;
			$u->active = 0;
			$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword($pwd);
			$u->ip_address = $this->app->request()->getIp();
			$u->group_id = $id;
			//$u->email = $user['Email'];
			try {
				$u->save();
				$newuser['username'] = $username;
				$newuser['pwd'] = $pwd;
				$mydata['users'][] = $newuser;
			}
			catch (\PDOException  $e) {
				//var_dump($e->getMessage());
			}
			
		}
		
		
		$response->status(200);
		$response['Content-Type'] = 'application/json';
		$response['X-Powered-By'] = 'openEssayist';
		
		$response->body($this->indent(json_encode($mydata)));
		
	}
	
	public function showEssayData()
	{
		$drafts = Model::factory('Draft')->order_by_desc('id')->find_many();
		
		$analysis = $drafts[0]->getAnalysis(true);
		ksort($analysis);

		$analysis['se_sample_graph'] = json_decode($analysis['se_sample_graph'],true);
		$analysis['ke_sample_graph'] = json_decode($analysis['ke_sample_graph'],true);
		
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
		
		$data = array();
		foreach ($reports as $feed)
		{
			$user = $feed->user()->find_one();
			$newrep = $feed->as_array();
			$newrep['user'] = $user->as_array();
			
			$data[]=$newrep;
		}
		
		
		$this->render('admin/reports',array(
				'reports' => $data
		));
	
	}	
	
	public function getLogs()
	{
		// read all log files in the logs (or remotelogs!) directory
		$path = $GLOBALS['db'][$GLOBALS['activeGroup']]['logdir'];
		$logfiles = glob($path.'/*.log',GLOB_BRACE);
		
		
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
			//return ($var['level']=='INFO');
			return ($var['level']=='INFO' || $var['level']=='WARN');
			
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

	public function getContentExcel()
	{
		// get all drafts
		$drafts = Model::factory('Draft')->find_many();
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Nicolas Van Labeke")
			->setLastModifiedBy("Nicolas Van Labeke")
			->setTitle("H810 content dump")
			->setSubject("OpenEssayist study")
			->setDescription("")
			->setKeywords("openEssayist h810")
			->setCategory("openEssayist");
		
		// create the overview info
		$objPHPExcel->setActiveSheetIndex(0)
			->setTitle('Overview');

		// create the sentences info
		$objPHPExcel->createSheet()->setTitle('Sentences');
		
		// merge the keywords
		$objPHPExcel->createSheet()
			->setTitle('Keywords');
		
		$keywords = array();
		$stats = array();
		$sentences = array();

		//create headers
		$stat = array(
				"USER",
				"TASKID",
				"DRAFTID",
				'avfreqsum',
				'sum_freq_kls_in_ass_q_long',
				'sum_freq_kls_in_ass_q_short',
				'sum_freq_kls_in_tb_index',
				'WORDLIMIT',
				'paras',
				'len_body',
				'len_headings',
				'all_sents',
				'countTrueSent',
				'number_of_words',
				'countAvSentLen',
				'countAssQSent',
				'countTitleSent');
		$stats[] = $stat;
		
		$sent = array();
		$sent["user"] = "USER";
		$sent["taskid"] = "TASKID";
		$sent["draftid"] = "DRAFTID";
		$sent["pid"] = "PAR";
		$sent["id"] = "SENT";
		$sent["tag"] = "TAG";
		$sent["rank"] = "RANK";
		$sent["text"] = "TEXT";
		$sentences[] = $sent;
		
		$keyword = array();
		$keyword["user"] ="USER";
		$keyword["draftid"] ="TASKID";
		$keyword["id"] ="DRAFTID";
		$keyword["source"] ="NGRAM";
		$keyword["count"] = "COUNT";
		$keyword["score"] = "SCORE";
		$keyword = array_merge($keyword,array("D0","D1","D2","D3","D4","D5","D6","D7","D8","D9"));
		$keywords[] = $keyword;
		
		foreach ($drafts as $key =>$draft)
		{
			$r = $draft->getAnalysis();
			$ngrams = $r->nvl_data->keywords;
			$user = $draft->user()->find_one();
			$tsk = $draft->task()->find_one();
			
			
			if (strncmp($user->username, "user", strlen("user")) != 0) continue;
			
			$stat = array();
			$stat["user"] = $user->username;
			$stat["taskid"] = $draft->task_id;
			$stat["draftid"] = $draft->id;
			$stat = array_merge($stat,
					(array)$r->ke_stats,
					array("wordlimit"=>$tsk->wordcount),
					(array)$r->se_stats
			);
			$stats[]= $stat;
			
			foreach ($ngrams as $h)
			{	
				$keyword = array();
				$keyword["user"] = $user->username;
				$keyword["taskid"] = $draft->task_id;
				$keyword["draftid"] = $draft->id;
				$keyword["source"] = $h->source[0];
				$keyword["count"] = $h->count;
				$keyword["score"] = $h->score[0];
				$keyword = array_merge($keyword,$h->trend);
				
				
				$keywords[] = $keyword;
				
			}
			
			$para= $r->se_data->se_parasenttok;
			foreach ($para as $key=> $p)
			{
				$sent = array();
				foreach ($p as $s){
					$sent["user"] = $user->username;
					$sent["taskid"] = $draft->task_id;
					$sent["draftid"] = $draft->id;
					$sent["pid"] = $key;
					$sent["id"] = $s->id;
					$sent["tag"] = $s->tag;
					$sent["rank"] = $s->rank;
					$sent["text"] = $s->text;
					$sentences[] = $sent;
				}
			}
		}
			
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->fromArray($sentences, NULL, 'A1',true);
		
		
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->fromArray($keywords, NULL, 'A1',true);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->fromArray($stats, NULL, 'A1',true);
		
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$response = $this->app->response();
		$response['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8';
		$response['X-Powered-By'] = 'openEssayist';
		$response->status(200);
		//$response->body($str);
		$objWriter->save("php://output");
		
	}

	public function getLogsCSV($format=null)
	{
		$path = $GLOBALS['db'][$GLOBALS['activeGroup']]['logdir'];
		$logfiles = glob($path.'/*.log',GLOB_BRACE);
		//var_dump($logfiles);die();
		
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
			return ($var['level']=='INFO' || $var['level']=='WARN');
			//return ($var['level']!='INFO');
		});
	
		$json['items']=array();
		// identifier of "session", per users
		$session = array();
		// placeholder for last event, per user
		$lastevent = array();
		// placeholder for last submit, per user
		$lastsubmit = array();
		$draftlist = array();
		$taskdraftlist = array();
		$drafttasklist = array();
		
		// get all drafts
		$drafts = Model::factory('Draft')->find_many();
		foreach ($drafts as $key =>$draft)
		{
			$user = $draft->user()->find_one();
			$tsk = $draft->task()->find_one();
			if (!$draftlist[$user->username])
				$draftlist[$user->username] = array();
			$draftlist[$user->username][]=$draft->id;
			if (!$taskdraftlist[$draft->task_id])
				$taskdraftlist[$draft->task_id] = array();
			$taskdraftlist[$draft->task_id][]=$draft->id;
			$drafttasklist[$draft->id]=$draft->task_id;
				
		}
		
		
		foreach ($csvArr as $key=>$var)
		{
			// filter out logactivity msgs
			if ($var['action'] == 'POST' && strpos($var['message'], '/tutor/logactivity')!==FALSE) continue;
			
			/*
			// fix bug with missing user identification in old logs
			if ($var['action'] == 'ACTION.LOGIN' && $var['message']==null)
			{
				$nextevent = $csvArr[$key+1];
				$var['message'] = $var['user'];
				$var['user'] = ($nextevent)? $nextevent['user'] : '[admin @ 127.0.0.1]';
			}*/
			
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
			unset($var['user']);
			$username = $var['user'] = $keywords[1];
			$var['ip'] = $keywords[2];
			
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
			$var['BASE']="";
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
					$var['BASE']= "DRAFT";
					$var['TASKID']=$drafttasklist[$keywords[2]];
					$var['DRAFTID'] = $keywords[2];
					$var['RES'] = $keywords[3];
				}
					
			}
			// get path of view, if relevant log event
			else if ($var['action'] == 'GET' && strpos($var['message'], '/me/essay/')!==FALSE)
			{
				$res = preg_match("/(\/me\/.*\/)([0-9]+)(.*)/i",$var['message'],$keywords);
				//var_dump($var['message'],$keywords);
				if ($res)
				{
					$var['BASE']= "TASK";
					$var['TASKID'] = $keywords[2];
					$var['RES'] = $keywords[3];
				}
				
			}
			// get path of view, if relevant log event
			else if ($var['action'] == 'POST' && strpos($var['message'], '/submit/')!==FALSE)
			{
				if (!$lastsubmit[$username])
					$lastsubmit[$username] = 0;
				
				$g = $draftlist[$username][$lastsubmit[$username]];
				
				$res = preg_match("/(\/me\/.*\/)([0-9]+)(.*)/i",$var['message'],$keywords);
				if ($res)
				{
					$var['BASE']= "TASK";
					$var['TASKID'] = $keywords[2];
					$var['DRAFTID'] = $g;
					$var['RES'] = $keywords[3];
				}
				$lastsubmit[$username]++;
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
		
		// Define the labels for the header
		$headers= array(
				"LEVEL","DATE","ACTION", "MSG",		// event basic info
				"USER", "IP",						// user identification
				"SESSION","ENDDATE","DURATION",		// session and timespan
				"BASE","TASKID","DRAFTID","RES",	// task and draft identification
				"BROWSER","OS","DEVICE"				// user agent
			);
		
		// format output as tab-separated values
		if ($format==="tsv")
		{
			$str="";
			$str =$str."".join("\t", $headers)."\n";
			foreach ($json['items'] as $key=>$var)
			{
				$str =$str."".join("\t", $var)."\n";
			}
			
			$response = $this->app->response();
			$response['Content-Type'] = 'text/tab-separated-values';
			//$response['Content-Type'] = 'text/plain';
			$response['X-Powered-By'] = 'openEssayist';
			$response->status(200);
			$response->body($str);
			
		}
		// format output as JSON object
		else if ($format==="json")
		{
			$response = $this->app->response();
			$response['Content-Type'] = 'application/x-javascript';
			$response['X-Powered-By'] = 'openEssayist';
			$response->status(200);
			$response->body(json_encode($json));
		}
		// format output as Excel document
		else 
		{
			// Hack for excel-compliant date format
			foreach ($json['items'] as &$var)
			{
				$date1 = new DateTime($var['date']);
				$date2 = new DateTime($var['ENDDATE']);
				$var['date'] = PHPExcel_Shared_Date::PHPToExcel($date1);//->format('d/m/Y  H:i:s');
				$var['ENDDATE']=PHPExcel_Shared_Date::PHPToExcel($date2);;//->format('d/m/Y  H:i:s');
			}
		
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Nicolas Van Labeke")
				->setLastModifiedBy("Nicolas Van Labeke")
				->setTitle("OpenEssayist log dump")
				->setSubject("OpenEssayist study")
				->setDescription("")
				->setKeywords("openEssayist activity log")
				->setCategory("openEssayist");
			
			$objPHPExcel->setActiveSheetIndex(0);
			
			// Set date format for the two column 'DATE' and 'ENDDATE'
			$objPHPExcel->getActiveSheet()
				->getStyle('B')
				->getNumberFormat()
				->setFormatCode("dd mmm yy");
			$objPHPExcel->getActiveSheet()
				->getStyle('H')
				->getNumberFormat()
				->setFormatCode("dd mmm yy");
						
			// create the 'logs' spreadsheet
			$objPHPExcel->getActiveSheet()
				->setTitle('logs')
				->fromArray($headers, NULL, 'A1',true)
				->fromArray($json['items'], NULL, 'A2',true);
			
			// create an Excel writer
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			
			// write the content to the ouptput as a file
			$response = $this->app->response();
			$response['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8';
			$response['X-Powered-By'] = 'openEssayist';
			$response->status(200);
			$objWriter->save("php://output");
		}
		
	}	
	
	public function showLogs()
	{
		$this->render('admin/logs');
		
	}
	
	public function showLogsTable()
	{
		$this->render('admin/logs.table');
	
	}
	
	public function showUserLogs($user)
	{
		$this->render('admin/logs.user',array(
			'userlog' => $user
		));
	}
	
}