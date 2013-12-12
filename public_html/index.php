<?php
		
require_once '../vendor/autoload.php';

use \Slim\Slim;
use \Slim\Extras\Views\Twig as TwigView;
use \Slim\Extras\Middleware\StrongAuth;
use \Slim\Middleware\LoggerMiddleWare;
use \Slim\Extras\Middleware\StrongAuthAdmin;

require_once "../app/config.php";
require_once "../app/application.php";
require_once "../app/utils/LoggerMiddleware.php";
require_once "../app/utils/PDOAdmin.php";
require_once "../app/utils/StrongAuthAdmin.php";
require_once "../app/utils/UASparser.php";

// Controllers
require_once "../app/controller.php";
require_once "../app/controllers/admin.controller.php";
require_once "../app/controllers/home.controller.php";
require_once "../app/controllers/login.controller.php";
require_once "../app/controllers/user.controller.php";
require_once "../app/controllers/demo.controller.php";
require_once "../app/controllers/tutor.controller.php";
require_once "../app/controllers/group.controller.php";

// Models
require_once "../app/models/users.model.php";
require_once "../app/models/draft.model.php";

// System's constants
define('APPLICATION', 'openEssayist');
define('VERSION', '2.6 beta');
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
    	'message_format' => '%label% | %date% | %message%'
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

// Create a hook to add the root URI to all views
$app->hook('slim.before.dispatch', function() use ($app) {
	$app->view()->appendData(array(
			'app_base' => $app->request()->getRootUri()
	));
});


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
				array('path' => '/group/'),
				array('path' => '/group/.+'),
				array('path' => '/admin/','admin'=> true),
				array('path' => '/admin/.+', 'admin' => true),
		),
);

ORM::configure('logging', true);

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
$groupCtrl = new GroupController();

// Define the routes
$c->app->get('/', array($appController, 'index'))->name('home');
$c->app->get('/about', array($appController, 'about'))->name('about');
//$c->app->get('/config', array($appController, 'testConfig'))->name('config');
$c->app->get('/login', array($loginController, 'index'))->via('GET', 'POST')->name('login');
$c->app->get('/logout', array($loginController, 'logout'))->name('logout');

$c->app->get('/me/', array($userCtrl, 'me'))->name('me.home');
$c->app->get('/me/consent', array($loginController, 'consent'))->via('GET', 'POST')->name('consent');

$c->app->get('/me/essay/(:id(/))', array($userCtrl, 'tasks'))->conditions(array('id' => '[0-9]+'))->name('me.tasks');

$c->app->get('/me/essay/:idt/drafts/', array($userCtrl, 'manageDraft'))->name('me.draft.action');
$c->app->get('/me/essay/:idt/history/', array($userCtrl, 'historyDraft'))->name('me.draft.history');
$c->app->get('/me/essay/:idt/submit/', array($userCtrl, 'submitDraft'))->via('GET', 'POST')->name('me.draft.submit');

$c->app->post('/api/process/:idt', array($userCtrl, 'processDraft'))->name('me.draft.process');
$c->app->get('/api/draft/:draft/exhibit.json', array($userCtrl, 'getExhibitJSON'))->conditions(array('id' => '[0-9]+'))->name('api.draft.exhibit');
$c->app->get('/api/orchestrator.json', array($tutorCtrl, 'getJSON'))->name('api.draft.orchestrator');

$c->app->get('/me/draft/:draft/show/', array($userCtrl, 'showDraft'))->name('me.draft.show');
$c->app->get('/me/draft/:draft/show/:cmd', array($userCtrl, 'showDraft'))->name('me.draft.showext')
->conditions(array('cmd' => '(text|keyword|sentence|all)'));
$c->app->get('/me/draft/:draft/keyword/', array($userCtrl, 'showKeyword'))->name('me.draft.keywords');
$c->app->get('/me/draft/:draft/stats/', array($userCtrl, 'showStats'))->name('me.draft.stats');
$c->app->get('/me/draft/:draft/sentence/', array($userCtrl, 'showSentence'))->name('me.draft.sentence');

$c->app->get('/me/draft/:draft/action/keyword', array($userCtrl, 'actionKeyword'))->name('me.draft.act.keyword');

$c->app->get('/me/draft/:draft/group/texts', array($demoCtrl, 'groupTexts'))->name('me.draft.group.texts');
$c->app->get('/me/draft/:draft/group/graphics', array($demoCtrl, 'groupGraphics'))->name('me.draft.group.graphics');
$c->app->get('/me/draft/:draft/group/actions', array($demoCtrl, 'groupActions'))->name('me.draft.group.actions');


$c->app->get('/me/draft/:draft/view/network/ke', array($userCtrl, 'viewKeGraph'))->name('me.draft.view.kegraph');
$c->app->get('/me/draft/:draft/view/network/se', array($userCtrl, 'viewSeGraph'))->name('me.draft.view.segraph');
$c->app->get('/me/draft/:draft/view/cytoscape/se', array($userCtrl, 'viewCytoScape'))->name('me.draft.view.cytoscape');
$c->app->get('/me/draft/:draft/view/links/se', array($userCtrl, 'viewLinksNetwork'))->name('me.draft.view.linksgraph');
$c->app->get('/me/draft/:draft/view/vivagraph/se', array($userCtrl, 'viewVivaGraph'))->name('me.draft.view.vivagraph');
$c->app->get('/me/draft/:draft/view/sigma/se', array($userCtrl, 'viewSigmaGraph'))->name('me.draft.view.sigma');
$c->app->get('/me/draft/:draft/view/voronoi/se', array($userCtrl, 'viewVoronoiGraph'))->name('me.draft.view.voronoi');
$c->app->get('/me/draft/:draft/view/hive/se', array($userCtrl, 'viewHivePlot'))->name('me.draft.view.hive');


