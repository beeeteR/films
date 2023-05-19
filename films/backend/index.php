<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');

require_once './includes/functions.php';


$method = $_SERVER['REQUEST_METHOD'];
$queries = explode('&', $_SERVER['QUERY_STRING']);

$q = isset($_GET['q']) ? $_GET['q'] : '';
$params = explode('/', $q);
$type = count($params) > 0 ? $params[0] : null;
$id = count($params) > 1 ? $params[1] : null;

$email = null;
$password = null;


switch ($method) {
    case 'GET':
        if($type === 'users') {
            if(isset($id)) {
                getUserById($id);
            }else if (count($queries) > 1) {
                foreach($queries as $query) {
                    $queryArray = explode('=', $query);

                    switch ($queryArray[0]) {
                        case 'email': $email = $queryArray[1];
                            break;
                        case 'password': $password = hash('md5', $queryArray[1]);
                            break;
                    }
                }
                if ($email && $password) {
                    getUserByEmailPassword($email, $password);
                }
            }else {
                getUsers();
            }
        }
        break;

    case 'POST':
        if($type === 'users') {
            addUser($_POST);
        }
        break;
    
    case 'PATCH':
        if($type === 'users') {
            if (isset($id)) {
                $data = file_get_contents('php://input');
                $data = json_decode($data, true);

                updateUser($id, $data);
            }
        }
        break;

    case 'DELETE':
        if($type === 'users') {
            if (isset($id)) {
                deleteUser($id);
            }
        }
        break;

    default:
    break;
}