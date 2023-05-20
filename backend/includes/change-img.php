<?php

include_once 'db.php';

$id = $_POST['id'];
$img = $_FILES['img'];

$user = R::load('users', $id);

unlink('../img/' . $user['img']);
$user['img'] = $img['name'];
move_uploaded_file($img['tmp_name'], '../img/' . $img['name']);

R::store($user);

header('Location: '.$location.'/user.php');

?>