<?php
use Respect\Validation\Validator as v;

/**
 * Controller for main routes to openEssayist
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class HomeController extends Controller
{
	/**
	 * @route "home"
	 */
	public function index()
	{
		$this->render('pages/welcome');
	}
	
	public function about()
	{
		$this->render('pages/about');
	}
	
	public function error(Exception $e)
	{
		$log = $this->app->getLog();
		$log->error($e->getMessage());
		
		
		$this->app->flashNow('error', $e->getMessage());
		$this->render('pages/error',array(
			'path' => $this->app->request()->headers(),
			'error' => $e
		));
	
	}	
	
	public function NotFound()
	{
		$log = $this->app->getLog();
		
		$this->app->flashNow('error', "404 Page Not Found");
		$this->render('pages/notfound',array(
		));
	
	}
	
	public function testConfig()
	{
		$configres = array();
		$this->db = ORM::get_db();

		$time_start = microtime(true);
		$code = true;
		$res = null;
		try {
			$this->db->exec("CREATE DATABASE IF NOT EXISTS `openessayist`;");
		} catch (PDOException $e) {
			$res = $e->getMessage();
			$code = false;
		}
		//var_dump(microtime(true)-$time_start);
		$configres[] = array(
				'time' => microtime(true)-$time_start,
				'msg' =>  "Try creating openessayist database if not exist",
				'code' => $code, 
				'res' => $res);
		try {
			$config = "Try creating dfd database if not exist";
			$res = null;//unset($res);
			$this->db->exec("
				CREATE TABLE IF NOT EXISTS `users` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `username` varchar(120) DEFAULT NULL,
				  `password` varchar(255) NOT NULL,
				  `name` varchar(180) DEFAULT NULL,
				  `email` varchar(220) DEFAULT NULL,
				  `ip_address` varchar(16) NOT NULL,
				  `active` int(11) DEFAULT '0',
				  `isadmin` int(11) DEFAULT '0',
				  PRIMARY KEY (`id`),
				  UNIQUE (`username`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
			");		
			$code = true;
				
		} catch (PDOException $e) {
			$res = $e->getMessage();
			$code = false;
				
		}
		$configres[] = array(
				'time' => microtime(true) - $time_start,
				'msg' =>  "Try creating the users table if not exist", 
				'code' => $code, 
				'res' => $res);
				
		try {
			$code = false;
			$u = Model::factory('Users')->create();
			$u->name = "admin";
			$u->email = "nicolas.vanlabeke@open.ac.uk";
			$u->username = "admin";
			$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("admin1");
			$u->ip_address = $this->app->request()->getIp();
			$u->isadmin = 1;
			$res = null;//unset($res);
				
			$u->save();
		}
		catch (\PDOException  $e) {
			$res = $e->getMessage();
			$code = true;
				
		}
		$configres[] = array(
				'time' => microtime(true) - $time_start,
								'msg' =>  "Use IDIORM to recreate an existing user",
				'code' => $code, 
				'res' => $res);

		try {
			$u = Model::factory('Users')->create();
			$u->name = "user".rand(0,50);
			$u->email = "nicolas.vanlabeke@open.ac.uk";
			$u->username = $u->name;
			$u->password =  Strong\Strong::getInstance()->getProvider()->hashPassword("user1");
			$u->ip_address = $this->app->request()->getIp();
			$u->isadmin = 0;
			$res = null;//unset($res);
				
			$ret = $u->save();
			$res = "ID: " . $u->id();
			$code = true;
		}
		catch (\PDOException  $e) {
			$res = $e->getMessage();
			$code = false;
		}
		$configres[] = array(
				'time' => microtime(true) - $time_start,
								'msg' =>  "Use IDIORM to create a new user (" . $u->name . ")",
				'code' => $code, 
				'res' => $res);

		/*try {
			$u = Model::factory('Users')->find_one($u->id());
			$ret = null;//unset($res);
		
			
			$res =$u->delete(); 
			$code = true;
		}
		catch (\PDOException  $e) {
			$res = $e->getMessage();
			$code = false;
		}
		$configres[] = array(
				'msg' =>  "Use IDIORM to delete the last user (" . $u->name . ")",
				'code' => $code,
				'res' => $res);
		*/
		try {
		$request = Requests::get('http://localhost:8062/',
								array(), 
								array('timeout' => 1));
						$res = null;//unset($res);
		
			$res = $request->body;
			$code = true;
		}
		catch (Exception  $e) {
			$res = $e->getMessage();
			$code = false;
		}
		$configres[] = array(
				'time' => microtime(true) - $time_start,
								'msg' =>  "Use REQUESTS to send a GET to pyEssayAnaliser",
				'code' => $code, 
				'res' => $res);
		

		try {
			
			$data = <<<EOF
The resource had some accessibility features that were achieved by keeping the document Microsoft® Office Word based,
thereby accessible for students using assistive technologies such as screen readers or voice controlled packages. I was then able
to make the navigation of the text primarily through headings and styles. Headings can indicate sections and subsections in a
long document and help any reader to understand different parts of the document and levels of importance. Students who use screen
reading software such as JAWS can use the Document Map to navigate, which in turn helps the student to understand the content and
to move around the document. If I had used direct formatting, which unfortunately I did on some occasions, then although I could
make the text have the same visual effect it would not appear in the document map and any screen reading software would not have
used it as a navigation tool. An added feature is that as a Word document a blind student could run the document through a Braille
embossing printer to give a Braille written document.			
EOF;
			$request = Requests::post('http://localhost:8062/api/analysis',
					array(), 
					array('text' => $data),
					array('timeout' => 30));
			$res = null;//unset($res);
		
			$res = $request->body;
			
			$code = $request->status_code == 200;
		}
		catch (Exception  $e) {
			$res = $e->getMessage();
			$code = false;
		}
		$configres[] = array(
				'time' => microtime(true) - $time_start,
								'msg' =>  "Use REQUESTS to send a POST to pyEssayAnaliser",
				'code' => $code,
				'res' => $res);
		
		
		
		$this->render('pages/debug', array('configres' => $configres));
	}
	
	
	public function testRequest()
	{
		$request = Requests::get('http://localhost:8062/');
		$this->app->flash("info", $request->body);
		$this->redirect('home');
		$this->render('pages/welcome');
		var_dump($request->status_code);
		// int(200)
	
		var_dump($request->headers['content-type']);
		// string(31) "application/json; charset=utf-8"
	
		var_dump($request->body);
	}
	
	
}