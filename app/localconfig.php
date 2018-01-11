<?php
/**
 * OpenEssayist-slim.
 *
 * @copyright © 2013-2018 The Open University. (Institute of Educational Technology)
 */

/**
 * REPLACE THIS FILE WITH YOUR OWN CONFIGURATION
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class OpenEssayistConfigurator extends Application {

	/**
	 *
	 */
	public function setupDB()
	{
		// Create H817 group (based on H810 data file)
		$grpU = $this->createGroup("H817");
		// Create admin user within that group
		$this->createUser(1,$grpU->id,true,"U",true);
		$this->createBatchUsers('../app/accountsH817.csv', $grpU->id);

		// Create Hertfordshire group (based on H810 data file)
		$grpH = $this->createGroup("Hertfordshire");
		// Create admin user within that group
		$this->createUser(1,$grpH->id,true,"H",true);
		$this->createBatchUsers('../app/accountsHerts.csv', $grpH->id);

		// Create Dubai group (based on H810 data file)
		$grpD = $this->createGroup("Dubai");
		// Create admin user within that group
		$this->createUser(1,$grpD->id,true,"D",true);
		$this->createBatchUsers('../app/accountsDubai.csv', $grpD->id);

		$nbi = 1;

		// Create H817 assignments
		$task = $this->createTasks($nbi++,$grpU->id);
		$task->deadline = "2014-03-14";
		$task->wordcount = 1500;
		$task->assignment = <<<EOF
Imagine that you have been asked to produce a report about elearning innovation. The report has been requested by an education or training provider of your choice. Your report should provide an update on current developments and how they might be employed in the workplace.
Your report should include:
- A clearly labelled introduction and conclusion section to your report.
- A definition of innovation in elearning. (100 words maximum)
- Three examples of innovation; two can be taken from the module materials and/or discussions in your tutor group forum. The third should be taken from your own experience or reading around this subject area. (400 words maximum)
- An explanation of why the chosen examples are innovative and how they might be used effectively in your organisation. (1000 words maximum)
- Compile an accurate references list for your assignment. Please use the Harvard (author/date) system, which is the one used in the MAODE Programme. List your references at the end of your assignment, alphabetically by author, and by date within author, as explained in the referencing section of this Guide.
EOF;
		$task->save();

		$task = $this->createTasks($nbi++,$grpU->id);
		$task->deadline = "2014-05-02";
		$task->wordcount = 3000;
		$task->assignment = <<<EOF
Block 2 examines open education from a number of perspectives. The aim of this assignment is to provide a context for synthesising these views and drawing together the knowledge and experience you have acquired.
The format for the assignment is a report you are writing for senior management in an education or training context of your choice. They have heard about open education and want to develop a strategy regarding it. The institution in question can be drawn from your own experience, or you can invent one. It needs to be appropriate, so that the project headings set out below can be addressed in detail. The institution could be, for example, a university, a further education college, a commercial training provider, a publisher, a professional body or a community.
The senior management group you are preparing the report for has heard a lot about open education, but they have little experience of it. You have been charged with writing a briefing document about open education for them to consider. This should conclude with a recommendation for them to consider, but it is not a fully costed business proposal. This recommendation can be in favour of open courses, suggest a strategy for the production of OER, suggest engagement with other initiatives, propose a cautious approach or be any mixture you deem appropriate.
The proposal needs to follow the standard template, which has these sections. You should label each section with the headings listed below::
- Executive summary: Brief, two paragraph summary of the report, not exceeding 500 words.
- Background: Provide an overview of the area, the current research and developments in the subject area.
- Policy: Describe your concluding recommendation in detail.
- Benefits: Set out the key benefits open education could bring to the institution.
- Risks: Set out the main risks (as you see them) of open education for the institution.
- Resources: Outline any technology that is required, and any new roles and responsibilities that would be required for open education to be adopted in the institution.
- References: Provide full references using the OU Harvard style of referencing for your report.
EOF;
		$task->save();

		$task = $this->createTasks($nbi++,$grpU->id);
		$task->deadline = "2014-06-20";
		$task->wordcount = 2500;
		$task->assignment = <<<EOF
Block 3 is designed as a group project, and the assessment will thus refer to the process and product of your work on the project.
Your report should include the following sections which are clearly labelled with the headings listed below:
- Situation: Describe the key characteristics of your context.
- Task:  What was the challenge that your group aimed to meet including your educational aims and objectives? How was your group organised to meet the challenge and how did your individual beliefs, desires and intentions shape the project?
- Actions: What actions did you undertake to address the challenge? What technical and pedagogical approach did you take? What obstacles did you encounter and what effect did they have on the project?
- Results: How did you achieve your aims and objectives? Where there any unexpected outcomes? Assess to what extent you achieved your challenge.
- Reflections: Reflect on the whole design project experience and consider what lessons can be learnt from it. Give a reflective account of your personal contribution to the project supported by evidence such as forum postings.
The report should be supported with references to published sources, forum posting and other sources.
EOF;
		$task->save();

		$task = $this->createTasks($nbi++,$grpU->id);
		$task->deadline = "2014-08-01";
		$task->wordcount = 2500;
		$task->assignment = <<<EOF
For this TMA you are required to critically discuss how assessment can support learning. The assignment is divided into three sections:
1.	Identify the main practical implications and opportunities of an assessment for learning approach either in your own context or in an imaginary educational institution.
2.	Set out and justify your view of the most important features of assessment for it to support learning most effectively either in your own or in a specific institution.
Evaluate three concrete examples of assessment practices and principles that you have encountered, either in your work or in this Block, on the basis of how well they support learning.
Please submit your assignment with a clearly labelled introduction and conclusion section and as a single Word document. Remember to give a word count at the end of your assignment; it should not exceed 2500 words, excluding the references section.
EOF;
		$task->save();


		$nbi = 1;
		// Create Dubai assignment
		$task = $this->createTasks(null,$grpD->id,"Report");
		$task->deadline = "2014-03-26";
		$task->wordcount = 4000;
		$task->assignment = <<<EOF
Qualitative Research Methods Report.
Objectives:
- To demonstrate a sufficient grasp of research methods in order to undertake research projects independently.
Introduction:
- In order to undertake an independent research project, it is important that you have a firm grasp on research methods and clear idea about the topic you will be pursuing. The coursework is designed to enable you to demonstrate your ability to conduct research, and develop knowledge in the area you intend to pursue.
- The coursework has two assignment components: one is qualitative approach based research work and the other is quantitative approach based research work. Both the components should be undertaken individually.
Brief:
- In both the assignments you have to mention the aim, and follow a systematic approach to narrow it down. You have to further derive objective(s) of the research. You have to perform a detailed literature review using REFEREED JOURNAL ARTICLES ONLY that have addressed same/similar/related/partially the issues/challenges/advantages/problems/solutions you are dealing with, in your research. At least 10 articles that show a connection to your work should be cited in each assignment. Based on this literature review you need to demonstrate logically, what approach you are going to take and describe your methodology for applying and validating your approach, e.g. developing clear hypotheses. Then you analyse your primary data that you collect using one of the various data collection techniques, e.g. interview or questionnaires, get them presented and discussed. The report then reports the summary of analysis, findings, and some conclusions and recommendations.
EOF;
		$task->save();


		// Create Herts assignment
		$task = $this->createTasks(null,$grpH->id,"Project");
		$task->deadline = "2014-10-21";
		$task->wordcount = 1500;
		$task->assignment = <<<EOF
This is a temporary task created for the Hertfordshire group.
EOF;
		$task->save();

		// Create Demo group
		$grp = $this->createGroup("Demo","H810");
		$grp->description = "This is a fake course, created specifically for the demo.";
		$grp->save();
		$u = $this->createDemoUser(1,$grp->id);
		$task = $this->createTasks(1,$grp->id);
		$task->wordcount = 1000;
		$task->assignment = "A simple mock-up of an essay based on Aesop's Fables.";
		$task->isopen=0;
		$task->save();
		$this->createDemoDraft($u,$task);


		$iUser=1;
		$grp = $this->createGroup("SAFeSEA","H810");
		// Create admin user within that group
		$this->createUser($iUser,$grp->id,true,null,true);
		// Create Safesea users
		$this->createSafeseaUser($iUser++,$grp->id);
		$this->createSafeseaUser($iUser++,$grp->id);
		$this->createSafeseaUser($iUser++,$grp->id);
		$this->createSafeseaUser($iUser++,$grp->id);
		$this->createSafeseaUser($iUser++,$grp->id);
		$this->createSafeseaUser($iUser++,$grp->id);
		$this->createTasks(1,$grp->id);
		$this->createTasks(2,$grp->id);
	}

	private function createBatchUsers($file,$gid)
	{
		$csvArr = $this->csv_to_array(file_get_contents($file));
		foreach ($csvArr as $user)
		{
			$u = Model::factory('Users')->create();
			$u->name = $user['Username'];
			//$u->email = $user['Email'];
			$u->username = $u->name;
			$u->active = 0;
			$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword( $user['Password']);
			$u->ip_address = $this->app->request()->getIp();
			$u->group_id = $gid;
			if (strpos($u->name, 'tutor') !== FALSE)
				$u->isgroup = true;
			try {
				$u->save();
			}
			catch (\PDOException  $e) {
				//var_dump($e->getMessage());
			}


		}



	}

	private function createDemoUser($id,$gid)
	{
		$gs = Model::factory('Group')->find_many();

		$u = Model::factory('Users')->create();
		$u->name = "demo";
		$u->email = "nicolas.vanlabeke@open.ac.uk";
		$u->username = $u->name;
		$u->active = 1;
		$u->isdemo = 1;
		$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("demo");
		$u->ip_address = $this->app->request()->getIp();
		$u->group_id = $gid;

		try {
			$u->save();
		}
		catch (\PDOException  $e) {
			//var_dump($e->getMessage());
		}

		return $u;

	}

	private function createSafeseaUser($id,$gid)
	{
		$gs = Model::factory('Group')->find_many();

		$u = Model::factory('Users')->create();
		$u->name = "safesea".$id;
		$u->email = "nicolas.vanlabeke@open.ac.uk";
		$u->username = $u->name;
		$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("safesea".$id);
		$u->ip_address = $this->app->request()->getIp();
		$u->isadmin = 0;
		$u->group_id = $gid;

		try {
			$u->save();
		}
		catch (\PDOException  $e) {
			//var_dump($e->getMessage());
		}

	}

	private function createUser($id,$gid,$isadmin=false,$adminID=null,$groupadmin=false)
	{
		$gs = Model::factory('Group')->find_many();

		$u = Model::factory('Users')->create();
		$u->name = ($isadmin) ? "admin".$adminID : "user".$id;
		$u->email = "nicolas.vanlabeke@open.ac.uk";
		$u->username = $u->name;
		if ($isadmin)
			$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("admin1");
		else
			$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword($u->name);
		$u->ip_address = $this->app->request()->getIp();
		$u->isadmin = ($isadmin)? 1:0;
		$u->isgroup = ($groupadmin)? 1:0;
		$u->active = ($isadmin)? 1:0;
		$u->group_id = $gid;

		try {
			$u->save();
		}
		catch (\PDOException  $e) {
			//var_dump($e->getMessage());
		}

	}

	private function createGroup($name,$code="H810")
	{
		$gs = Model::factory('Group')->create();
		$gs->name = $name;
		$gs->code = $code;
		//$gs->url = "https://learn2.open.ac.uk/mod/forumng/view.php?id=333356";
		$gs->email = "Open-Essayist@open.ac.uk";
		try {
			$gs->save();
		}
		catch (\PDOException  $e) {}
		return $gs;
	}

	private function createTasks($id,$gid,$namepattern="TMA0",$code="TMA01")
	{
		$gs = Model::factory('Group')->find_many();

		/* @var $task Task */
		$task = Model::factory('Task')->create();
		$task->group_id = $gid;
		if (is_null($id))
			$task->name = $namepattern;
		else
			$task->name = $namepattern.$id;
		$task->code = $code;
		$today = date("Y-m-d");
		$task->deadline = $today;
		$task->isopen = 1;
		$task->wordcount = 1000;
		try {
			$task->save();
		}
		catch (\PDOException  $e) {}

		return $task;
	}

	private function createDemoDraft($u,$task)
	{
		$draft = Model::factory('Draft')->create();
		$draft->task_id = $task->id;
		$draft->users_id = $u->id;
		$draft->processed = 1;
		$draft->analysis = $this->getDemoAnalysis();
		$draft->date =  date("Y-m-d");

		try {
			$draft->save();
		}
		catch (\PDOException  $e) {}

		$kw = Model::factory('KWCategory')->create();
		$kw->draft_id = $draft->id;
		$kw->category = $this->getDemoKWGroups();

		try {
			$kw->save();
		}
		catch (\PDOException  $e) {}

		return $draft;
	}

	private function getDemoKWGroups()
	{
		return <<<EOF
{"category_all":{"keywords":["manreplied","doctorasked","treasureinside","singledog","dogreplied","nowbigger","positivesymptoms","familyasked","positivesymptom","doctorsaid","patientsaid","dogsaid","makinghoney","onetime","passengersswam","say","make","reply","ask","sun","bite","one","now","ox","single","time","sky","feel","gown","fell","treasure","swim","notice","inside","good","symptom","positive","thales","gaze","astronomer","wound","family","die","athena","pray","athenian","dead","cure","big","passenger","moon","profit","honey"],"id":"category_all"},"category_1":{"keywords":["doctor","child","patient","man"],"id":"category_1","attr":{"name":"Group 1","desc":"","color":"#1f77b4"}},"category_2":{"keywords":["bee","hen","frog","dog"],"id":"category_2","attr":{"name":"Group 2","desc":"","color":"#ff7f0e"}}}
EOF;
	}

	private function getDemoAnalysis()
	{
		$ff = <<<EOF
{
  "body": {
    "late_wc": false,
    "b_last": 22
  },
  "ke_stats": {
    "avfreqsum": 6,
    "sum_freq_kls_in_ass_q_long": 0,
    "sum_freq_kls_in_ass_q_short": 0,
    "sum_freq_kls_in_tb_index": 0
  },
  "ke_data": {
    "myarray_ke": {
      "gown": [
        "gown"
      ],
      "oak": [
        "oak"
      ],
      "move": [
        "moving"
      ],
      "soon": [
        "soon"
      ],
      "awful": [
        "awful"
      ],
      "explode": [
        "exploded"
      ],
      "shake": [
        "shaking"
      ],
      "suffer": [
        "suffering"
      ],
      "find": [
        "find",
        "found"
      ],
      "pray": [
        "praying",
        "pray"
      ],
      "treasure": [
        "treasure",
        "treasure",
        "treasure"
      ],
      "sky": [
        "sky",
        "sky"
      ],
      "arid": [
        "arid"
      ],
      "actually": [
        "actually"
      ],
      "eager": [
        "eager"
      ],
      "marsh": [
        "marsh"
      ],
      "bravery": [
        "bravery"
      ],
      "foolishly": [
        "foolishly"
      ],
      "announce": [
        "announced"
      ],
      "clamor": [
        "clamor"
      ],
      "save": [
        "save"
      ],
      "hope": [
        "hopes",
        "hope"
      ],
      "indignant": [
        "indignant"
      ],
      "swim": [
        "swim",
        "swam"
      ],
      "shepherd": [
        "shepherd",
        "shepherd"
      ],
      "complaint": [
        "complaint"
      ],
      "woman": [
        "woman"
      ],
      "around": [
        "around"
      ],
      "get": [
        "get"
      ],
      "beat": [
        "beating"
      ],
      "big": [
        "bigger",
        "bigger",
        "bigger"
      ],
      "know": [
        "know",
        "know"
      ],
      "wealthy": [
        "wealthy"
      ],
      "foot": [
        "feet"
      ],
      "shiver": [
        "shivering"
      ],
      "bit": [
        "bit"
      ],
      "now": [
        "now",
        "now"
      ],
      "day": [
        "day"
      ],
      "condition": [
        "condition"
      ],
      "bread": [
        "bread",
        "bread"
      ],
      "desire": [
        "desire"
      ],
      "disturb": [
        "disturbed"
      ],
      "confine": [
        "confined"
      ],
      "cause": [
        "cause"
      ],
      "brother": [
        "brother"
      ],
      "hollow": [
        "hollow"
      ],
      "someone": [
        "someone",
        "someone"
      ],
      "die": [
        "die",
        "dying"
      ],
      "mean": [
        "means"
      ],
      "body": [
        "body"
      ],
      "blew": [
        "blew"
      ],
      "right": [
        "right"
      ],
      "old": [
        "old"
      ],
      "miserably": [
        "miserably"
      ],
      "deal": [
        "dealing"
      ],
      "people": [
        "people"
      ],
      "skin": [
        "skin"
      ],
      "discover": [
        "discovered"
      ],
      "dead": [
        "dead"
      ],
      "beg": [
        "begged"
      ],
      "past": [
        "past"
      ],
      "astronomer": [
        "astronomer"
      ],
      "full": [
        "full",
        "full"
      ],
      "jupiter": [
        "jupiter"
      ],
      "expect": [
        "expecting"
      ],
      "sea": [
        "sea"
      ],
      "fail": [
        "failing"
      ],
      "home": [
        "homes"
      ],
      "happen": [
        "happening"
      ],
      "air": [
        "air"
      ],
      "arm": [
        "arms"
      ],
      "croaking": [
        "croaking"
      ],
      "even": [
        "even",
        "even"
      ],
      "voyage": [
        "voyage"
      ],
      "profit": [
        "profit",
        "profit"
      ],
      "sun": [
        "sun",
        "sun",
        "suns"
      ],
      "away": [
        "away"
      ],
      "moon": [
        "moon",
        "moon",
        "moon"
      ],
      "witty": [
        "witty"
      ],
      "re": [
        "re",
        "re",
        "re"
      ],
      "laid": [
        "laid"
      ],
      "run": [
        "ran"
      ],
      "woe": [
        "woe"
      ],
      "reply": [
        "replied",
        "replied",
        "replied",
        "replied"
      ],
      "neither": [
        "neither"
      ],
      "size": [
        "size"
      ],
      "beget": [
        "beget"
      ],
      "satisfy": [
        "satisfied"
      ],
      "notice": [
        "notice",
        "noticed"
      ],
      "noise": [
        "noise"
      ],
      "patient": [
        "patient",
        "patient",
        "patient",
        "patient"
      ],
      "lose": [
        "lost"
      ],
      "slaughter": [
        "slaughtered"
      ],
      "fun": [
        "fun"
      ],
      "fee": [
        "feed"
      ],
      "let": [
        "let"
      ],
      "ask": [
        "asked",
        "asked",
        "asked",
        "asked",
        "asked"
      ],
      "hand": [
        "hands"
      ],
      "come": [
        "coming"
      ],
      "remark": [
        "remarked"
      ],
      "piece": [
        "piece"
      ],
      "drum": [
        "drum"
      ],
      "terrible": [
        "terrible"
      ],
      "tie": [
        "tied"
      ],
      "ox": [
        "ox",
        "ox",
        "ox",
        "ox"
      ],
      "puff": [
        "puffed",
        "puff"
      ],
      "drip": [
        "drip"
      ],
      "onto": [
        "onto",
        "onto"
      ],
      "golden": [
        "golden"
      ],
      "shamefully": [
        "shamefully"
      ],
      "family": [
        "family"
      ],
      "bell": [
        "bell",
        "bell"
      ],
      "feel": [
        "feeling",
        "feel"
      ],
      "compels": [
        "compels"
      ],
      "sign": [
        "sign"
      ],
      "one": [
        "one",
        "one",
        "one",
        "one"
      ],
      "everyone": [
        "everyone"
      ],
      "promise": [
        "promises"
      ],
      "another": [
        "another"
      ],
      "carry": [
        "carry"
      ],
      "parade": [
        "paraded"
      ],
      "standing": [
        "standing"
      ],
      "city": [
        "city"
      ],
      "use": [
        "used"
      ],
      "forth": [
        "forth"
      ],
      "fill": [
        "filled"
      ],
      "start": [
        "started",
        "start"
      ],
      "positive": [
        "positive",
        "positive"
      ],
      "bite": [
        "bite",
        "bitten",
        "bitten",
        "bite"
      ],
      "assure": [
        "assured"
      ],
      "two": [
        "two"
      ],
      "frog": [
        "frogs",
        "frog",
        "frog",
        "frog"
      ],
      "next": [
        "next"
      ],
      "intention": [
        "intention"
      ],
      "gaze": [
        "gazing"
      ],
      "master": [
        "master"
      ],
      "storm": [
        "storm"
      ],
      "hen": [
        "hen",
        "hen",
        "hen"
      ],
      "tell": [
        "told"
      ],
      "athena": [
        "athena",
        "athena"
      ],
      "head": [
        "head"
      ],
      "hard": [
        "harder",
        "harder"
      ],
      "heal": [
        "heal"
      ],
      "wretched": [
        "wretched"
      ],
      "exclaim": [
        "exclaimed"
      ],
      "back": [
        "back"
      ],
      "lift": [
        "lifted"
      ],
      "child": [
        "children",
        "children",
        "children"
      ],
      "attempt": [
        "attempted"
      ],
      "hold": [
        "held"
      ],
      "inflate": [
        "inflating"
      ],
      "symptom": [
        "symptom",
        "symptoms"
      ],
      "fly": [
        "flew"
      ],
      "decoration": [
        "decoration"
      ],
      "kind": [
        "kinds"
      ],
      "parches": [
        "parches"
      ],
      "wise": [
        "wise"
      ],
      "look": [
        "looking"
      ],
      "wound": [
        "wound",
        "wound",
        "wound"
      ],
      "strain": [
        "straining"
      ],
      "thales": [
        "thales"
      ],
      "inside": [
        "inside",
        "inside"
      ],
      "work": [
        "work"
      ],
      "tree": [
        "tree"
      ],
      "single": [
        "single",
        "single"
      ],
      "bed": [
        "bed"
      ],
      "bee": [
        "bees",
        "bees"
      ],
      "while": [
        "whiles"
      ],
      "future": [
        "future"
      ],
      "behavior": [
        "behavior"
      ],
      "grasp": [
        "grasped"
      ],
      "making": [
        "making"
      ],
      "voice": [
        "voices"
      ],
      "passenger": [
        "passengers",
        "passengers"
      ],
      "marketplace": [
        "marketplace"
      ],
      "capsize": [
        "capsized"
      ],
      "figure": [
        "figure"
      ],
      "give": [
        "give"
      ],
      "thracian": [
        "thracian"
      ],
      "sting": [
        "stinging",
        "stings"
      ],
      "equal": [
        "equal"
      ],
      "honey": [
        "honey",
        "honey",
        "honey"
      ],
      "dear": [
        "dear"
      ],
      "say": [
        "said",
        "said",
        "said",
        "said",
        "said",
        "said",
        "said",
        "said"
      ],
      "good": [
        "good",
        "good"
      ],
      "want": [
        "want"
      ],
      "need": [
        "need",
        "need"
      ],
      "ship": [
        "ship"
      ],
      "pit": [
        "pit"
      ],
      "deed": [
        "deeds"
      ],
      "worry": [
        "worried"
      ],
      "chase": [
        "chasing"
      ],
      "high": [
        "high"
      ],
      "cure": [
        "cured"
      ],
      "end": [
        "end"
      ],
      "doctor": [
        "doctor",
        "doctor",
        "doctor",
        "doctor"
      ],
      "inquire": [
        "inquired"
      ],
      "make": [
        "making",
        "made",
        "make",
        "making"
      ],
      "shipwrecked": [
        "shipwrecked"
      ],
      "member": [
        "member"
      ],
      "very": [
        "very"
      ],
      "fitting": [
        "fitting"
      ],
      "take": [
        "take"
      ],
      "seize": [
        "seized"
      ],
      "forge": [
        "forged"
      ],
      "new": [
        "new"
      ],
      "honeybee": [
        "honeybees",
        "honeybees"
      ],
      "creature": [
        "creature"
      ],
      "badly": [
        "badly"
      ],
      "fever": [
        "fever"
      ],
      "slave": [
        "slave"
      ],
      "mother": [
        "mother"
      ],
      "upon": [
        "upon"
      ],
      "sneak": [
        "sneak"
      ],
      "evil": [
        "evil"
      ],
      "companion": [
        "companions"
      ],
      "fell": [
        "fell",
        "fell"
      ],
      "blood": [
        "blood"
      ],
      "jealous": [
        "jealous"
      ],
      "wrinkle": [
        "wrinkled"
      ],
      "man": [
        "man",
        "man",
        "man",
        "man",
        "man",
        "man",
        "man",
        "man",
        "man"
      ],
      "meadow": [
        "meadow"
      ],
      "kept": [
        "kept"
      ],
      "wife": [
        "wife"
      ],
      "try": [
        "tried"
      ],
      "proud": [
        "proud"
      ],
      "well": [
        "well"
      ],
      "dog": [
        "dog",
        "dog",
        "dog",
        "dog",
        "dog",
        "dog",
        "dog",
        "dog"
      ],
      "daily": [
        "daily"
      ],
      "athenian": [
        "athenian",
        "athenian"
      ],
      "u": [
        "us"
      ],
      "time": [
        "time",
        "time",
        "time"
      ],
      "egg": [
        "egg"
      ]
    },
    "fivemostfreq": [
      [
        "man",
        0.3187750570447613,
        2,
        9
      ],
      [
        "dog",
        0.33004963871651066,
        1,
        8
      ],
      [
        "say",
        0.4240237526567572,
        0,
        8
      ],
      [
        "ask",
        0.14379225044249666,
        6,
        5
      ],
      [
        "ox",
        0.09468110912076427,
        14,
        4
      ]
    ],
    "keylemmas": [
      "say",
      "dog",
      "man",
      "make",
      "reply",
      "patient",
      "ask",
      "sun",
      "bite",
      "doctor",
      "child",
      "frog",
      "one",
      "now",
      "ox",
      "single",
      "time",
      "sky",
      "honey",
      "feel",
      "gown",
      "fell",
      "treasure",
      "bee",
      "swim",
      "notice",
      "inside",
      "good",
      "symptom",
      "positive",
      "thales",
      "gaze",
      "astronomer",
      "wound",
      "family",
      "die",
      "athena",
      "pray",
      "athenian",
      "dead",
      "cure",
      "big",
      "passenger",
      "hen",
      "moon",
      "profit"
    ],
    "threshold_ke": [
      0.2,
      0.03
    ],
    "keywords": [
      "said",
      "dog",
      "man",
      "making",
      "made",
      "make",
      "replied",
      "patient",
      "asked",
      "sun",
      "suns",
      "bite",
      "bitten",
      "doctor",
      "children",
      "frogs",
      "frog",
      "one",
      "now",
      "ox",
      "single",
      "time",
      "sky",
      "honey",
      "feeling",
      "feel",
      "gown",
      "fell",
      "treasure",
      "bees",
      "swim",
      "swam",
      "notice",
      "noticed",
      "inside",
      "good",
      "symptom",
      "symptoms",
      "positive",
      "thales",
      "gazing",
      "astronomer",
      "wound",
      "family",
      "die",
      "dying",
      "athena",
      "praying",
      "pray",
      "athenian",
      "dead",
      "cured",
      "bigger",
      "passengers",
      "hen",
      "moon",
      "profit"
    ],
    "all_bigrams": 18,
    "bigram_keyphrases": [
      [
        [
          "man",
          "replied"
        ],
        2
      ],
      [
        [
          "doctor",
          "asked"
        ],
        2
      ],
      [
        [
          "treasure",
          "inside"
        ],
        2
      ],
      [
        [
          "single",
          "dog"
        ],
        1
      ],
      [
        [
          "dog",
          "replied"
        ],
        1
      ],
      [
        [
          "now",
          "bigger"
        ],
        1
      ],
      [
        [
          "positive",
          "symptoms"
        ],
        1
      ],
      [
        [
          "family",
          "asked"
        ],
        1
      ],
      [
        [
          "positive",
          "symptom"
        ],
        1
      ],
      [
        [
          "doctor",
          "said"
        ],
        1
      ],
      [
        [
          "patient",
          "said"
        ],
        1
      ],
      [
        [
          "dog",
          "said"
        ],
        1
      ],
      [
        [
          "making",
          "honey"
        ],
        1
      ],
      [
        [
          "one",
          "time"
        ],
        1
      ],
      [
        [
          "passengers",
          "swam"
        ],
        1
      ]
    ],
    "trigram_keyphrases": [],
    "quadgram_keyphrases": [],
    "kls_in_ass_q_long": [],
    "kls_in_ass_q_short": [],
    "kls_in_tb_index": [],
    "scoresNfreqs": [
      [
        "say",
        0.4240237526567572,
        0,
        8
      ],
      [
        "dog",
        0.33004963871651066,
        1,
        8
      ],
      [
        "man",
        0.3187750570447613,
        2,
        9
      ],
      [
        "make",
        0.18329134945514244,
        3,
        4
      ],
      [
        "reply",
        0.1694363586604965,
        4,
        4
      ],
      [
        "patient",
        0.14606846526489373,
        5,
        4
      ],
      [
        "ask",
        0.14379225044249666,
        6,
        5
      ],
      [
        "sun",
        0.1415380049616502,
        7,
        3
      ],
      [
        "bite",
        0.12674039835369885,
        8,
        4
      ],
      [
        "doctor",
        0.11926344728068865,
        9,
        4
      ],
      [
        "child",
        0.11713848548823919,
        10,
        3
      ],
      [
        "frog",
        0.1117356712800062,
        11,
        4
      ],
      [
        "one",
        0.10318331094193162,
        12,
        4
      ],
      [
        "now",
        0.09909014938263713,
        13,
        2
      ],
      [
        "ox",
        0.09468110912076427,
        14,
        4
      ],
      [
        "single",
        0.09380595629056221,
        15,
        2
      ],
      [
        "time",
        0.08811327561327562,
        16,
        3
      ],
      [
        "sky",
        0.08624577051301188,
        17,
        2
      ],
      [
        "honey",
        0.0817426108374384,
        18,
        3
      ],
      [
        "feel",
        0.0759219348659004,
        19,
        2
      ],
      [
        "gown",
        0.07447691197691197,
        20,
        1
      ],
      [
        "fell",
        0.07183130566751257,
        21,
        2
      ],
      [
        "treasure",
        0.06795044036423346,
        22,
        3
      ],
      [
        "bee",
        0.06785527690700104,
        23,
        2
      ],
      [
        "swim",
        0.06407050803602526,
        24,
        2
      ],
      [
        "notice",
        0.06359322464618032,
        25,
        2
      ],
      [
        "inside",
        0.061718166890580684,
        26,
        2
      ],
      [
        "good",
        0.06012091356918943,
        27,
        2
      ],
      [
        "symptom",
        0.05878427128427129,
        28,
        2
      ],
      [
        "positive",
        0.05878427128427129,
        29,
        2
      ],
      [
        "thales",
        0.05792593421903765,
        30,
        1
      ],
      [
        "gaze",
        0.05792593421903765,
        31,
        1
      ],
      [
        "astronomer",
        0.05792593421903765,
        32,
        1
      ],
      [
        "wound",
        0.057795939692491416,
        33,
        3
      ],
      [
        "family",
        0.05173097974822112,
        34,
        1
      ],
      [
        "die",
        0.051409414340448836,
        35,
        2
      ],
      [
        "athena",
        0.05059523809523808,
        36,
        2
      ],
      [
        "pray",
        0.05059523809523808,
        37,
        2
      ],
      [
        "athenian",
        0.04776023784644473,
        38,
        2
      ],
      [
        "dead",
        0.04613717221475843,
        39,
        1
      ],
      [
        "cure",
        0.045741901776384535,
        40,
        1
      ],
      [
        "big",
        0.04560124467759937,
        41,
        3
      ],
      [
        "passenger",
        0.04377519032691446,
        42,
        2
      ],
      [
        "hen",
        0.04367722794446932,
        43,
        3
      ],
      [
        "moon",
        0.04102851171816689,
        44,
        3
      ],
      [
        "profit",
        0.04013658755038065,
        45,
        2
      ],
      [
        "member",
        0.03936968204209582,
        46,
        1
      ],
      [
        "full",
        0.03936532815843163,
        47,
        2
      ],
      [
        "shepherd",
        0.038721353222584756,
        48,
        2
      ],
      [
        "start",
        0.03839192416778623,
        49,
        2
      ],
      [
        "bell",
        0.03828929690998656,
        50,
        2
      ],
      [
        "deal",
        0.0378818977956909,
        51,
        1
      ],
      [
        "mean",
        0.0378818977956909,
        52,
        1
      ],
      [
        "past",
        0.03751492760113448,
        53,
        1
      ],
      [
        "sting",
        0.036765837473965564,
        54,
        2
      ],
      [
        "honeybee",
        0.03527578743095984,
        55,
        2
      ],
      [
        "fill",
        0.034566726377071214,
        56,
        1
      ],
      [
        "next",
        0.03442678011643529,
        57,
        1
      ],
      [
        "sign",
        0.03442678011643529,
        58,
        1
      ],
      [
        "actually",
        0.03438013136288998,
        59,
        1
      ],
      [
        "daily",
        0.033904314076727865,
        60,
        1
      ],
      [
        "satisfy",
        0.033904314076727865,
        61,
        1
      ],
      [
        "onto",
        0.03362753147235906,
        62,
        2
      ],
      [
        "re",
        0.03342165497337911,
        63,
        3
      ],
      [
        "hope",
        0.03189375279892522,
        64,
        2
      ],
      [
        "know",
        0.03179579041648007,
        65,
        2
      ],
      [
        "puff",
        0.03046909986565159,
        66,
        2
      ],
      [
        "even",
        0.030288102701895803,
        67,
        2
      ],
      [
        "hard",
        0.0302881027018958,
        68,
        2
      ],
      [
        "bit",
        0.03023585609792506,
        69,
        1
      ],
      [
        "ship",
        0.029780564263322883,
        70,
        1
      ],
      [
        "storm",
        0.029780564263322883,
        71,
        1
      ],
      [
        "terrible",
        0.029780564263322883,
        72,
        1
      ],
      [
        "companion",
        0.029780564263322883,
        73,
        1
      ],
      [
        "voyage",
        0.029780564263322883,
        74,
        1
      ],
      [
        "sea",
        0.029780564263322883,
        75,
        1
      ],
      [
        "blew",
        0.029780564263322883,
        76,
        1
      ],
      [
        "capsize",
        0.029780564263322876,
        77,
        1
      ],
      [
        "figure",
        0.028350002487933516,
        78,
        1
      ],
      [
        "fitting",
        0.028350002487933516,
        79,
        1
      ],
      [
        "tie",
        0.027432577001542512,
        80,
        1
      ],
      [
        "size",
        0.027247225954122507,
        81,
        1
      ],
      [
        "someone",
        0.027115587614356076,
        82,
        2
      ],
      [
        "discover",
        0.025644374782305815,
        83,
        1
      ],
      [
        "inquire",
        0.025582176444245406,
        84,
        1
      ],
      [
        "cause",
        0.025582176444245406,
        85,
        1
      ],
      [
        "croaking",
        0.025582176444245406,
        86,
        1
      ],
      [
        "jupiter",
        0.025582176444245406,
        87,
        1
      ],
      [
        "noise",
        0.025582176444245406,
        88,
        1
      ],
      [
        "disturb",
        0.025582176444245406,
        89,
        1
      ],
      [
        "complaint",
        0.025582176444245406,
        90,
        1
      ],
      [
        "need",
        0.02476115838184803,
        91,
        2
      ],
      [
        "bread",
        0.02463054187192118,
        92,
        2
      ],
      [
        "lift",
        0.02399083196496989,
        93,
        1
      ],
      [
        "voice",
        0.023990831964969886,
        94,
        1
      ],
      [
        "clamor",
        0.023990831964969882,
        95,
        1
      ],
      [
        "neither",
        0.023099840772254562,
        96,
        1
      ],
      [
        "assure",
        0.022495983764456665,
        97,
        1
      ],
      [
        "brother",
        0.02235874757426482,
        98,
        1
      ],
      [
        "dear",
        0.022358747574264815,
        99,
        1
      ],
      [
        "future",
        0.021761643528884905,
        100,
        1
      ],
      [
        "beget",
        0.021761643528884905,
        101,
        1
      ],
      [
        "home",
        0.021761643528884905,
        102,
        1
      ],
      [
        "miserably",
        0.021761643528884905,
        103,
        1
      ],
      [
        "condition",
        0.021761643528884905,
        104,
        1
      ],
      [
        "arid",
        0.021761643528884905,
        105,
        1
      ],
      [
        "proud",
        0.02141177787729512,
        106,
        1
      ],
      [
        "creature",
        0.02141177787729512,
        107,
        1
      ],
      [
        "decoration",
        0.02141177787729512,
        108,
        1
      ],
      [
        "wretched",
        0.02141177787729512,
        109,
        1
      ],
      [
        "bravery",
        0.02141177787729512,
        110,
        1
      ],
      [
        "fever",
        0.02137756879136189,
        111,
        1
      ],
      [
        "confine",
        0.02137756879136189,
        112,
        1
      ],
      [
        "high",
        0.02137756879136189,
        113,
        1
      ],
      [
        "bed",
        0.02137756879136189,
        114,
        1
      ],
      [
        "suffer",
        0.02137756879136189,
        115,
        1
      ],
      [
        "awful",
        0.02137756879136189,
        116,
        1
      ],
      [
        "evil",
        0.021365129123749813,
        117,
        1
      ],
      [
        "slave",
        0.021365129123749813,
        118,
        1
      ],
      [
        "deed",
        0.021365129123749813,
        119,
        1
      ],
      [
        "pit",
        0.021365129123749813,
        120,
        1
      ],
      [
        "woman",
        0.021365129123749813,
        121,
        1
      ],
      [
        "behavior",
        0.021365129123749813,
        122,
        1
      ],
      [
        "wise",
        0.021365129123749813,
        123,
        1
      ],
      [
        "shamefully",
        0.021365129123749813,
        124,
        1
      ],
      [
        "drum",
        0.021365129123749813,
        125,
        1
      ],
      [
        "witty",
        0.021365129123749813,
        126,
        1
      ],
      [
        "thracian",
        0.021365129123749813,
        127,
        1
      ],
      [
        "beat",
        0.021365129123749813,
        128,
        1
      ],
      [
        "look",
        0.021256504169065736,
        129,
        1
      ],
      [
        "hand",
        0.021204657411553963,
        130,
        1
      ],
      [
        "hold",
        0.021204657411553963,
        131,
        1
      ],
      [
        "wealthy",
        0.021204657411553963,
        132,
        1
      ],
      [
        "piece",
        0.020301537542916852,
        133,
        1
      ],
      [
        "find",
        0.01880131362889983,
        134,
        2
      ],
      [
        "end",
        0.01848667889308283,
        135,
        1
      ],
      [
        "fun",
        0.018469796487037865,
        136,
        1
      ],
      [
        "eager",
        0.018469796487037865,
        137,
        1
      ],
      [
        "marketplace",
        0.01815569487983281,
        138,
        1
      ],
      [
        "parade",
        0.01815569487983281,
        139,
        1
      ],
      [
        "shake",
        0.01815569487983281,
        140,
        1
      ],
      [
        "mother",
        0.016582076926904513,
        141,
        1
      ],
      [
        "beg",
        0.016582076926904513,
        142,
        1
      ],
      [
        "very",
        0.01605152510324924,
        143,
        1
      ],
      [
        "worry",
        0.01605152510324924,
        144,
        1
      ],
      [
        "shiver",
        0.01605152510324924,
        145,
        1
      ],
      [
        "woe",
        0.01605152510324924,
        146,
        1
      ],
      [
        "come",
        0.0159538737124944,
        147,
        1
      ],
      [
        "tree",
        0.015905670000497602,
        148,
        1
      ],
      [
        "oak",
        0.015905670000497592,
        149,
        1
      ],
      [
        "hollow",
        0.01590567000049759,
        150,
        1
      ],
      [
        "forge",
        0.015804597701149423,
        151,
        1
      ],
      [
        "master",
        0.015804597701149423,
        152,
        1
      ],
      [
        "people",
        0.015804597701149423,
        153,
        1
      ],
      [
        "inflate",
        0.014972383937901177,
        154,
        1
      ],
      [
        "skin",
        0.014972383937901177,
        155,
        1
      ],
      [
        "wrinkle",
        0.014972383937901176,
        156,
        1
      ],
      [
        "foolishly",
        0.014602925809822361,
        157,
        1
      ],
      [
        "grasp",
        0.01460292580982236,
        158,
        1
      ],
      [
        "expect",
        0.01460292580982236,
        159,
        1
      ],
      [
        "try",
        0.01413146240732448,
        160,
        1
      ],
      [
        "indignant",
        0.01413146240732448,
        161,
        1
      ],
      [
        "around",
        0.014090100512514307,
        162,
        1
      ],
      [
        "fly",
        0.014090100512514305,
        163,
        1
      ],
      [
        "wife",
        0.013811140966313383,
        164,
        1
      ],
      [
        "take",
        0.01381114096631338,
        165,
        1
      ],
      [
        "intention",
        0.01381114096631338,
        166,
        1
      ],
      [
        "announce",
        0.013811140966313378,
        167,
        1
      ],
      [
        "making",
        0.013164278250485145,
        168,
        1
      ],
      [
        "kind",
        0.013164278250485145,
        169,
        1
      ],
      [
        "promise",
        0.013164278250485145,
        170,
        1
      ],
      [
        "save",
        0.013164278250485145,
        171,
        1
      ],
      [
        "u",
        0.012875055978504253,
        172,
        1
      ],
      [
        "seize",
        0.012875055978504253,
        173,
        1
      ],
      [
        "attempt",
        0.012875055978504253,
        174,
        1
      ],
      [
        "work",
        0.012875055978504253,
        175,
        1
      ],
      [
        "parches",
        0.012875055978504253,
        176,
        1
      ],
      [
        "egg",
        0.012875055978504253,
        177,
        1
      ],
      [
        "carry",
        0.012875055978504253,
        178,
        1
      ],
      [
        "jealous",
        0.012875055978504253,
        179,
        1
      ],
      [
        "compels",
        0.012875055978504253,
        180,
        1
      ],
      [
        "laid",
        0.012875055978504253,
        181,
        1
      ],
      [
        "away",
        0.012875055978504253,
        182,
        1
      ],
      [
        "desire",
        0.012875055978504253,
        183,
        1
      ],
      [
        "day",
        0.012875055978504253,
        184,
        1
      ],
      [
        "marsh",
        0.012875055978504253,
        185,
        1
      ],
      [
        "golden",
        0.012875055978504253,
        186,
        1
      ],
      [
        "equal",
        0.012875055978504251,
        187,
        1
      ],
      [
        "upon",
        0.012806637806637808,
        188,
        1
      ],
      [
        "arm",
        0.012806637806637806,
        189,
        1
      ],
      [
        "move",
        0.012806637806637806,
        190,
        1
      ],
      [
        "air",
        0.011827635965566996,
        191,
        1
      ],
      [
        "strain",
        0.011827635965566992,
        192,
        1
      ],
      [
        "tell",
        0.011684179941569102,
        193,
        1
      ],
      [
        "head",
        0.01151291237498134,
        194,
        1
      ],
      [
        "happen",
        0.01151291237498134,
        195,
        1
      ],
      [
        "fail",
        0.01151291237498134,
        196,
        1
      ],
      [
        "body",
        0.011167711598746079,
        197,
        1
      ],
      [
        "explode",
        0.011167711598746079,
        198,
        1
      ],
      [
        "heal",
        0.011102403343782654,
        199,
        1
      ],
      [
        "two",
        0.010318948634835328,
        200,
        1
      ],
      [
        "shipwrecked",
        0.009665621734587251,
        201,
        1
      ],
      [
        "everyone",
        0.008996989600437875,
        202,
        1
      ],
      [
        "give",
        0.0087479741112746,
        203,
        1
      ],
      [
        "exclaim",
        0.0087479741112746,
        204,
        1
      ],
      [
        "well",
        0.00860203015375429,
        205,
        1
      ],
      [
        "forth",
        0.00860203015375429,
        206,
        1
      ],
      [
        "back",
        0.00860203015375429,
        207,
        1
      ],
      [
        "old",
        0.00860203015375429,
        208,
        1
      ],
      [
        "get",
        0.00860203015375429,
        209,
        1
      ],
      [
        "soon",
        0.00860203015375429,
        210,
        1
      ],
      [
        "right",
        0.007967607105538139,
        211,
        1
      ],
      [
        "foot",
        0.007967607105538139,
        212,
        1
      ],
      [
        "while",
        0.007894213066626857,
        213,
        1
      ],
      [
        "another",
        0.007894213066626857,
        214,
        1
      ],
      [
        "blood",
        0.00779967159277504,
        215,
        1
      ],
      [
        "let",
        0.00779967159277504,
        216,
        1
      ],
      [
        "sneak",
        0.005784445439617853,
        217,
        1
      ],
      [
        "want",
        0.005784445439617853,
        218,
        1
      ],
      [
        "use",
        0.005784445439617853,
        219,
        1
      ],
      [
        "city",
        0.005784445439617853,
        220,
        1
      ],
      [
        "meadow",
        0.005308628153455741,
        221,
        1
      ],
      [
        "standing",
        0.00530862815345574,
        222,
        1
      ],
      [
        "chase",
        0.005206000895656067,
        223,
        1
      ],
      [
        "remark",
        0.005206000895656067,
        224,
        1
      ],
      [
        "kept",
        0.0025097029407374232,
        225,
        1
      ],
      [
        "lose",
        0.0019032691446484549,
        226,
        1
      ],
      [
        "drip",
        0.0018659501418122105,
        227,
        1
      ],
      [
        "run",
        0.0015300791162860127,
        228,
        1
      ],
      [
        "new",
        0.0011942080907598146,
        229,
        1
      ],
      [
        "badly",
        0.0,
        230,
        1
      ],
      [
        "fee",
        0.0,
        231,
        1
      ],
      [
        "slaughter",
        0.0,
        232,
        1
      ]
    ]
  },
  "parasenttok": [
    [
      "Introduction"
    ],
    [
      "A man had a hen that laid a golden egg for him each and every day.",
      "The man was not satisfied with this daily profit.",
      "Instead, he foolishly grasped for more.",
      "Expecting to find a treasure inside, the man slaughtered the hen.",
      "When he found that the hen did not have a treasure inside her after all, he remarked to himself, \"While chasing after hopes of a treasure, I lost the profit I held in my hands!",
      "\""
    ],
    [
      "Heading One"
    ],
    [
      "A wealthy Athenian was making a sea voyage with some companions.",
      "A terrible storm blew up and the ship capsized.",
      "All the other passengers started to swim, but the Athenian kept praying to Athena, making all kinds of promises if only she would save him.",
      "Then one of the other shipwrecked passengers swam past him and said, \"While you pray to Athena, start moving your arms!",
      "\""
    ],
    [
      "Heading Two"
    ],
    [
      "Once upon a time, when the sun announced his intention to take a wife, the frogs lifted up their voices in clamor to the sky.",
      "Jupiter, disturbed by the noise of their croaking, inquired the cause of their complaint.",
      "One of them said, \"The sun, now while he is single, parches up the marsh, and compels us to die miserably in our arid homes.",
      "What will be our future condition if he should beget other suns?",
      "\""
    ],
    [
      "Heading Three"
    ],
    [
      "When Thales the astronomer was gazing up at the sky, he fell into a pit.",
      "A Thracian slave woman, who was both wise and witty, is said to have made fun of him for being eager to know what was happening over his head while failing to notice what was right there at his feet."
    ],
    [
      "Heading Four"
    ],
    [
      "The moon once begged her mother to make her a gown.",
      "\"How can I?",
      "\" replied she.",
      "\"There's no fitting your figure."
    ],
    [
      "Heading Four Point Five"
    ],
    [
      "At one time you're a new moon, and at another you're a Full Moon, and between whiles you're neither one nor the other.",
      "\""
    ],
    [
      "Heading Five"
    ],
    [
      "Some honeybees were making honey in the hollow of an oak tree.",
      "A shepherd discovered the bees' work and attempted to carry away some of the honey.",
      "The honeybees flew all around him, stinging the man with their stings.",
      "In the end the shepherd exclaimed, \"I give up!",
      "I don't need the honey if it means dealing with the bees.",
      "\""
    ],
    [
      "Heading Six"
    ],
    [
      "There was a dog who used to sneak up and bite people.",
      "His master forged a bell for the dog and tied it onto him so that everyone would know when he was coming.",
      "The dog then paraded about the marketplace, shaking his bell back and forth.",
      "An old dog said to him, \"You wretched creature!",
      "Why are you so proud of yourself?",
      "This is not a decoration for bravery or good behavior.",
      "You are shamefully beating the drum of your own evil deeds!",
      "\""
    ],
    [
      "Heading Seven"
    ],
    [
      "The doctor asked his patient, \"How are you feeling?",
      "\"\" The patient said, \"Woe is me!",
      "I'm shivering all over, which has me very worried.",
      "\" The doctor assured the patient that this was actually a good sign.",
      "The next time the doctor asked the patient how he was doing, the man replied, \"I feel awful!",
      "I'm suffering from a high fever which has me confined to bed.",
      "\" Again the doctor said that this was a positive symptom.",
      "Finally a member of the man's family asked, \"How are you doing, my dear brother?",
      "I hope you get well soon!",
      "\" The man replied, \"I'm dying of positive symptoms!",
      "\""
    ],
    [
      "Heading Eight"
    ],
    [
      "There was once a frog who noticed an ox standing in the meadow.",
      "The frog was seized by a jealous desire to equal the ox in size so she puffed herself up, inflating her wrinkled skin.",
      "She then asked her children if she was now bigger than the ox.",
      "They said that she was not.",
      "Once again she filled herself full of air, straining even harder than before, and asked her children which of the two of them was bigger.",
      "\"The ox is bigger,\" said her children.",
      "The frog was finally so indignant that she tried even harder to puff herself up, but her body exploded and she fell down dead."
    ],
    [
      "Heading Nine"
    ],
    [
      "A man who had just been badly bitten by a dog was looking for someone who could heal his wound.",
      "He ran into someone who told him, \"Here is what you need to do: let the blood from your wound drip onto a piece of bread and then feed the bread to the dog who bit you.",
      "If you do that, your wound will be cured.",
      "\" The man who had been bitten by the dog replied, \"But if I do that, every single dog in the city will want to bite me!",
      "\""
    ]
  ],
  "refs": {
    "len_refs": 0,
    "refsheaded": false
  },
  "concl": {
    "conclheaded": false,
    "c_first": 21,
    "c_last": 21,
    "countConclSent": 5,
    "percent_body_c": 10.68,
    "c_toprank": 2
  },
  "intro": {
    "introheaded": [],
    "i_first": 1,
    "i_last": 1,
    "countIntroSent": 6,
    "percent_body_i": 9.97,
    "i_toprank": 4
  },
  "appendix": {
    "appendixheaded": false
  },
  "version": "3.2.0",
  "ke_sample_graph": "{\"directed\": true, \"graph\": [], \"nodes\": [{\"id\": \"gown\"}, {\"id\": \"family\"}, {\"id\": \"passenger\"}, {\"id\": \"feel\"}, {\"id\": \"wound\"}, {\"id\": \"dead\"}, {\"id\": \"one\"}, {\"id\": \"honey\"}, {\"id\": \"say\"}, {\"id\": \"cure\"}, {\"id\": \"astronomer\"}, {\"id\": \"die\"}, {\"id\": \"positive\"}, {\"id\": \"doctor\"}, {\"id\": \"pray\"}, {\"id\": \"sun\"}, {\"id\": \"make\"}, {\"id\": \"treasure\"}, {\"id\": \"sky\"}, {\"id\": \"frog\"}, {\"id\": \"moon\"}, {\"id\": \"gaze\"}, {\"id\": \"symptom\"}, {\"id\": \"reply\"}, {\"id\": \"hen\"}, {\"id\": \"athena\"}, {\"id\": \"swim\"}, {\"id\": \"notice\"}, {\"id\": \"good\"}, {\"id\": \"patient\"}, {\"id\": \"big\"}, {\"id\": \"time\"}, {\"id\": \"fell\"}, {\"id\": \"child\"}, {\"id\": \"ask\"}, {\"id\": \"now\"}, {\"id\": \"single\"}, {\"id\": \"man\"}, {\"id\": \"thales\"}, {\"id\": \"inside\"}, {\"id\": \"dog\"}, {\"id\": \"bee\"}, {\"id\": \"athenian\"}, {\"id\": \"profit\"}, {\"id\": \"ox\"}, {\"id\": \"bite\"}], \"links\": [{\"source\": 0, \"target\": 23}, {\"source\": 1, \"target\": 34}, {\"source\": 2, \"target\": 26}, {\"source\": 3, \"target\": 29}, {\"source\": 18, \"target\": 32}, {\"source\": 5, \"target\": 37}, {\"source\": 6, \"target\": 8}, {\"source\": 6, \"target\": 31}, {\"source\": 10, \"target\": 21}, {\"source\": 9, \"target\": 37}, {\"source\": 38, \"target\": 10}, {\"source\": 13, \"target\": 34}, {\"source\": 13, \"target\": 8}, {\"source\": 14, \"target\": 25}, {\"source\": 12, \"target\": 22}, {\"source\": 16, \"target\": 7}, {\"source\": 16, \"target\": 0}, {\"source\": 17, \"target\": 39}, {\"source\": 4, \"target\": 9}, {\"source\": 19, \"target\": 27}, {\"source\": 21, \"target\": 18}, {\"source\": 23, \"target\": 3}, {\"source\": 23, \"target\": 11}, {\"source\": 23, \"target\": 36}, {\"source\": 24, \"target\": 17}, {\"source\": 36, \"target\": 40}, {\"source\": 26, \"target\": 42}, {\"source\": 27, \"target\": 44}, {\"source\": 29, \"target\": 3}, {\"source\": 29, \"target\": 8}, {\"source\": 29, \"target\": 37}, {\"source\": 30, \"target\": 8}, {\"source\": 30, \"target\": 44}, {\"source\": 31, \"target\": 15}, {\"source\": 31, \"target\": 13}, {\"source\": 32, \"target\": 5}, {\"source\": 33, \"target\": 35}, {\"source\": 33, \"target\": 19}, {\"source\": 34, \"target\": 29}, {\"source\": 34, \"target\": 33}, {\"source\": 35, \"target\": 30}, {\"source\": 35, \"target\": 36}, {\"source\": 22, \"target\": 19}, {\"source\": 37, \"target\": 23}, {\"source\": 37, \"target\": 45}, {\"source\": 37, \"target\": 24}, {\"source\": 37, \"target\": 1}, {\"source\": 44, \"target\": 30}, {\"source\": 44, \"target\": 8}, {\"source\": 11, \"target\": 12}, {\"source\": 39, \"target\": 37}, {\"source\": 40, \"target\": 23}, {\"source\": 40, \"target\": 8}, {\"source\": 8, \"target\": 14}, {\"source\": 8, \"target\": 15}, {\"source\": 8, \"target\": 16}, {\"source\": 8, \"target\": 12}, {\"source\": 8, \"target\": 33}, {\"source\": 41, \"target\": 40}, {\"source\": 42, \"target\": 16}, {\"source\": 15, \"target\": 38}, {\"source\": 15, \"target\": 35}, {\"source\": 45, \"target\": 40}], \"multigraph\": false}",
  "se_stats": {
    "paras": 22,
    "len_body": 55,
    "len_headings": 11,
    "all_sents": 72,
    "countTrueSent": 55,
    "number_of_words": 779,
    "countAvSentLen": 6.55,
    "countAssQSent": 0,
    "countTitleSent": 1
  },
  "se_graph": {
    "nodes": 72,
    "edges": 156,
    "edges_over_sents": 2.84
  },
  "se_sample_graph": "{\"directed\": true, \"graph\": [], \"nodes\": [{\"id\": 64}, {\"id\": 33}, {\"id\": 67}, {\"id\": 68}, {\"id\": 5}, {\"id\": 70}, {\"id\": 65}, {\"id\": 41}, {\"id\": 61}, {\"id\": 48}, {\"id\": 50}, {\"id\": 51}, {\"id\": 21}, {\"id\": 54}, {\"id\": 56}, {\"id\": 63}, {\"id\": 28}, {\"id\": 53}, {\"id\": 62}, {\"id\": 31}], \"links\": [{\"source\": 0, \"target\": 7, \"weight\": 0.22360679774997896}, {\"source\": 0, \"target\": 9, \"weight\": 0.2886751345948129}, {\"source\": 0, \"target\": 17, \"weight\": 0.25}, {\"source\": 0, \"target\": 12, \"weight\": 0.125}, {\"source\": 0, \"target\": 8, \"weight\": 0.6708203932499369}, {\"source\": 0, \"target\": 18, \"weight\": 0.5}, {\"source\": 0, \"target\": 15, \"weight\": 0.31622776601683794}, {\"source\": 1, \"target\": 19, \"weight\": 0.14433756729740646}, {\"source\": 2, \"target\": 14, \"weight\": 0.15811388300841897}, {\"source\": 2, \"target\": 1, \"weight\": 0.12499999999999997}, {\"source\": 2, \"target\": 11, \"weight\": 0.1178511301977579}, {\"source\": 2, \"target\": 13, \"weight\": 0.14433756729740646}, {\"source\": 2, \"target\": 7, \"weight\": 0.15811388300841897}, {\"source\": 3, \"target\": 7, \"weight\": 0.10846522890932808}, {\"source\": 3, \"target\": 2, \"weight\": 0.25724787771376323}, {\"source\": 5, \"target\": 1, \"weight\": 0.09805806756909201}, {\"source\": 5, \"target\": 2, \"weight\": 0.49029033784546}, {\"source\": 5, \"target\": 3, \"weight\": 0.13453455879926252}, {\"source\": 5, \"target\": 7, \"weight\": 0.24806946917841693}, {\"source\": 5, \"target\": 11, \"weight\": 0.18490006540840973}, {\"source\": 5, \"target\": 13, \"weight\": 0.11322770341445959}, {\"source\": 5, \"target\": 14, \"weight\": 0.24806946917841693}, {\"source\": 6, \"target\": 15, \"weight\": 0.19999999999999996}, {\"source\": 19, \"target\": 12, \"weight\": 0.10206207261596577}, {\"source\": 7, \"target\": 12, \"weight\": 0.11180339887498948}, {\"source\": 9, \"target\": 7, \"weight\": 0.2581988897471611}, {\"source\": 9, \"target\": 12, \"weight\": 0.14433756729740646}, {\"source\": 10, \"target\": 9, \"weight\": 0.23570226039551587}, {\"source\": 11, \"target\": 9, \"weight\": 0.19245008972987526}, {\"source\": 11, \"target\": 1, \"weight\": 0.1178511301977579}, {\"source\": 11, \"target\": 10, \"weight\": 0.2721655269759087}, {\"source\": 11, \"target\": 16, \"weight\": 0.06950480468569159}, {\"source\": 13, \"target\": 1, \"weight\": 0.14433756729740646}, {\"source\": 13, \"target\": 11, \"weight\": 0.2721655269759087}, {\"source\": 14, \"target\": 1, \"weight\": 0.15811388300841897}, {\"source\": 14, \"target\": 11, \"weight\": 0.29814239699997197}, {\"source\": 14, \"target\": 17, \"weight\": 0.4472135954999579}, {\"source\": 14, \"target\": 13, \"weight\": 0.18257418583505536}, {\"source\": 17, \"target\": 9, \"weight\": 0.2886751345948129}, {\"source\": 17, \"target\": 7, \"weight\": 0.22360679774997896}, {\"source\": 17, \"target\": 10, \"weight\": 0.20412414523193154}, {\"source\": 17, \"target\": 11, \"weight\": 0.16666666666666666}, {\"source\": 17, \"target\": 12, \"weight\": 0.125}, {\"source\": 8, \"target\": 11, \"weight\": 0.14907119849998599}, {\"source\": 8, \"target\": 13, \"weight\": 0.18257418583505536}, {\"source\": 18, \"target\": 9, \"weight\": 0.5773502691896258}, {\"source\": 18, \"target\": 7, \"weight\": 0.4472135954999579}, {\"source\": 18, \"target\": 17, \"weight\": 0.5}, {\"source\": 18, \"target\": 12, \"weight\": 0.25}, {\"source\": 15, \"target\": 11, \"weight\": 0.10540925533894598}, {\"source\": 15, \"target\": 16, \"weight\": 0.0659380473395787}, {\"source\": 15, \"target\": 8, \"weight\": 0.42426406871192845}, {\"source\": 15, \"target\": 13, \"weight\": 0.12909944487358058}], \"multigraph\": false}",
  "se_data": {
    "se_ranked": [
      [
        0.0021551716443478203,
        14,
        "#+s#"
      ],
      [
        0.0021435375869661637,
        1,
        "#+s:i#"
      ],
      [
        0.002143285118987026,
        8,
        "#+s#"
      ],
      [
        0.00213119873957626,
        21,
        "#+s#"
      ],
      [
        0.0021291238150068286,
        11,
        "#+s#"
      ],
      [
        0.0021281144896515922,
        47,
        "#+s#"
      ],
      [
        0.0021268950949044053,
        16,
        "#+s#"
      ],
      [
        0.0021265209100815082,
        38,
        "#+s#"
      ],
      [
        0.0021235447969425833,
        32,
        "#+s#"
      ],
      [
        0.0021233698481829097,
        31,
        "#+s#"
      ],
      [
        0.002116976046258408,
        2,
        "#+s:i#"
      ],
      [
        0.00211504223927679,
        4,
        "#+s:i#"
      ],
      [
        0.002113346254109833,
        59,
        "#+s#"
      ],
      [
        0.002110244463724926,
        39,
        "#+s#"
      ],
      [
        0.0021093958240552277,
        67,
        "#+s:c#"
      ]
    ],
    "se_parasenttok": [
      [
        {
          "text": "Introduction",
          "tag": "#-s:t#",
          "id": 0,
          "lemma": [
            "introduction"
          ]
        }
      ],
      [
        {
          "text": "A man had a hen that laid a golden egg for him each and every day.",
          "tag": "#+s:i#",
          "id": 1,
          "rank": 1,
          "lemma": [
            "man",
            "hen",
            "laid",
            "golden",
            "egg",
            "day"
          ]
        },
        {
          "text": "The man was not satisfied with this daily profit.",
          "tag": "#+s:i#",
          "id": 2,
          "rank": 10,
          "lemma": [
            "man",
            "satisfy",
            "daily",
            "profit"
          ]
        },
        {
          "text": "Instead, he foolishly grasped for more.",
          "tag": "#+s:i#",
          "id": 3,
          "lemma": [
            "foolishly",
            "grasp"
          ]
        },
        {
          "text": "Expecting to find a treasure inside, the man slaughtered the hen.",
          "tag": "#+s:i#",
          "id": 4,
          "rank": 11,
          "lemma": [
            "expect",
            "find",
            "treasure",
            "inside",
            "man",
            "slaughter",
            "hen"
          ]
        },
        {
          "text": "When he found that the hen did not have a treasure inside her after all, he remarked to himself, \"While chasing after hopes of a treasure, I lost the profit I held in my hands!",
          "tag": "#+s:i#",
          "id": 5,
          "lemma": [
            "find",
            "hen",
            "treasure",
            "inside",
            "remark",
            "chase",
            "hope",
            "treasure",
            "lose",
            "profit",
            "hold",
            "hand"
          ]
        },
        {
          "text": "\"",
          "tag": "#+s:i#",
          "id": 6,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading One",
          "tag": "#-s:h#",
          "id": 7,
          "lemma": [
            "heading",
            "one"
          ]
        }
      ],
      [
        {
          "text": "A wealthy Athenian was making a sea voyage with some companions.",
          "tag": "#+s#",
          "id": 8,
          "rank": 2,
          "lemma": [
            "wealthy",
            "athenian",
            "make",
            "sea",
            "voyage",
            "companion"
          ]
        },
        {
          "text": "A terrible storm blew up and the ship capsized.",
          "tag": "#+s#",
          "id": 9,
          "lemma": [
            "terrible",
            "storm",
            "blew",
            "ship",
            "capsize"
          ]
        },
        {
          "text": "All the other passengers started to swim, but the Athenian kept praying to Athena, making all kinds of promises if only she would save him.",
          "tag": "#+s#",
          "id": 10,
          "lemma": [
            "passenger",
            "start",
            "swim",
            "athenian",
            "kept",
            "pray",
            "athena",
            "making",
            "kind",
            "promise",
            "save"
          ]
        },
        {
          "text": "Then one of the other shipwrecked passengers swam past him and said, \"While you pray to Athena, start moving your arms!",
          "tag": "#+s#",
          "id": 11,
          "rank": 4,
          "lemma": [
            "one",
            "shipwrecked",
            "passenger",
            "swim",
            "past",
            "say",
            "pray",
            "athena",
            "start",
            "move",
            "arm"
          ]
        },
        {
          "text": "\"",
          "tag": "#-s:p#",
          "id": 12,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading Two",
          "tag": "#-s:h#",
          "id": 13,
          "lemma": [
            "heading",
            "two"
          ]
        }
      ],
      [
        {
          "text": "Once upon a time, when the sun announced his intention to take a wife, the frogs lifted up their voices in clamor to the sky.",
          "tag": "#+s#",
          "id": 14,
          "rank": 0,
          "lemma": [
            "upon",
            "time",
            "sun",
            "announce",
            "intention",
            "take",
            "wife",
            "frog",
            "lift",
            "voice",
            "clamor",
            "sky"
          ]
        },
        {
          "text": "Jupiter, disturbed by the noise of their croaking, inquired the cause of their complaint.",
          "tag": "#+s#",
          "id": 15,
          "lemma": [
            "jupiter",
            "disturb",
            "noise",
            "croaking",
            "inquire",
            "cause",
            "complaint"
          ]
        },
        {
          "text": "One of them said, \"The sun, now while he is single, parches up the marsh, and compels us to die miserably in our arid homes.",
          "tag": "#+s#",
          "id": 16,
          "rank": 6,
          "lemma": [
            "one",
            "say",
            "sun",
            "now",
            "single",
            "parches",
            "marsh",
            "compels",
            "u",
            "die",
            "miserably",
            "arid",
            "home"
          ]
        },
        {
          "text": "What will be our future condition if he should beget other suns?",
          "tag": "#+s#",
          "id": 17,
          "lemma": [
            "future",
            "condition",
            "beget",
            "sun"
          ]
        },
        {
          "text": "\"",
          "tag": "#-s:p#",
          "id": 18,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading Three",
          "tag": "#-s:h#",
          "id": 19,
          "lemma": [
            "heading",
            "three"
          ]
        }
      ],
      [
        {
          "text": "When Thales the astronomer was gazing up at the sky, he fell into a pit.",
          "tag": "#+s#",
          "id": 20,
          "lemma": [
            "thales",
            "astronomer",
            "gaze",
            "sky",
            "fell",
            "pit"
          ]
        },
        {
          "text": "A Thracian slave woman, who was both wise and witty, is said to have made fun of him for being eager to know what was happening over his head while failing to notice what was right there at his feet.",
          "tag": "#+s#",
          "id": 21,
          "rank": 3,
          "lemma": [
            "thracian",
            "slave",
            "woman",
            "wise",
            "witty",
            "say",
            "make",
            "fun",
            "eager",
            "know",
            "happen",
            "head",
            "fail",
            "notice",
            "right",
            "foot"
          ]
        }
      ],
      [
        {
          "text": "Heading Four",
          "tag": "#-s:h#",
          "id": 22,
          "lemma": [
            "heading",
            "four"
          ]
        }
      ],
      [
        {
          "text": "The moon once begged her mother to make her a gown.",
          "tag": "#+s#",
          "id": 23,
          "lemma": [
            "moon",
            "beg",
            "mother",
            "make",
            "gown"
          ]
        },
        {
          "text": "\"How can I?",
          "tag": "#+s#",
          "id": 24,
          "lemma": []
        },
        {
          "text": "\" replied she.",
          "tag": "#+s#",
          "id": 25,
          "lemma": [
            "reply"
          ]
        },
        {
          "text": "\"There's no fitting your figure.",
          "tag": "#+s#",
          "id": 26,
          "lemma": [
            "fitting",
            "figure"
          ]
        }
      ],
      [
        {
          "text": "Heading Four Point Five",
          "tag": "#-s:h#",
          "id": 27,
          "lemma": [
            "heading",
            "four",
            "point",
            "five"
          ]
        }
      ],
      [
        {
          "text": "At one time you're a new moon, and at another you're a Full Moon, and between whiles you're neither one nor the other.",
          "tag": "#+s#",
          "id": 28,
          "lemma": [
            "one",
            "time",
            "re",
            "new",
            "moon",
            "another",
            "re",
            "full",
            "moon",
            "while",
            "re",
            "neither",
            "one"
          ]
        },
        {
          "text": "\"",
          "tag": "#-s:p#",
          "id": 29,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading Five",
          "tag": "#-s:h#",
          "id": 30,
          "lemma": [
            "heading",
            "five"
          ]
        }
      ],
      [
        {
          "text": "Some honeybees were making honey in the hollow of an oak tree.",
          "tag": "#+s#",
          "id": 31,
          "rank": 9,
          "lemma": [
            "honeybee",
            "make",
            "honey",
            "hollow",
            "oak",
            "tree"
          ]
        },
        {
          "text": "A shepherd discovered the bees' work and attempted to carry away some of the honey.",
          "tag": "#+s#",
          "id": 32,
          "rank": 8,
          "lemma": [
            "shepherd",
            "discover",
            "bee",
            "work",
            "attempt",
            "carry",
            "away",
            "honey"
          ]
        },
        {
          "text": "The honeybees flew all around him, stinging the man with their stings.",
          "tag": "#+s#",
          "id": 33,
          "lemma": [
            "honeybee",
            "fly",
            "around",
            "sting",
            "man",
            "sting"
          ]
        },
        {
          "text": "In the end the shepherd exclaimed, \"I give up!",
          "tag": "#+s#",
          "id": 34,
          "lemma": [
            "end",
            "shepherd",
            "exclaim",
            "give"
          ]
        },
        {
          "text": "I don't need the honey if it means dealing with the bees.",
          "tag": "#+s#",
          "id": 35,
          "lemma": [
            "need",
            "honey",
            "mean",
            "deal",
            "bee"
          ]
        },
        {
          "text": "\"",
          "tag": "#-s:p#",
          "id": 36,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading Six",
          "tag": "#-s:h#",
          "id": 37,
          "lemma": [
            "heading",
            "six"
          ]
        }
      ],
      [
        {
          "text": "There was a dog who used to sneak up and bite people.",
          "tag": "#+s#",
          "id": 38,
          "rank": 7,
          "lemma": [
            "dog",
            "use",
            "sneak",
            "bite",
            "people"
          ]
        },
        {
          "text": "His master forged a bell for the dog and tied it onto him so that everyone would know when he was coming.",
          "tag": "#+s#",
          "id": 39,
          "rank": 13,
          "lemma": [
            "master",
            "forge",
            "bell",
            "dog",
            "tie",
            "onto",
            "everyone",
            "know",
            "come"
          ]
        },
        {
          "text": "The dog then paraded about the marketplace, shaking his bell back and forth.",
          "tag": "#+s#",
          "id": 40,
          "lemma": [
            "dog",
            "parade",
            "marketplace",
            "shake",
            "bell",
            "back",
            "forth"
          ]
        },
        {
          "text": "An old dog said to him, \"You wretched creature!",
          "tag": "#+s#",
          "id": 41,
          "lemma": [
            "old",
            "dog",
            "say",
            "wretched",
            "creature"
          ]
        },
        {
          "text": "Why are you so proud of yourself?",
          "tag": "#+s#",
          "id": 42,
          "lemma": [
            "proud"
          ]
        },
        {
          "text": "This is not a decoration for bravery or good behavior.",
          "tag": "#+s#",
          "id": 43,
          "lemma": [
            "decoration",
            "bravery",
            "good",
            "behavior"
          ]
        },
        {
          "text": "You are shamefully beating the drum of your own evil deeds!",
          "tag": "#+s#",
          "id": 44,
          "lemma": [
            "shamefully",
            "beat",
            "drum",
            "evil",
            "deed"
          ]
        },
        {
          "text": "\"",
          "tag": "#-s:p#",
          "id": 45,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading Seven",
          "tag": "#-s:h#",
          "id": 46,
          "lemma": [
            "heading",
            "seven"
          ]
        }
      ],
      [
        {
          "text": "The doctor asked his patient, \"How are you feeling?",
          "tag": "#+s#",
          "id": 47,
          "rank": 5,
          "lemma": [
            "doctor",
            "ask",
            "patient",
            "feel"
          ]
        },
        {
          "text": "\"\" The patient said, \"Woe is me!",
          "tag": "#+s#",
          "id": 48,
          "lemma": [
            "patient",
            "say",
            "woe"
          ]
        },
        {
          "text": "I'm shivering all over, which has me very worried.",
          "tag": "#+s#",
          "id": 49,
          "lemma": [
            "shiver",
            "very",
            "worry"
          ]
        },
        {
          "text": "\" The doctor assured the patient that this was actually a good sign.",
          "tag": "#+s#",
          "id": 50,
          "lemma": [
            "doctor",
            "assure",
            "patient",
            "actually",
            "good",
            "sign"
          ]
        },
        {
          "text": "The next time the doctor asked the patient how he was doing, the man replied, \"I feel awful!",
          "tag": "#+s#",
          "id": 51,
          "lemma": [
            "next",
            "time",
            "doctor",
            "ask",
            "patient",
            "man",
            "reply",
            "feel",
            "awful"
          ]
        },
        {
          "text": "I'm suffering from a high fever which has me confined to bed.",
          "tag": "#+s#",
          "id": 52,
          "lemma": [
            "suffer",
            "high",
            "fever",
            "confine",
            "bed"
          ]
        },
        {
          "text": "\" Again the doctor said that this was a positive symptom.",
          "tag": "#+s#",
          "id": 53,
          "lemma": [
            "doctor",
            "say",
            "positive",
            "symptom"
          ]
        },
        {
          "text": "Finally a member of the man's family asked, \"How are you doing, my dear brother?",
          "tag": "#+s#",
          "id": 54,
          "lemma": [
            "member",
            "man",
            "family",
            "ask",
            "dear",
            "brother"
          ]
        },
        {
          "text": "I hope you get well soon!",
          "tag": "#+s#",
          "id": 55,
          "lemma": [
            "hope",
            "get",
            "well",
            "soon"
          ]
        },
        {
          "text": "\" The man replied, \"I'm dying of positive symptoms!",
          "tag": "#+s#",
          "id": 56,
          "lemma": [
            "man",
            "reply",
            "die",
            "positive",
            "symptom"
          ]
        },
        {
          "text": "\"",
          "tag": "#-s:p#",
          "id": 57,
          "lemma": []
        }
      ],
      [
        {
          "text": "Heading Eight",
          "tag": "#-s:h#",
          "id": 58,
          "lemma": [
            "heading",
            "eight"
          ]
        }
      ],
      [
        {
          "text": "There was once a frog who noticed an ox standing in the meadow.",
          "tag": "#+s#",
          "id": 59,
          "rank": 12,
          "lemma": [
            "frog",
            "notice",
            "ox",
            "standing",
            "meadow"
          ]
        },
        {
          "text": "The frog was seized by a jealous desire to equal the ox in size so she puffed herself up, inflating her wrinkled skin.",
          "tag": "#+s#",
          "id": 60,
          "lemma": [
            "frog",
            "seize",
            "jealous",
            "desire",
            "equal",
            "ox",
            "size",
            "puff",
            "inflate",
            "wrinkle",
            "skin"
          ]
        },
        {
          "text": "She then asked her children if she was now bigger than the ox.",
          "tag": "#+s#",
          "id": 61,
          "lemma": [
            "ask",
            "child",
            "now",
            "big",
            "ox"
          ]
        },
        {
          "text": "They said that she was not.",
          "tag": "#+s#",
          "id": 62,
          "lemma": [
            "say"
          ]
        },
        {
          "text": "Once again she filled herself full of air, straining even harder than before, and asked her children which of the two of them was bigger.",
          "tag": "#+s#",
          "id": 63,
          "lemma": [
            "fill",
            "full",
            "air",
            "strain",
            "even",
            "hard",
            "ask",
            "child",
            "two",
            "big"
          ]
        },
        {
          "text": "\"The ox is bigger,\" said her children.",
          "tag": "#+s#",
          "id": 64,
          "lemma": [
            "ox",
            "big",
            "say",
            "child"
          ]
        },
        {
          "text": "The frog was finally so indignant that she tried even harder to puff herself up, but her body exploded and she fell down dead.",
          "tag": "#+s#",
          "id": 65,
          "lemma": [
            "frog",
            "indignant",
            "try",
            "even",
            "hard",
            "puff",
            "body",
            "explode",
            "fell",
            "dead"
          ]
        }
      ],
      [
        {
          "text": "Heading Nine",
          "tag": "#-s:h#",
          "id": 66,
          "lemma": [
            "heading",
            "nine"
          ]
        }
      ],
      [
        {
          "text": "A man who had just been badly bitten by a dog was looking for someone who could heal his wound.",
          "tag": "#+s:c#",
          "id": 67,
          "rank": 14,
          "lemma": [
            "man",
            "badly",
            "bite",
            "dog",
            "look",
            "someone",
            "heal",
            "wound"
          ]
        },
        {
          "text": "He ran into someone who told him, \"Here is what you need to do: let the blood from your wound drip onto a piece of bread and then feed the bread to the dog who bit you.",
          "tag": "#+s:c#",
          "id": 68,
          "lemma": [
            "run",
            "someone",
            "tell",
            "need",
            "let",
            "blood",
            "wound",
            "drip",
            "onto",
            "piece",
            "bread",
            "fee",
            "bread",
            "dog",
            "bit"
          ]
        },
        {
          "text": "If you do that, your wound will be cured.",
          "tag": "#+s:c#",
          "id": 69,
          "lemma": [
            "wound",
            "cure"
          ]
        },
        {
          "text": "\" The man who had been bitten by the dog replied, \"But if I do that, every single dog in the city will want to bite me!",
          "tag": "#+s:c#",
          "id": 70,
          "lemma": [
            "man",
            "bite",
            "dog",
            "reply",
            "single",
            "dog",
            "city",
            "want",
            "bite"
          ]
        },
        {
          "text": "\"",
          "tag": "#+s:c#",
          "id": 71,
          "lemma": []
        }
      ]
    ]
  },
  "nvl_data": {
    "keywords": [
      {
        "count": 8,
        "trend": [
          0,
          1,
          1,
          1,
          0,
          1,
          1,
          1,
          2,
          0
        ],
        "dispersion": [
          61,
          89,
          118,
          212,
          232,
          258,
          299,
          312
        ],
        "source": [
          "said"
        ],
        "ngram": [
          "say"
        ],
        "score": [
          0.4240237526567572
        ]
      },
      {
        "count": 8,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          4,
          0,
          0,
          0,
          4
        ],
        "dispersion": [
          189,
          197,
          203,
          211,
          329,
          347,
          353,
          356
        ],
        "source": [
          "dog"
        ],
        "ngram": [
          "dog"
        ],
        "score": [
          0.33004963871651066
        ]
      },
      {
        "count": 9,
        "trend": [
          3,
          0,
          0,
          0,
          1,
          0,
          1,
          2,
          0,
          2
        ],
        "dispersion": [
          1,
          7,
          17,
          176,
          248,
          262,
          271,
          326,
          351
        ],
        "source": [
          "man"
        ],
        "ngram": [
          "man"
        ],
        "score": [
          0.3187750570447613
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          1,
          0,
          2,
          1,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          36,
          119,
          134,
          159
        ],
        "source": [
          "making",
          "make",
          "made"
        ],
        "ngram": [
          "make"
        ],
        "score": [
          0.18329134945514244
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          0,
          0,
          1,
          0,
          0,
          1,
          1,
          0,
          1
        ],
        "dispersion": [
          136,
          249,
          272,
          354
        ],
        "source": [
          "replied"
        ],
        "ngram": [
          "reply"
        ],
        "score": [
          0.1694363586604965
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          4,
          0,
          0,
          0
        ],
        "dispersion": [
          229,
          231,
          239,
          247
        ],
        "source": [
          "patient"
        ],
        "ngram": [
          "patient"
        ],
        "score": [
          0.14606846526489373
        ]
      },
      {
        "count": 5,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          2,
          1,
          2,
          0
        ],
        "dispersion": [
          228,
          246,
          264,
          294,
          306
        ],
        "source": [
          "asked"
        ],
        "ngram": [
          "ask"
        ],
        "score": [
          0.14379225044249666
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          1,
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          71,
          90,
          104
        ],
        "source": [
          "sun",
          "suns"
        ],
        "ngram": [
          "sun"
        ],
        "score": [
          0.1415380049616502
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          1,
          0,
          0,
          0,
          3
        ],
        "dispersion": [
          192,
          328,
          352,
          359
        ],
        "source": [
          "bitten",
          "bite"
        ],
        "ngram": [
          "bite"
        ],
        "score": [
          0.12674039835369885
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          3,
          1,
          0,
          0
        ],
        "dispersion": [
          227,
          237,
          245,
          257
        ],
        "source": [
          "doctor"
        ],
        "ngram": [
          "doctor"
        ],
        "score": [
          0.11926344728068865
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          3,
          0
        ],
        "dispersion": [
          295,
          307,
          313
        ],
        "source": [
          "children"
        ],
        "ngram": [
          "child"
        ],
        "score": [
          0.11713848548823919
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          2,
          1,
          0
        ],
        "dispersion": [
          76,
          278,
          283,
          314
        ],
        "source": [
          "frogs",
          "frog"
        ],
        "ngram": [
          "frog"
        ],
        "score": [
          0.1117356712800062
        ]
      },
      {
        "count": 4,
        "trend": [
          1,
          1,
          1,
          1,
          1,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          33,
          56,
          88,
          143,
          155
        ],
        "source": [
          "one"
        ],
        "ngram": [
          "one"
        ],
        "score": [
          0.10318331094193162
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          0,
          1,
          0
        ],
        "dispersion": [
          91,
          296
        ],
        "source": [
          "now"
        ],
        "ngram": [
          "now"
        ],
        "score": [
          0.09909014938263713
        ]
      },
      {
        "count": 4,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          1,
          3,
          0
        ],
        "dispersion": [
          280,
          288,
          298,
          310
        ],
        "source": [
          "ox"
        ],
        "ngram": [
          "ox"
        ],
        "score": [
          0.09468110912076427
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          0,
          0,
          1
        ],
        "dispersion": [
          92,
          355
        ],
        "source": [
          "single"
        ],
        "ngram": [
          "single"
        ],
        "score": [
          0.09380595629056221
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          1,
          0,
          0,
          1,
          0,
          1,
          0,
          0,
          0
        ],
        "dispersion": [
          70,
          144,
          244
        ],
        "source": [
          "time"
        ],
        "ngram": [
          "time"
        ],
        "score": [
          0.08811327561327562
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          1,
          1,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          80,
          110
        ],
        "source": [
          "sky"
        ],
        "ngram": [
          "sky"
        ],
        "score": [
          0.08624577051301188
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          0,
          0,
          0,
          2,
          1,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          160,
          171,
          183
        ],
        "source": [
          "honey"
        ],
        "ngram": [
          "honey"
        ],
        "score": [
          0.0817426108374384
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          2,
          0,
          0,
          0
        ],
        "dispersion": [
          230,
          250
        ],
        "source": [
          "feel",
          "feeling"
        ],
        "ngram": [
          "feel"
        ],
        "score": [
          0.0759219348659004
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          135
        ],
        "source": [
          "gown"
        ],
        "ngram": [
          "gown"
        ],
        "score": [
          0.07447691197691197
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          1,
          0
        ],
        "dispersion": [
          111,
          322
        ],
        "source": [
          "fell"
        ],
        "ngram": [
          "fell"
        ],
        "score": [
          0.07183130566751257
        ]
      },
      {
        "count": 3,
        "trend": [
          3,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          15,
          22,
          27
        ],
        "source": [
          "treasure"
        ],
        "ngram": [
          "treasure"
        ],
        "score": [
          0.06795044036423346
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          0,
          1,
          1,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          166,
          186
        ],
        "source": [
          "bees"
        ],
        "ngram": [
          "bee"
        ],
        "score": [
          0.06785527690700104
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          47,
          59
        ],
        "source": [
          "swim",
          "swam"
        ],
        "ngram": [
          "swim"
        ],
        "score": [
          0.06407050803602526
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          1,
          0,
          0,
          0,
          1,
          0,
          0
        ],
        "dispersion": [
          126,
          279
        ],
        "source": [
          "notice",
          "noticed"
        ],
        "ngram": [
          "notice"
        ],
        "score": [
          0.06359322464618032
        ]
      },
      {
        "count": 2,
        "trend": [
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          16,
          23
        ],
        "source": [
          "inside"
        ],
        "ngram": [
          "inside"
        ],
        "score": [
          0.061718166890580684
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          2,
          0,
          0,
          0
        ],
        "dispersion": [
          218,
          241
        ],
        "source": [
          "good"
        ],
        "ngram": [
          "good"
        ],
        "score": [
          0.06012091356918943
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          2,
          0,
          0
        ],
        "dispersion": [
          260,
          275
        ],
        "source": [
          "symptoms",
          "symptom"
        ],
        "ngram": [
          "symptom"
        ],
        "score": [
          0.05878427128427129
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          2,
          0,
          0
        ],
        "dispersion": [
          259,
          274
        ],
        "source": [
          "positive"
        ],
        "ngram": [
          "positive"
        ],
        "score": [
          0.05878427128427129
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          107
        ],
        "source": [
          "thales"
        ],
        "ngram": [
          "thales"
        ],
        "score": [
          0.05792593421903765
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          109
        ],
        "source": [
          "gazing"
        ],
        "ngram": [
          "gaze"
        ],
        "score": [
          0.05792593421903765
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          108
        ],
        "source": [
          "astronomer"
        ],
        "ngram": [
          "astronomer"
        ],
        "score": [
          0.05792593421903765
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          3
        ],
        "dispersion": [
          333,
          340,
          349
        ],
        "source": [
          "wound"
        ],
        "ngram": [
          "wound"
        ],
        "score": [
          0.057795939692491416
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          1,
          0,
          0
        ],
        "dispersion": [
          263
        ],
        "source": [
          "family"
        ],
        "ngram": [
          "family"
        ],
        "score": [
          0.05173097974822112
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          0,
          1,
          0,
          0,
          0,
          0,
          1,
          0,
          0
        ],
        "dispersion": [
          97,
          273
        ],
        "source": [
          "die",
          "dying"
        ],
        "ngram": [
          "die"
        ],
        "score": [
          0.051409414340448836
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          51,
          63
        ],
        "source": [
          "athena"
        ],
        "ngram": [
          "athena"
        ],
        "score": [
          0.05059523809523808
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          50,
          62
        ],
        "source": [
          "pray",
          "praying"
        ],
        "ngram": [
          "pray"
        ],
        "score": [
          0.05059523809523808
        ]
      },
      {
        "count": 2,
        "trend": [
          1,
          1,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          35,
          48
        ],
        "source": [
          "athenian"
        ],
        "ngram": [
          "athenian"
        ],
        "score": [
          0.04776023784644473
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          1,
          0
        ],
        "dispersion": [
          323
        ],
        "source": [
          "dead"
        ],
        "ngram": [
          "dead"
        ],
        "score": [
          0.04613717221475843
        ]
      },
      {
        "count": 1,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          1
        ],
        "dispersion": [
          350
        ],
        "source": [
          "cured"
        ],
        "ngram": [
          "cure"
        ],
        "score": [
          0.045741901776384535
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          3,
          0
        ],
        "dispersion": [
          297,
          309,
          311
        ],
        "source": [
          "bigger"
        ],
        "ngram": [
          "big"
        ],
        "score": [
          0.04560124467759937
        ]
      },
      {
        "count": 2,
        "trend": [
          0,
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          45,
          58
        ],
        "source": [
          "passengers"
        ],
        "ngram": [
          "passenger"
        ],
        "score": [
          0.04377519032691446
        ]
      },
      {
        "count": 3,
        "trend": [
          3,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          2,
          19,
          21
        ],
        "source": [
          "hen"
        ],
        "ngram": [
          "hen"
        ],
        "score": [
          0.04367722794446932
        ]
      },
      {
        "count": 3,
        "trend": [
          0,
          0,
          0,
          1,
          2,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          131,
          147,
          151
        ],
        "source": [
          "moon"
        ],
        "ngram": [
          "moon"
        ],
        "score": [
          0.04102851171816689
        ]
      },
      {
        "count": 2,
        "trend": [
          2,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0,
          0
        ],
        "dispersion": [
          10,
          29
        ],
        "source": [
          "profit"
        ],
        "ngram": [
          "profit"
        ],
        "score": [
          0.04013658755038065
        ]
      }
    ],
    "trigrams": [],
    "bigrams": [
      {
        "count": 2,
        "source": [
          "man",
          "replied"
        ],
        "ngram": [
          "man",
          "replied"
        ],
        "score": [
          0.3187750570447613,
          0.1694363586604965
        ]
      },
      {
        "count": 2,
        "source": [
          "doctor",
          "asked"
        ],
        "ngram": [
          "doctor",
          "asked"
        ],
        "score": [
          0.11926344728068865,
          0.14379225044249666
        ]
      },
      {
        "count": 2,
        "source": [
          "treasure",
          "inside"
        ],
        "ngram": [
          "treasure",
          "inside"
        ],
        "score": [
          0.06795044036423346,
          0.061718166890580684
        ]
      },
      {
        "count": 1,
        "source": [
          "single",
          "dog"
        ],
        "ngram": [
          "single",
          "dog"
        ],
        "score": [
          0.09380595629056221,
          0.33004963871651066
        ]
      },
      {
        "count": 1,
        "source": [
          "dog",
          "replied"
        ],
        "ngram": [
          "dog",
          "replied"
        ],
        "score": [
          0.33004963871651066,
          0.1694363586604965
        ]
      },
      {
        "count": 1,
        "source": [
          "now",
          "bigger"
        ],
        "ngram": [
          "now",
          "bigger"
        ],
        "score": [
          0.09909014938263713,
          0.04560124467759937
        ]
      },
      {
        "count": 1,
        "source": [
          "positive",
          "symptoms"
        ],
        "ngram": [
          "positive",
          "symptoms"
        ],
        "score": [
          0.05878427128427129,
          0.05878427128427129
        ]
      },
      {
        "count": 1,
        "source": [
          "family",
          "asked"
        ],
        "ngram": [
          "family",
          "asked"
        ],
        "score": [
          0.05173097974822112,
          0.14379225044249666
        ]
      },
      {
        "count": 1,
        "source": [
          "positive",
          "symptom"
        ],
        "ngram": [
          "positive",
          "symptom"
        ],
        "score": [
          0.05878427128427129,
          0.05878427128427129
        ]
      },
      {
        "count": 1,
        "source": [
          "doctor",
          "said"
        ],
        "ngram": [
          "doctor",
          "said"
        ],
        "score": [
          0.11926344728068865,
          0.4240237526567572
        ]
      },
      {
        "count": 1,
        "source": [
          "patient",
          "said"
        ],
        "ngram": [
          "patient",
          "said"
        ],
        "score": [
          0.14606846526489373,
          0.4240237526567572
        ]
      },
      {
        "count": 1,
        "source": [
          "dog",
          "said"
        ],
        "ngram": [
          "dog",
          "said"
        ],
        "score": [
          0.33004963871651066,
          0.4240237526567572
        ]
      },
      {
        "count": 1,
        "source": [
          "making",
          "honey"
        ],
        "ngram": [
          "making",
          "honey"
        ],
        "score": [
          0.013164278250485145,
          0.0817426108374384
        ]
      },
      {
        "count": 1,
        "source": [
          "one",
          "time"
        ],
        "ngram": [
          "one",
          "time"
        ],
        "score": [
          0.10318331094193162,
          0.08811327561327562
        ]
      },
      {
        "count": 1,
        "source": [
          "passengers",
          "swam"
        ],
        "ngram": [
          "passengers",
          "swam"
        ],
        "score": [
          0.04377519032691446,
          0.06407050803602526
        ]
      }
    ],
    "quadgrams": []
  }
}
EOF;
		return $ff;

	}

}
