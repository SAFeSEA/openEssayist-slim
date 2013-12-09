<?php

/**
 * Database Details
 */

// active database
$activeGroup = 'local';

// local database
$db['local']['hostname'] = 'localhost';
$db['local']['username'] = 'root';
$db['local']['password'] = 'root';
$db['local']['database'] = 'openessayist';
$db['local']['dbProvider'] = 'mysql';

// H810 archive database
$db['archive']['hostname'] = 'localhost';
$db['archive']['username'] = 'root';
$db['archive']['password'] = 'root';
$db['archive']['database'] = 'openh810';
$db['archive']['dbProvider'] = 'mysql';



/**
 * Needed in the RHEL distribution of Apache/PHP for the use of date();
 */
date_default_timezone_set('Europe/London');

try {
	//echo "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'DBName'";
	$host = $db[$activeGroup]['hostname'];
	$root = $db[$activeGroup]['username'];
	$root_password= $db[$activeGroup]['password'];
	$database= $db[$activeGroup]['database'];

	$dbh = new PDO("mysql:host=$host", $root, $root_password);

	$dbh->exec("CREATE DATABASE IF NOT EXISTS `$database`;")
	or die(print_r($dbh->errorInfo(), true));

} catch (PDOException $e) {
	die("DB ERROR: ". $e->getMessage());
}

$providerString = sprintf('mysql:host=%s;dbname=%s', $db[$activeGroup]['hostname'], $db[$activeGroup]['database']);
ORM::configure($providerString);
ORM::configure('username', $db[$activeGroup]['username']);
ORM::configure('password', $db[$activeGroup]['password']);