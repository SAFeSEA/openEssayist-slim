<?php
use Slim\Extras\Middleware\StrongAuthAdmin;


require_once '../vendor/autoload.php';
require_once '../vendor/jamie/idiorm/idiorm.php';
require_once '../vendor/jamie/paris/paris.php';

use \Slim\Slim;
use \Slim\Extras\Views\Twig as TwigView;
use \Slim\Extras\Middleware\StrongAuth;
use Slim\Middleware\LoggerMiddleWare;

require_once "../app/config.php";
require_once "../app/application.php";

// Controllers
require_once "../app/utils/LoggerMiddleware.php";
require_once "../app/utils/PDOAdmin.php";
require_once "../app/utils/StrongAuthAdmin.php";
require_once "../app/controller.php";
require_once "../app/controllers/admin.controller.php";
require_once "../app/controllers/home.controller.php";
require_once "../app/controllers/login.controller.php";
require_once "../app/controllers/user.controller.php";
require_once "../app/controllers/demo.controller.php";
require_once "../app/controllers/tutor.controller.php";

// Models
require_once "../app/models/users.model.php";
require_once "../app/models/draft.model.php";

// System's constants
define('APPLICATION', 'openEssayist');
define('VERSION', '2.1.0');
define('EXT', '.twig');

// Create main Slim application
$app = new \Slim\Slim(array(
	'openEssayist.async' => false,
	'view' => new TwigView,
	'debug' => true,
    'log.level' => \Slim\Log::DEBUG,
    'log.enabled' => true,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
        'path' => '../.logs',
        'name_format' => 'y-m-d',
    ))
));

// Asset Management
TwigView::$twigExtensions = array(
	'Twig_Extensions_Slim',
	'Twig_Extension_Debug'
);

TwigView::$twigTemplateDirs = array(
	'../templates',
);
TwigView::$twigOptions = array(
	'cache' => '../.cache',
	'debug'=> true,
);

// Set custom Twig filters
$view = $app->view();

if ($view instanceof TwigView)
{
	/* @var $twig Twig_Environment */
	$twig = $view->getEnvironment();
	
	/**
	 * Create a TWIG filter for Boolean values
	 * @param 	$var	The variable to render
	 * @return	A String containing "True" or "False"
	 * Usage: {{ item|boolean}}
	 */
	$filter = new Twig_SimpleFilter('boolean', function ($var) {
		if (is_bool($var))
			return ($var) ? "True":"False"; 
		else 
			return $var;
	});
	
	/**
	 * Create a TWIG test for checking the existence of a value in an array
	 * @param 	$val	The value to search for
	 * @param 	$arr	The array
	 * @param 	$def	The default value if the array does not exist
	 * @return	True if the value is in the array, False if not, $def if the array is not set
	 * Usage: {{ val is inOption(arr,def) }}
	 */
	$test = new Twig_SimpleTest('inOption', function ($val,$arr,$def=true) {
		if (!isset($arr)) return $def;
		if (isset($arr) && in_array($val, $arr) )
			return true; 
		return false;
	});
	
	$twig->addFilter($filter);
	$twig->addTest($test);
}


/**
 * @var $config
 * Configuration for Idiom & StrongAuth
 */
$config = array(
		'provider' => 'PDOAdmin',
		'pdo' => ORM::get_db(),
		'auth.type' => 'form',				// identification by form 
		'login.url' => '/login',			// URL for login form
		'consent.url' => '/me/consent',		// URL for consent form
		'security.urls' => array(			// URLs for secured access
				array('path' => '/account/'),
				array('path' => '/tutor/.+'),
				array('path' => '/me/'),
				array('path' => '/me/.+'),
				array('path' => '/admin/','admin'=> true),
				array('path' => '/admin/.+', 'admin' => true),
		),
);

// Define and add the StrongAuth middleware to the framework
$app->add(new StrongAuthAdmin($config, new Strong\Strong($config)));
$app->add(new LoggerMiddleWare());

// Create the openEssaysit application core
$c = new Application($app);

// Create the controllers
$loginController = new LoginController();
$appController = new HomeController();
$adminCtrl = new AdminController();
$userCtrl = new UserController();
$demoCtrl = new DemoController();
$tutorCtrl = new TutorController();


// Define the routes
$c->app->get('/', array($appController, 'index'))->name('home');
$c->app->get('/about', array($appController, 'about'))->name('about');
//$c->app->get('/config', array($appController, 'testConfig'))->name('config');
$c->app->get('/login', array($loginController, 'index'))->via('GET', 'POST')->name('login');
$c->app->get('/logout', array($loginController, 'logout'))->name('logout');

$c->app->get('/me/', array($userCtrl, 'me'))->name('me.home');
$c->app->get('/me/consent', array($loginController, 'consent'))->via('GET', 'POST')->name('consent');

