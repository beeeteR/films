<?php

include_once './db.php';
include_once './consts.php';

$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

$user = R::dispense('users');
$user['email'] = $email;
$user['name'] = $name;
$user['password'] = hash('md5', $password);

R::store($user);

header('Location: '.$location);

?>