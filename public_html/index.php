<?php
use Slim\Extras\Middleware\StrongAuthAdmin;


require_once '../vendor/autoload.php';
require_once '../vendor/jamie/idiorm/idiorm.php';
require_once '../vendor/jamie/paris/paris.php';

use \Slim\Slim;
use \Slim\Extras\Views\Twig as TwigView;
use \Slim\Extras\Middleware\StrongAuth;

require_once "../app/config.php";
require_once "../app/application.php";

// Controllers
require_once "../app/utils/PDOAdmin.php";
require_once "../app/utils/StrongAuthAdmin.php";
require_once "../app/controller.php";
require_once "../app/controllers/admin.controller.php";
require_once "../app/controllers/home.controller.php";
require_once "../app/controllers/login.controller.php";
require_once "../app/controllers/user.controller.php";

// Models
require_once "../app/models/users.model.php";
require_once "../app/models/draft.model.php";

#// Very basic ways of simulating "first-run" for initial configuration
#if (false && !file_exists('./setup/config.ini'))
#{
#	require_once "setup/index.php";
#	die("Configuration ");
#}

// System's constants
define('APPLICATION', 'openEssayist');
define('VERSION', '2.0.0');
define('EXT', '.twig');

// Create main Slim application
$app = new \Slim\Slim(array(
	'view' => new TwigView,
	'debug' => true,
    'log.level' => 4,
    'log.enabled' => true,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
        'path' => '../.logs',
        'name_format' => 'y-m-d'
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
	
	
	$filter = new Twig_SimpleFilter('boolean', function ($var) {
		if (is_bool($var))
			return ($var) ? "True":"False"; 
		else 
			return $var;
	});
	
	$twig->addFilter($filter);
}


// Configuration for Idiom & StrongAuth
$config = array(
		'provider' => 'PDOAdmin',
		//'dsn' => sprintf('mysql:host=%s;dbname=%s', $db[$activeGroup]['hostname'], $db[$activeGroup]['database']),
		'pdo' => ORM::get_db(),
		//'dbuser' => $db[$activeGroup]['username'],
		//'dbpass' => $db[$activeGroup]['password'],
		'auth.type' => 'form',
		'login.url' => '/login',
		'security.urls' => array(
				array('path' => '/account/'),
				array('path' => '/me/'),
				array('path' => '/me/.+'),
				array('path' => '/admin/','admin'=> true),
				array('path' => '/admin/.+', 'admin' => true),
		),
);

// Define the StrongAuth middleware
$app->add(new StrongAuthAdmin($config, new Strong\Strong($config)));
//$app->hook('slim.before', function () use ($app)
//{
//});

// Create the openEssaysit application core
$c = new Application($app);
// Create the controllers
$loginController = new LoginController();
$appController = new HomeController();
$adminCtrl = new AdminController();
$userCtrl = new UserController();



// Define the routes
$c->app->get('/', array($appController, 'index'))->name('home');
$c->app->get('/config', array($appController, 'testConfig'))->name('config');
$c->app->get('/login', array($loginController, 'index'))->via('GET', 'POST')->name('login');
$c->app->get('/logout', array($loginController, 'logout'))->name('logout');

$c->app->get('/me/', array($userCtrl, 'me'))->name('me.home');
$c->app->get('/me/essay/(:id(/))', array($userCtrl, 'tasks'))->conditions(array('id' => '[0-9]+'))->name('me.tasks');
$c->app->get('/me/essay/:idt/draft/(:idd(/))', array($userCtrl, 'drafts'))->name('me.drafts');
$c->app->get('/me/essay/:idt/submit/', array($userCtrl, 'submitDraft'))->via('GET', 'POST')->name('me.draft.submit');

$c->app->get('/me/draft/:draft/show/', array($userCtrl, 'showDraft'))->name('me.draft.show');
$c->app->get('/me/draft/:draft/keyword/', array($userCtrl, 'showKeyword'))->name('me.draft.keywords');
$c->app->get('/me/draft/:draft/stats/', array($userCtrl, 'showStats'))->name('me.draft.stats');
$c->app->get('/me/draft/:draft/sentence/', array($userCtrl, 'showSentence'))->name('me.draft.sentence');


$c->app->get('/admin/', array($adminCtrl, 'index'))->name('admin.home');
$c->app->get('/admin/reset', array($adminCtrl, 'reset'))->name('admin.reset');
$c->app->get('/admin/users/', array($adminCtrl, 'allUsers'))->name('admin.users');
$c->app->get('/admin/tasks/', array($adminCtrl, 'allTasks'))->name('admin.tasks');
$c->app->get('/admin/task/:taskid', array($adminCtrl, 'editTask'))->via('GET', 'POST')->name('admin.task.edit');

$c->app->error(array($appController, 'error'));

// Run the application
$c->run();



	