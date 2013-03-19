<?php
require_once '../vendor/autoload.php';
require_once '../vendor/jamie/idiorm/idiorm.php';
require_once '../vendor/jamie/paris/paris.php';

require_once "../app/application.php";

use \Slim\Slim;
use \Slim\Extras\Views\Twig as TwigView;
use \Slim\Extras\Middleware\StrongAuth;

define('APPLICATION', 'openEssayist');
define('VERSION', '1.0.0');
define('EXT', '.twig');

// Asset Management
TwigView::$twigExtensions = array(
	'Twig_Extensions_Slim',
);

TwigView::$twigTemplateDirs = array(
	'../templates',
);
TwigView::$twigOptions = array(
	'cache' => '../.cache',
	'debug'=> true,
);


$app = new \Slim\Slim(array(
	'view' => new TwigView,
	'debug' => true
));

$c = new Application($app);

$app->get('/hello/:name', function ($name) use ($app) {
	echo "Hello, $name";
});


$app->get('/hello/', function () use ($app) {
		$app->render('site' . EXT, array());
});
	
$app->get('/', function () use ($app) {
	//echo "Hello XXXX";
	$app->render('pages/welcome' . EXT, array());
});
		

$app->run();



	