<?php
use Respect\Validation\Validator as v;

class UserController extends Controller
{
	public static $STRUCT = array(
			'#-s:t#'=>'Title',
			'#+s#'=>'Body',
			'#-s:h#'=>'Heading',
			'#+s:i#'=>'Introduction',
			'#-s:n#'=>'Number',
			'#-s:p#'=>'Others',
			'#+s:c#'=>'Conclusion',
	);
		
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

		//var_dump($u->as_array(),$g->as_array());
		//var_dump($t);
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
		/* @var $g Group */
		$g = $u->group()->find_one();
		if ($g===false)
		{
			$this->app->flash("error", "Cannot find the group data");
			$this->redirect('me.home');
		}

		$d = $u->drafts()->where_equal('task_id',$taskId)->order_by_desc('id')->find_array();
		foreach ($d as $key => &$draft)
		{
			$analysis = json_decode($draft['analysis'],true);
			$wordcount = $analysis['se_stats']['number_of_words'];
			$draft['wordcount'] = $wordcount;
			$k = $analysis['nvl_data'];
			if (isset($k))
				$draft['keywords'] = array_merge($k['quadgrams'],$k['trigrams'],$k['bigrams']);
			//var_dump($draft['keywords']);
			unset($draft['analysis']);
			$gg = $this->timeSince(strtotime($draft['date']));
			$draft['datesince'] = $gg;
		}
		/* @var $d Draft */
		$ap = $g->tasks()->find_one($taskId);
		
