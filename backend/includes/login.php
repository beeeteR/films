<?php

session_start();

include_once "./db.php";
include_once "./consts.php";

$email = $_POST['email'];
$password = md5($_POST['password']);

$user = R::findOne('users', 'email = ? and password = ?', [$email, $password]);
if ($user == null) {
    header('Location: /rodionov/register/index.php?errLog=true');
}
else {
    $_SESSION['id'] = $user['id'];
    header('Location: '.$location.'/user.php');
}

?>