$c->app->get('/me/essay/(:id(/))', array($userCtrl, 'tasks'))->conditions(array('id' => '[0-9]+'))->name('me.tasks');
//$c->app->get('/me/essay/:idt/draft/(:idd(/))', array($userCtrl, 'drafts'))->name('me.drafts');

$c->app->get('/me/essay/:idt/drafts/', array($userCtrl, 'manageDraft'))->name('me.draft.action');
$c->app->get('/me/essay/:idt/history/', array($userCtrl, 'historyDraft'))->name('me.draft.history');
$c->app->get('/me/essay/:idt/submit/', array($userCtrl, 'submitDraft'))->via('GET', 'POST')->name('me.draft.submit');

$c->app->post('/api/process/:idt', array($userCtrl, 'processDraft'))->name('me.draft.process');
$c->app->get('/api/draft/:draft/exhibit.json', array($userCtrl, 'getExhibitJSON'))->conditions(array('id' => '[0-9]+'))->name('api.draft.exhibit');
$c->app->get('/api/orchestrator.json', array($tutorCtrl, 'getJSON'))->name('api.draft.orchestrator');

$c->app->get('/me/draft/:draft/show/', array($userCtrl, 'showDraft'))->name('me.draft.show');
$c->app->get('/me/draft/:draft/keyword/', array($userCtrl, 'showKeyword'))->name('me.draft.keywords');
$c->app->get('/me/draft/:draft/stats/', array($userCtrl, 'showStats'))->name('me.draft.stats');
$c->app->get('/me/draft/:draft/sentence/', array($userCtrl, 'showSentence'))->name('me.draft.sentence');
$c->app->get('/me/draft/:draft/action/keyword', array($userCtrl, 'actionKeyword'))->name('me.draft.act.keyword');

$c->app->get('/me/draft/:draft/view/network/ke', array($userCtrl, 'viewKeGraph'))->name('me.draft.view.kegraph');
$c->app->get('/me/draft/:draft/view/network/se', array($userCtrl, 'viewSeGraph'))->name('me.draft.view.segraph');
$c->app->get('/me/draft/:draft/view/cytoscape/se', array($userCtrl, 'viewCytoScape'))->name('me.draft.view.cytoscape');


$c->app->get('/me/draft/:draft/view/dispersion', array($userCtrl, 'viewDispersion'))->name('me.draft.view.dispersion');
$c->app->get('/me/draft/:draft/view/structure', array($userCtrl, 'viewStructure'))->name('me.draft.view.structure');
$c->app->get('/me/draft/:draft/view/matrix', array($userCtrl, 'viewMatrix'))->name('me.draft.view.matrix');
$c->app->get('/me/draft/:draft/view/cloud', array($userCtrl, 'viewCloud'))->name('me.draft.view.cloud');
$c->app->get('/me/draft/:draft/view/chord', array($userCtrl, 'viewChord'))->name('me.draft.view.chord');
$c->app->get('/me/draft/:draft/view/exhibit', array($userCtrl, 'viewExhibit'))->name('me.draft.view.exhibit');

$c->app->post('/profile/data/keywords', array($userCtrl, 'saveKeywords'))->name('profile.save.keyword');
$c->app->get('/profile/data/notes', array($userCtrl, 'saveNotes'))->via('GET', 'POST')->name('profile.save.notes');

$c->app->get('/ajax/draft/:draft/graph/:graph.json', array($userCtrl, 'ajaxGraph'))->name('ajax.graph.json')
				->conditions(array('graph' => '(graphse|graphke)'));
$c->app->get('/ajax/draft/:draft/keywords.json', array($userCtrl, 'ajaxKeyword'))->name('ajax.keyword.json');


$c->app->post('/tutor/logactivity', array($tutorCtrl, 'postActivityLog'))->name('ajax.log.activity');

$c->app->get('/help/:topic', array($tutorCtrl, 'getHelpOnTopic'))->name('ajax.help.topic');


$c->app->get('/admin/', array($adminCtrl, 'index'))->name('admin.home');
$c->app->get('/admin/reset', array($adminCtrl, 'reset'))->name('admin.reset');
$c->app->get('/admin/users/', array($adminCtrl, 'allUsers'))->name('admin.users');
$c->app->get('/admin/tasks/', array($adminCtrl, 'allTasks'))->name('admin.tasks');
$c->app->get('/admin/task/:taskid', array($adminCtrl, 'editTask'))->via('GET', 'POST')->name('admin.task.edit');
$c->app->get('/admin/analyser/', array($adminCtrl, 'showEssayData'))->name('admin.json');

//$c->app->get('/demo/draft/:draft/show/', array($demoCtrl, 'showDraft'))->name('demo.draft.show');

$c->app->error(array($appController, 'error'));

// Run the application
$c->run();



	