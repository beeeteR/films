<?php

include_once "rb.php";

$host = '127.0.0.1';
$port = '3306';
$dbname = 'rodionovregister';
$login = 'root';
$password = '';

R::setup("mysql:host=$host:$port;dbname=$dbname", "$login", $password);