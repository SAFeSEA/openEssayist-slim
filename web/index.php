<?php
//require '../vendor/autoload.php';


$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
	echo "Hello, $name";
});

$app->get('/', array("test","fdgfdfd"));
			
$app->run();



function fdgfdfd() {
	echo "Hello";
};

// An example callback method
class test {
	static function fdgfdfd() {
		echo 'Hello World!';
	}
}