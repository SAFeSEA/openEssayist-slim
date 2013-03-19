<?php

/**
 * Database Details
 */
$activeGroup = 'local';

$db['local']['hostname'] = 'localhost';
$db['local']['username'] = 'root';
$db['local']['password'] = '';
$db['local']['database'] = 'openessayist';
$db['local']['dbProvider'] = 'mysql';


/*try {
	//echo "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'DBName'";
	$host = $db['local']['hostname'];
	$root = $db['local']['username'];
	$root_password= $db['local']['password'];
	$database=$db['local']['database'];

	$dbh = new PDO("mysql:host=$host", $root, $root_password);

	$dbh->exec("CREATE DATABASE IF NOT EXISTS `$database`;")
	or die(print_r($dbh->errorInfo(), true));

} catch (PDOException $e) {
	die("DB ERROR: ". $e->getMessage());
}*/

$providerString = sprintf('mysql:host=%s;dbname=%s', $db[$activeGroup]['hostname'], $db[$activeGroup]['database']);
ORM::configure($providerString);
ORM::configure('username', $db[$activeGroup]['username']);
ORM::configure('password', $db[$activeGroup]['password']);