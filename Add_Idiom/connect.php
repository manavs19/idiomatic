<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'idiomatic';

$con = mysql_connect($host, $user, $password);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$db = mysql_select_db($dbname, $con);

if (!$db)
{
	die ("Can\'t use test_db : " . mysql_error());
}

?>