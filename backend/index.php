<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');

require_once './includes/API.php';

// http://localhost/films/backend/?user&id=1

// $location = $_SERVER['SERVER_NAME'] . '/films/backend';

$method = $_SERVER['REQUEST_METHOD'];
$queries = explode('&', $_SERVER['QUERY_STRING']);
$view = $queries[0];

$query = [];
foreach (array_slice($queries, 1) as $key) {
    $elArr = explode('=', $key);
    $query[$elArr[0]] = $elArr[1];
}

$queries = $query;
unset($query);

// $_SESSION['queries'] = [];
// foreach (array_slice($queries, 1) as $key) {
//     $elArr = explode('=', $key);
//     $_SESSION['queries'][$elArr[0]] = $elArr[1];
// }
// $_SESSION['method'] = $method;

// $newURL = "$location/views/user.php";

switch ($method) {
    case 'GET':
        if ($view == 'user') {
            getUserById($_GET['id']);
        }
    case 'POST':
        if ($view == 'user') {
            addUser($_POST);
        }
    case 'PATCH':
        break;
    case 'DELETE':
        break;
}
