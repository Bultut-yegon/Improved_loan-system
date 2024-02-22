<?php

require __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbHost = $_ENV['DB_HOST'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];
$dbDatabase = $_ENV['DB_DATABASE'];

mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);

//Check for connection errors

if ($mysqli->connect_error)
{
	die("Error: " . $mysqli->connect_error);
}

return $mysqli;