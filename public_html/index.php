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

// Models
require_once "../app/models/users.model.php";

// Very basic ways of simulating "first-run" for initial configuration
if (false && !file_exists('./setup/config.ini'))
{
	require_once "setup/index.php";
	die("Configuration ");
}

// System's constants
define('APPLICATION', 'openEssayist');
define('VERSION', '2.0.0');
define('EXT', '.twig');

// Create main Slim application
$app = new \Slim\Slim(array(
	'view' => new TwigView,
	'debug' => true
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
				array('path' => '/api/'),
				array('path' => '/admin/'),
				array('path' => '/admin/.+'),
		),
);

// Define the StringAuth middleware
$app->add(new StrongAuthAdmin($config, new Strong\Strong($config)));

// Create the openEssaysit application and controllers
$c = new Application($app);
//$loginController = new LoginController();
$appController = new HomeController();
$loginController = new LoginController();
$adminCtrl = new AdminController();

// Define the routes
$c->app->get('/', array($appController, 'index'))->name('home');
$c->app->get('/config', array($appController, 'testConfig'))->name('config');
$c->app->get('/login', array($loginController, 'index'))->via('GET', 'POST')->name('login');
$c->app->get('/logout', array($loginController, 'logout'))->name('logout');

$c->app->get('/admin/', array($adminCtrl, 'index'))->name('admin.home');
$c->app->get('/admin/users/', array($adminCtrl, 'index'))->name('admin.users');


// Run the application
$c->run();



	