		$actions = TutorController::getActivities();
		
			
		$this->render('user/task.info',array(
				'group' => $g->as_array(),
				'task' => $ap->as_array(),
				'actions' => $actions,
				'drafts' => $d
		));
		//var_dump($d);
		//date_default_timezone_set('Europe/London');
		//var_dump(date('Y-m-d H:i:s e'));
	}

	public function submitDraft($taskId)
	{
		$req = $this->app->request();
		$async = $this->app->config('openEssayist.async');

		/* @var $d Draft */
		$ap = Model::factory('Task')->find_one($taskId);

		if ($req && $req->isPost())
		{
			$post = $req->post();

			if ($async)
				$this->submitAsync($ap, $taskId, $post);
			else
			{
				try {
					$url = 'http://localhost:8062/api/analysis';
					$request = Requests::post($url,
							array(),
							array('text' => $post["text"]),
							array('timeout' => 30));
					//var_dump($request->status_code);
					if ($request->status_code === 200)
					{
						//var_dump($request->body);
							
						$json = $request->body;
						$ret = json_decode($json,true);
						//var_dump(array_keys($ret));
						//var_dump($ret['ke_data']['bigram_keyphrases']);
							
						/* @var $draft Draft */
						$draft = Model::factory('Draft')->create();
						$draft->type = 0;
						$draft->analysis = $json;
						$draft->task_id = $taskId;
						$draft->users_id = $this->user['id'];
						$draft->date = date('Y-m-d H:i:s e');
						$draft->save();

					}
				}
				catch (Requests_Exception $e)
				{
					$this->app->flashNow("error", "Cannot connect to the analyser (" . $url.")! Try again later");
					//var_dump($e);
				}
				catch (\PDOException  $e)
				{
					$this->app->flashNow("error", "Problem with the database.");
					var_dump($e);

				}
				$r= $this->app->urlFor('me.drafts',array("idt" => $taskId));
				$this->redirect($r,false);
			}
		}

		$this->render('user/draft.submit',array(
				'task' => $ap->as_array(),
		));
	}

	private function submitAsync($ap,$taskId,$post)
	{
		$r= $this->app->urlFor('me.draft.process',array("idt" => $taskId));

		$req = $this->app->request();
		$root = $req->getRootUri();
		if ($root == '') $r = "http://localhost:8080".$r;
		try {

			$draft = Model::factory('Draft')->create();
			$draft->type = 0;
			$draft->processed = 0;
			$draft->analysis = $post["text"];
			$draft->task_id = $taskId;
			$draft->users_id = $this->user['id'];
			$draft->date = date('Y-m-d H:i:s e');
			$draft->save();

			$request = Requests::post($r,
					array(),
					array(
							'text' => $post["text"],
							'users_id' => $this->user['id'],
							'task_id' => $taskId,
							'draft_id' => $draft->id
					),
					array('timeout' => 1)
			);
		}
		catch (Requests_Exception $e)
		{
			$this->app->flashNow("info", "Analysis in progress....");
		}
		catch (Exception $e)
		{
			$this->app->flashNow("error", $e);
		}
		$this->render('user/draft.submit',array(
				'task' => $ap->as_array(),
				'text' => $post["text"]
		));

	}

	public function processDraft($taskId)
	{
		ignore_user_abort(true);
		$log = $this->app->getLog();

		$log->info("START PROCESS");

		$req = $this->app->request();
		if ($req && $req->isPost())
		{
			$post = $req->post();
			$log->info(json_encode($post));
			//echo $post["text"];

			/* @var $draft Draft */
			$draft = Model::factory('Draft')->find_one($post['draft_id']);
				
			try {
				$data = $draft->as_array();
				$log->info(json_encode($data));
				sleep(2);

				$url = 'http://localhost:8062/api/analysis';
				$request = Requests::post($url,
						array(),
						array('text' => $post["text"]),
						array('timeout' => 100));

				$log->error("==> " . $request->status_code);
				if ($request->status_code === 200)
				{
					$json = $request->body;
					$ret = json_decode($json,true);
					$draft->analysis = $json;
					$draft->processed = 1;
					$draft->date = date('Y-m-d H:i:s e');
					$draft->save();
					$log->info("ANALYSIS COMPLETED");
				}
				else
				{
					$draft->processed = -1;
					$draft->save();
					$log->info("ANALYSIS FAILED");
				}

			} catch (Exception $e)
			{
				$log->error("==> ERROR");
				$draft->processed = -1;
				$draft->save();
				//$draft->delete();
			}
				
		}
		//$this->redirect('me.home');
		$log->info("STOP PROCESS");

	}

	/**
	 *
	 * @param string $draft
	 * @return Draft
	 */
	protected function getDraft($draft)
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
		return $g;

	}

	/**
	 *
	 * @param string $draft
	 */
	public function showDraft($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();


		$data = $dr->as_array();
		$parasenttok = $dr->getParasenttok();
		$analysis = $dr->getAnalysis();

		// extract structure tags
		// Need to be done once for all
		$struct2 = array();
		foreach ($parasenttok as $index => $par)
		{
			foreach ($par as $index2 => $sent)
			{
		
				$struct2[] = $sent['tag'];
			}
		}
		$tt = array_unique($struct2);
		$tt = array_flip($tt);
		$tt = array_keys($tt);
		$tt = array_flip($tt);
		
		
		// Get all ngrams in a single structure
		$allkw = array_merge(array(),
				$analysis->nvl_data->quadgrams,
				$analysis->nvl_data->trigrams,
				$analysis->nvl_data->bigrams,
				$analysis->nvl_data->keywords
		);

		$tt = $dr->kwCategories()->find_one();
		$groups = array();
		if ($tt!=false)
		{
			$groups = $tt->getGroups();
		}
		else {
			$kw = array();
			foreach ($allkw as $key=>$item)
				$kw[] = $key; 
			$groups = array(array('id' => 'category_all','keywords' => $kw));
		}
		
		
		$highlighjs = array();
		
		foreach ($groups as $key=>$group){
			
			//$formatter = array();
			//$formatter['id'] = $group['id'];
				
			$kw = $group['keywords'];
			$nkw2 = array();
			
	//		if (!$kw) $kw=array();

			if ($kw) foreach($kw as $ref){
				$ngram = $allkw[$ref];
				
				$ngram->groupid =  $group['id'];
				
				$highlighjs[] = $ngram;
				//$nkw2[] = $allkw[$ref];
			}
			//usort($nkw2,function($a,$b)
			//{
			//	return count($b->ngram)-count($a->ngram);
			//});
			//$formatter['kw'] = $nkw2;
			//$highlighjs[] = $formatter;
		}
		usort($highlighjs,function($a,$b)
		{
			return count($b->ngram)-count($a->ngram);
		});
		
		$tutor = $_SESSION['tutor'];
		$config = null;
		if (isset($tutor))
		{
			$config  = $tutor['config'];
			unset($_SESSION['tutor']);
		}
			
	
	
		$this->render('drafts/draft.show',array(
				'configxx' => array(
						'modify' => false,
						'structure'=> array(
								'modify' => true,
								'show'=> true,
								'check' => null//array('#+s:i#','#+s:c#')
							),
						'sentence'=> array(
								'show'=> true,
								'range' => array('min'=> 0,'max'=>15)
							),
						'keyword'=> array(
								'show'=> true,
								'check'=> array('category_all')
							),
				),
				'config' => $config,
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'parasenttok' => $parasenttok,
				'keywords' => $allkw,
				'groups' => $groups,
				'ngrams' => $highlighjs
		));
	}

	/**
	 *
	 * @param string $draft
	 */
	public function showSentence($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();

		$parasenttok = $dr->getParasenttok();
		$data = $dr->as_array();
		$analysis = $dr->getAnalysis();


		// unpack senteces from structure
		$parasenttok = call_user_func_array('array_merge', $parasenttok);
		$sort = array();
		// extract ranks as key and remove sentence wihthout
		foreach($parasenttok as $k=>&$v)
		{
			if (isset($v['rank']))
				$sort['rank'][$k] = $v['rank'];
			else
				unset($parasenttok[$k]);
		}
		// sort remaining sentences accordingly
		array_multisort($sort['rank'], SORT_ASC, $parasenttok);

		$this->render('drafts/draft.sentence',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'sentences' => $parasenttok
		));

		//var_dump($parasenttok);
	}

	/**
	 *
	 * @param string $draft
	 */
	public function showKeyword($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();

		$data = $dr->as_array();
		$analysis = $dr->getAnalysis();

		// Get all key phrases in a single structure
		$allkw = array_merge(array(),
				$analysis->nvl_data->quadgrams,
				$analysis->nvl_data->trigrams,
				$analysis->nvl_data->bigrams
		);
		

		$this->render('drafts/draft.keyword',array(
				'task' => $tsk->as_array(),
				'draft' => $dr	->as_array(),
				'keywords' => array(
						'Key Words' => $analysis->nvl_data->keywords,
						'Key Phrases' => $allkw,
						//'quadgrams' => $analysis->nvl_data->quadgrams,
						//'trigrams' => $analysis->nvl_data->trigrams,
						//'bigrams' => $analysis->nvl_data->bigrams,
				)
		));
	}

	public function showStats($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();

		$tttt= $dr->getAnalysis(true);
		unset($tttt['parasenttok']);
		unset($tttt['se_data']);
		$body = $tttt['body'];
		$intro = $tttt['intro'];
		$concl = $tttt['concl'];
		$refs = $tttt['refs'];
		$appendix = $tttt['appendix'];
		unset($tttt['body']);
		unset($tttt['intro']);
		unset($tttt['concl']);
		unset($tttt['refs']);
		unset($tttt['appendix']);
		unset($tttt['nvl_data']);
		$struct = array();
		$struct['intro'] = $intro;
		$struct['concl'] = $concl;
		$struct['body'] = $body;
		$struct['refs'] = $refs;
		$struct['appendix'] = $appendix;
		$tttt['structure'] = $struct;

		foreach ($tttt['ke_data'] as $key => $value)
		{
			if (!is_array($value))
			{
				$tttt['ke_stats'][$key] = $value;
			}
		}
		unset($tttt['ke_data']);
		$this->render('drafts/stats',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'stats' => $tttt
		));
	}

	public function actionKeyword($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		
		$tt = $dr->kwCategories()->find_array();
		$analysis = $dr->getAnalysis();
		
		//$cat = Model::factory('KWCategory')->create();
		//$cat->draft_id = $dr->id;
		//$cat->category = "THIS IS A TEST";
		//$cat->save();
		
		$tt = $dr->kwCategories()->find_one();
		$groups = array();
		if ($tt!=false)
		{
			$groups = $tt->getGroups();
		}
		
		
		$alllema = array_keys(get_object_vars($analysis->ke_data->myarray_ke));
		$allfreq = $analysis->ke_data->scoresNfreqs ;
		foreach ($allfreq as $key=>&$item)
		{
			$item = array(
					'value'=>$item[0],
					//'label'=>$item[0],
					'ngram'=>array($item[0]),
					'source'=>array($item[0]),
					'count'=>$item[3],
					'score'=>array($item[1])
				);
		}
		
		
		$allkw = array_merge(array(),
			$analysis->nvl_data->quadgrams,
			$analysis->nvl_data->trigrams,
			$analysis->nvl_data->bigrams,
			$analysis->nvl_data->keywords
		);
		
		$tmpl = array('drag'=>'drafts/action.keyword','table'=>'drafts/action.keyword.table');
		$this->render($tmpl['drag'],array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'keywords' => $allkw,
				'groups' => $groups,
				'lemmas' => $allfreq
		));

		
		
	}
	
	public function viewStructure($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
	
		$analysis = $dr->getAnalysis();
		$text = $dr->getParasenttok();
		
		
		$breakdown = array();
		foreach ($text as $index => &$par)
		{
			foreach ($par as $index2 => $sent)
			{
				$tt = $sent['text'];
				$tag = $sent['tag'];
				$count = str_word_count($tt, 0);
				if (!isset($breakdown[$tag]))
					$breakdown[$tag] = 0;
				$breakdown[$tag] += $count;
			}
		}
		
		
		$distribution = array();
		$bullet = array();
		foreach ($breakdown as $id => $count)
		{
			$tt = array(
					'name' => $this->STRUCT[$id],
					'y'=>$count);
			if (in_array($id, array('#+s:c#','#+s:i#')))
			{
				$tt['sliced'] = true;
				$tt['selected'] = true;
				
			}
			$distribution[] = $tt;
			$bullet[] = array(
					'name' => $this->STRUCT[$id],
					'data'=> array($count),
					'grouping' => false,'stack' => 'structure');
			
		}
		$bullet[] = array(
				'name' => 'target',
				'data' => array(intval($tsk->wordcount)),
				'pointWidth' => 50,
				'stack' => 'target','grouping' => false);
		
	
		$this->render('drafts/view.structure',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'breakdown' => $distribution,
				'bullet' => $bullet

		));
		var_dump($this->STRUCT);
	}

	public function viewDispersion($draft)
	{
		// Get request object
		//$req = $this->app->request();
		//$env = $this->app->environment();
		//$a = $env['PATH_INFO'];
		//$this->app->etag('12345'.$draft);
		//$this->app->expires('+1 week');
		
		
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		
		$analysis = $dr->getAnalysis();
		$text = $dr->getParasenttok();
		
		$struct = array(
				$analysis->intro->i_first,
				$analysis->intro->i_last,
				$analysis->concl->c_first,
				$analysis->concl->c_last);
		
		$tags = array();
		$struct2 = array();
		$count = array();
		// Join the array into a single string and count words
		foreach ($text as $index => &$par)
		{	
			$setag = array();
			foreach ($par as $index2 => &$sent)
			{
				$setag[] = $sent['tag'];
				$sent = $sent['text'];
			}
			$tags[] = array_shift(array_unique($setag));
			$par = "" . join(" ", $par);
			$count2 = str_word_count($par, 1);
			$struct2[] = count($count2);
			$count = array_merge($count,$count2);
			//var_dump($struct2,$count);
		}
		
		$limit= array();
		$ticks= array();
		$inc = 0;
		foreach ($struct2 as $key=>$wcount) 
		{
			$item = array(
					'from' => $inc,
					'to' => $inc+$wcount,
					'tag' => $tags[$key],
				
			);
			$ticks[] = $inc;
			$inc += $wcount;
			$limit[] = $item;
		}
		
		
		/*
		$limit= array(
			'Introduction'=> array(
					'tag' => '#+s:i#',
					'from' => array_sum(array_slice($struct2,0,$analysis->intro->i_first)),
					'to' => array_sum(array_slice($struct2,0,$analysis->intro->i_last+1))),
			'Conclusion'=> array(
					'tag' => '#+s:c#',
					'from' => array_sum(array_slice($struct2,0,$analysis->concl->c_first)),
					'to' => array_sum(array_slice($struct2,0,$analysis->concl->c_last+1))),

			);*/

		$text = "" . join(" ", $text);
// 		/$count = str_word_count($text, 1);
		$count = array_map('strtolower', $count);
		//$ret = array_search(strtolower("learners"), array_map('strtolower', $count));
		
		
		
		$allkw = array_merge(array(),
				$analysis->nvl_data->quadgrams,
				$analysis->nvl_data->trigrams,
				$analysis->nvl_data->bigrams,
				$analysis->nvl_data->keywords,
				array()
		);
		
		usort($allkw,function($a,$b)
		{
			return ($b->count)-($a->count);
		});		
		

		
		$tt = $dr->kwCategories()->find_one();
		$groups = array();
		if ($tt!=false)
		{
			$groups = $tt->getGroups();
		}
		else {
			$kw = array();
			foreach ($allkw as $key=>$item)
				$kw[] = $key;
			$groups = array(array('id' => 'category_all','keywords' => $kw));
		}
		
		$categories=array();
		$series=array();
		$series2= array(
					'name' => "TEST",
					'data' => array()
					);
		
		
		$yaxis=0;
		foreach ($allkw as  $key=>$kw)
		{	
			$cnt = $kw->count;
			$src = $kw->source;
			$ngram = $kw->ngram;
			$score = $kw->score;
			
			$groupid = null;
			$groupcolor = null;
			$groupname = null;
			foreach ($groups as $gr)
			{
				
				if (in_array($key,$gr['keywords']))
				{
					$groupid = $gr['id'];
					break;
				}
			}
			
			//var_dump($ngram,$groupid);
			$dispers=array();
			if (count($ngram)>1)
			{	
				$temp = array();
				// find occurences of all individal terms and merge indexes
				foreach ($src as $infform)
				{	
					$ret = array_keys($count,strtolower($infform));
					$temp = array_merge($temp,$ret);
					
				}
				// sort indexes numerially
				sort($temp);
				//var_dump($temp);
				$res = array();
				$res2 = array();
				$prev = -1000;
				// find indexes that are consecutive (definition of ngram)
				foreach ($temp as $key=>$val)
				{ 	
					$diff = $val-$prev;
					if ($diff==1)
					{
						$res2[] = $prev;
						$res2[] = $val;
						//var_dump(count($res2) . " " . count($ngram));
						if (count($res2) == 2*(count($ngram)-1))
						{
							$res = array_merge($res,$res2);
						}
					}
					else {
						$res2=array();
					}
					$prev = $val;
				}
				
				// remove duplicates
				//var_dump($res);
				$res = array_unique($res);
				// if needed, get only the first index of each ngram
				//$res = array_chunk($res,count($ngram));
				//var_dump($res);
				// merge the indexes into the dispersion array
				$dispers = array_merge($dispers,$res);
			}
			else foreach ($src as $infform)
			{	
				$ret = array_keys($count,strtolower($infform));
				$dispers = array_merge($dispers,$ret);
			}
			//sort the indexes
			sort($dispers,SORT_NUMERIC);
			// add the y-axis values
			array_walk($dispers,function (&$item1, $key, $yaxis) { $item1 = array($item1,$yaxis); },$yaxis);
			$yaxis++;

			if (array_key_exists($groupid,	$series))
			{
				$series[$groupid] = array_merge($series[$groupid],$dispers);
			}
			else 
			{
				$series[$groupid] = $dispers;
			}
			$series2['data'] = array_merge($series2['data'],$dispers);
			$categories[] = "".join($ngram," ");
		}
		
		//var_dump($limit);
		//die();
		$series3=array();
		
		$groups2 = array();
		foreach ($groups as $key=>$gr)
		{
			$groups2[$gr['id']] = $gr;
		}
		
		foreach ($series as $key=>$ser)
		{
			$gr = $groups2[$key];
			$name = $gr['attr']['name'];
			$name = ($name) ?: "Default Group"; 
			$color = $gr['attr']['color'];
			$color = ($color) ?: "#880000";
			
			$series3[] = array(
					'name' => $name,
					'data' => $ser,
					'color' =>$color
			);
		}
		
		$series2['data'] = array_slice($series2['data'],0, 1000);
		
		$tags = array();
		foreach ($limit as $item)
			if ($item['tag']!='#-s:h#')
				$tags[$item['from']] = $item['tag'];
		//var_dump($tags);
		//die();
		
		$this->render('drafts/view.dispersion',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'series' => $series3,
				'ticks' => $ticks,
				'tags' =>$tags,
				'categories' => $categories,
				'structure' => $limit
		));		
	}

	public function viewCloud($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		$analysis = $dr->getAnalysis();
		$allkw = array_merge(array(),
				$analysis->nvl_data->quadgrams,
				$analysis->nvl_data->trigrams,
				$analysis->nvl_data->bigrams,
				$analysis->nvl_data->keywords,
				array()
		);
		
		
		
		$this->render('drafts/view.cloud',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'keywords' => $allkw
		));
	}
	
	public function viewChord($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		$analysis = $dr->getAnalysis();
		$allkw = array_merge(array(),
				$analysis->nvl_data->quadgrams,
				$analysis->nvl_data->trigrams,
				$analysis->nvl_data->bigrams,
				$analysis->nvl_data->keywords,
				array()
		);

		$rank = $analysis->se_data->se_ranked;
		foreach ($rank as &$p2222r)
			$p2222r = $p2222r[1];
			
		$text = $dr->getParasenttok();
		
		$struct2 = array();
		$count = array();
		// Join the array into a single string and count words
		foreach ($text as $index => $par)
		{
			foreach ($par as $index2 => $sent)
			{
		
				$struct2[$sent['id']] = $sent['tag'];
				$count[$sent['id']] = $sent['text'];
			}
		}

		
	
	
		$this->render('drafts/view.chord',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'category' => $struct2,
				'sentence' => $count,
				'rank' => $rank
			));
	}
	
	public function viewMatrix($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();

		$analysis = $dr->getAnalysis(true);
		$gr = json_decode($analysis['se_sample_graph'],true);
		
		$rank = $analysis['se_data']['se_ranked'];
		foreach ($rank as &$p2222r)
			$p2222r = $p2222r[1];
			
		$text = $dr->getParasenttok();
		
		$struct2 = array();
		$count = array();
		// Join the array into a single string and count words
		foreach ($text as $index => $par)
		{
			foreach ($par as $index2 => $sent)
			{
				
				$struct2[$sent['id']] = $sent['tag'];
				$count[$sent['id']] = $sent['text'];
			}
		}
		
		//var_dump($struct2,$count,$gr);die();
		$tt = array_unique($struct2);
		$tt = array_flip($tt);
		$tt = array_keys($tt);
		$tt = array_flip($tt);


		$this->render('drafts/view.matrix',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'graph' => $gr,
				'category' => $struct2,
				'sentence' => $count,
				'rank' => $rank
		));
	}
	
	public function viewGraph($draft,$graph=null)
	{
		$graphlist = array(
				'graphse' => array(
						'name'	=> 'Sentence network',
						'path'	=> 'drafts/view.graph',
						'data'	=> 'se_sample_graph'),
				'graphke' => array(
						'name'	=> 'Keyword network',
						'path'	=> 'drafts/view.graph',
						'data'	=> 'ke_sample_graph'),
				'cytoscape' => array(
						'name'	=> 'Sentence network',
						'path'	=> 'drafts/view.cytoscape',
						'data'	=> 'se_sample_graph'),
				);

		if (!isset($graph))
		{
			$this->render('drafts/view.graph');
			return;
		}

		if (!array_key_exists($graph,$graphlist))
		{
			$this->app->flash("error", "This view does not exist. Try one of the following.");
			//$this->redirect('me.home');
			$url = $this->app->urlFor('me.draft.view.graph',array('draft'=>$draft));
			var_dump($url);

			$this->app->redirect($url);
			return;
		}

		$path = $graphlist[$graph]['path'];
		$data = $graphlist[$graph]['data'];

		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();

		$analysis = $dr->getAnalysis(true);
		$gr = json_decode($analysis[$data],true);
		if ($data=='se_sample_graph')
		{
			$gg = array();
			$rr = $analysis['se_data']['se_ranked'];
			foreach ($rr as $key => &$ranked)
			{
				$ranked['rank'] = $key;
				$gg[$ranked[1]] = $ranked;
			}
			foreach ($gr['nodes'] as $key => &$node)
			{
				if ($gg[$node[id]])
					$node['rank'] = $gg[$node[id]]['rank'];
			}
			//var_dump($gg,$gr);die();
		}
			
		$this->render($path,array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array(),
				'views' => $graphlist,
				'view' => $graph,
				'graph' => $gr
		));
	}

	public function saveNotes()
	{
		 $this->app->etag('unique-id');
		echo "<h3>fggffggffgfg</h3>";
	}
	
	public function saveKeywords()
	{
		$req = $this->app->request();
		$post = null;
		//if ($req && $req->isPost())
		$post = $req->post();
		$draftid = $post['draft'];
		
		

		$dr = $this->getDraft($draftid);
		$tt = $dr->kwCategories()->find_one();
		if ($tt==false)
		{
			$cat = Model::factory('KWCategory')->create();
			$cat->draft_id = $dr->id;
			$cat->category = json_encode($post['data']);
			$cat->save();
			$tt = $cat;
			//var_dump( $tt->category );
					
		}
		else
		{
			$tt->category = json_encode($post['data']);
			$tt->save();
			//var_dump( $tt->category );
		}
		//sleep(10);
		//var_dump( $tt);
		header("Content-Type: application/json");
		echo json_encode($tt->category);
		//echo json_encode(array());
		
	}

	
	public function getExhibitJSON($draftid)
	{
		$json = array('items'=> array());

		$dr = $this->getDraft($draftid);
		$tsk = $dr->task()->find_one();
		
		date_default_timezone_set('UTC');
		
		$date = new DateTime('0001-01-01');
		$inc = $date;
		//$data = $dr->as_array();
		$parasenttok = $dr->getParasenttok();
		$analysis = $dr->getAnalysis();
		
		$keylemmas =$analysis->ke_data->keylemmas; 
		
		foreach ($parasenttok as $key=>$tt)
		{
			$par = array(
					'label' => 'par'.str_pad($key, 4, '0', STR_PAD_LEFT),
					'type' => 'paragraph',
					'start' => $inc->format('Y-m-d'),
					'contains' => array()
			);
			foreach ($tt as $key=>$hh)
			{
				
				$tt = array_intersect($keylemmas,$hh['lemma']);
				
				
				$id = str_pad($hh['id'], 4, '0', STR_PAD_LEFT);
				$sen = array(
						'label' => 'sent'.$id,
						'type' => 'sentence',
						'tag' => $hh['tag'],
						
						'start' => $inc->format('Y-m-d'),
						'text' => $hh['text'],
						'keyword' => array_values($tt)//$hh['lemma'],
				);
				if ($hh['rank'])
					$sen['rank'] = $hh['rank'];
				
				$par['tag']=$hh['tag'];
				$par['contains'][]='sent'.$id;
				$json['items'][]=$sen;
				
				$gg = count($tt);
				$gg = "+".$gg." month +1 year";
				
				$inc->modify($gg);
				
				
			}
			$inc2 = clone $inc;
			$inc2->modify('-1 week');
			$par['end'] = $inc2->format('Y-m-d');
			$json['items'][]=$par;
		}
		
		$keywords = $analysis->nvl_data->keywords;
		foreach ($keywords as $key=>$hh)
			{
				$key = array(
						'label' => $hh->ngram[0],
						'type' => 'keyword',
						'forms' =>  $hh->source,
				);
				
				
				$json['items'][]=$key;
			}
		

		
		$json['types']= array(
				'paragraph' => array(
						"label" => "Paragraph",
						"pluralLabel" => "Paragraphs"),
				'sentence' => array(
						"label" => "Sentence",
						"pluralLabel" => "Sentences"),
				'keyword' => array(
						"label" => "Key Word",
						"pluralLabel" => "Key Words"),
		);
		$json['properties']= array(
				'contains' 	=> array("valueType" => "item"),
				'keyword' 	=> array("valueType" => "item"),
				'start' 	=> array("valueType" => "date"),
				'rank' 		=> array("valueType" => "number")
		);		
		$response = $this->app->response();
		$response['Content-Type'] = 'application/json';
		$response['X-Powered-By'] = 'openEssayist';
		$response->status(200);
		
		//$response->body(json_encode($parasenttok));
		$response->body(json_encode($json));
		
	}
	
	public function viewExhibit($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		

	
		$this->render('drafts/view.exhibit',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array()
		));
	}
	
	
	
	public function ajaxGraph($draft,$graph)
	{
		
		$config = array(
				'graphse'=>'se_sample_graph',
				'graphke'=>'ke_sample_graph'
			);
		
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		
		$analysis = $dr->getAnalysis(true);
		$gr = json_decode($analysis[$config[$graph]],true);
		
		
		$response = $this->app->response();
		$response['Content-Type'] = 'application/json';
		$response['X-Powered-By'] = 'openEssayist';
		$response->status(200);
		$response->body(json_encode($gr));
		
	}
	
	public function scafoldRedirect($config,$url)
	{
		$redirect = "".join($url,"/");

		$configs = TutorController::getViewConfigurations();
		
		
		unset($_SESSION['tutor']);
		$tt = $configs[$config];
		$tt['id'] = $config;
		if (isset($tt))
			$_SESSION['tutor'] = $tt;
	
		$this->app->flash("tutor", "Cannot find the user data");
		$this->app->redirect($redirect);
	}
}
