<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/films/backend/includes/rb.php';

$host = '127.0.0.1';
$port = '3306';
$dbname = 'films';
$login = 'root';
$password = '';

R::setup("mysql:host=$host:$port;dbname=$dbname", "$login", $password);