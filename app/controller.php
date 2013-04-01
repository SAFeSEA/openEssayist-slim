<?php

/**
 * Abstract class for all controllers
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
abstract class Controller extends Application {

	public function __construct()
	{
		parent::__construct();
		$this->auth = Strong\Strong::getInstance();
		//echo $this->auth ;
		if ($this->auth->loggedIn()) {
			$this->user = $this->auth->getUser();
			//var_dump($this->user);
		}
	}
	
	public function timeSince($ptime) {
		$etime = time() - $ptime;
	
		if ($etime < 1) {
			return '0 seconds';
		}
	
		$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
				30 * 24 * 60 * 60       =>  'month',
				24 * 60 * 60            =>  'day',
				60 * 60                 =>  'hour',
				60                      =>  'minute',
				1                       =>  'second'
		);
	
		foreach ($a as $secs => $str) {
			$d = $etime / $secs;
			if ($d >= 1) {
				$r = round($d);
				return $r . ' ' . $str . ($r > 1 ? 's' : '');
			}
		}
	}

	public function redirect($name, $routeName = true)
	{
		$url = $routeName ? $this->app->urlFor($name) : $name;
		$this->app->redirect($url);
	}

	public function get($value = null)
	{
		return $this->app->request()->get($value);
	}

	public function post($value = null)
	{
		$post = $this->app->request()->post($value);
        if (empty($value)) {
            $p = new stdClass;
            foreach ($post as $pt => $value) {
                $p->$pt = $value;
            }
            $post = $p;
        }
        return $post;
	}

	public function response($body)
	{
		$response = $this->app->response();
		$response['Content-Type'] = 'application/json';
		$response['X-Powered-By'] = APPLICATION . ' ' . VERSION;
		$response->body(json_encode(array($body)));
	}

	public function render($template, $data = array(), $status = null)
	{
		//if ($len = strpos(strrev($template), '.')) {
		//	$template = substr( $template, 0, -($len+1) );
		//}
		$this->app->view()->appendData(array('auth' => $this->auth));
		$this->app->render($template . EXT, $data, $status);
	}
}