$c->app->get('/me/draft/:draft/view/dispersion', array($userCtrl, 'viewDispersion'))->name('me.draft.view.dispersion');
$c->app->get('/me/draft/:draft/view/structure', array($userCtrl, 'viewStructure'))->name('me.draft.view.structure');
$c->app->get('/me/draft/:draft/view/target', array($userCtrl, 'viewTarget'))->name('me.draft.view.target');
$c->app->get('/me/draft/:draft/view/matrix', array($userCtrl, 'viewMatrix'))->name('me.draft.view.matrix');
$c->app->get('/me/draft/:draft/view/cloud', array($userCtrl, 'viewCloud'))->name('me.draft.view.cloud');
$c->app->get('/me/draft/:draft/view/chord', array($userCtrl, 'viewChord'))->name('me.draft.view.chord');
$c->app->get('/me/draft/:draft/view/exhibit', array($userCtrl, 'viewExhibit'))->name('me.draft.view.exhibit');
$c->app->get('/me/draft/:draft/view/generator', array($userCtrl, 'viewGenerator'))->name('me.draft.view.generator');

$c->app->post('/profile/data/keywords', array($userCtrl, 'saveKeywords'))->name('profile.save.keyword');
$c->app->post('/profile/data/keywords/reset', array($userCtrl, 'resetKeywords'))->name('profile.reset.keyword');
$c->app->get('/profile/data/notes', array($userCtrl, 'saveNotes'))->via('GET', 'POST')->name('profile.save.notes');

$c->app->get('/ajax/draft/:draft/graph/:graph.json', array($userCtrl, 'ajaxGraph'))->name('ajax.graph.json')
				->conditions(array('graph' => '(graphse|graphke)'));
$c->app->get('/ajax/draft/:draft/keywords.json', array($userCtrl, 'ajaxKeyword'))->name('ajax.keyword.json');


$c->app->post('/tutor/logactivity', array($tutorCtrl, 'postActivityLog'))->name('ajax.log.activity');

$c->app->get('/me/report', array($tutorCtrl, 'getFeedback'))->via('GET', 'POST')->name('system.report');
$c->app->get('/help/', array($tutorCtrl, 'getHelpSystem'))->name('system.help');
$c->app->get('/help/on/:topic', array($tutorCtrl, 'getHelpOnTopic'))->name('ajax.help.topic');
$c->app->get('/help/on/:topic/hints', array($tutorCtrl, 'getHelpOnTopic'))->name('ajax.help.hint');



$c->app->get('/admin/', array($adminCtrl, 'index'))->name('admin.home');
$c->app->get('/admin/reset', array($adminCtrl, 'reset'))->name('admin.reset');
$c->app->get('/admin/users', array($adminCtrl, 'allUsers'))->name('admin.users');
$c->app->get('/admin/tasks', array($adminCtrl, 'allTasks'))->name('admin.tasks');
$c->app->get('/admin/task/:taskid', array($adminCtrl, 'editTask'))->via('GET', 'POST')->name('admin.task.edit');
$c->app->get('/admin/analyser', array($adminCtrl, 'showEssayData'))->name('admin.json');
$c->app->get('/admin/history', array($adminCtrl, 'showHistory'))->name('admin.history');
$c->app->get('/admin/feedback', array($adminCtrl, 'showFeedback'))->name('admin.feedback');
$c->app->get('/admin/data/logs.js', array($adminCtrl, 'getLogs'))->name('admin.data.logs');
$c->app->get('/admin/data/logs', array($adminCtrl, 'getLogsCSV'))->name('admin.data.csv');
$c->app->get('/admin/data/logs(.:format)', array($adminCtrl, 'getLogsCSV'))->name('admin.data.log')
	->conditions(array('format' => '(xlsx|json)'));
$c->app->get('/admin/data/content', array($adminCtrl, 'getContentExcel'))->name('admin.data.content');
$c->app->get('/admin/logs/table', array($adminCtrl, 'showLogsTable'))->name('admin.logs.table');
$c->app->get('/admin/logs', array($adminCtrl, 'showLogs'))->name('admin.logs');
$c->app->get('/admin/logs/:userid/', array($adminCtrl, 'showUserLogs'))->name('admin.logs.user');

$c->app->get('/admin/group/:gid/addusers/(:nb(/:prf))', array($adminCtrl, 'addUsersToGroup'))->name('admin.task.adduser');
$c->app->post('/admin/group/addusers', array($adminCtrl, 'addUsersToGroup'))->name('admin.task.postadduser');

$c->app->get('/group/', array($groupCtrl, 'index'))->name('group.home');
$c->app->get('/group/tasks/', array($groupCtrl, 'showTasks'))->name('group.task');

$c->app->error(array($appController, 'error'));
$c->app->notFound(array($appController, 'NotFound'));

// Run the application
$c->run();



	