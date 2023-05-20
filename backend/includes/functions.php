<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/rodionov/newregister/backend/includes/db.php';


function getUsers()
{

    $users = R::findAll('users');
    $usersList = [];

    foreach ($users as $user) {
        array_push(
            $usersList,
            [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'password' => $user['password'],
                'img' => $user['img']
            ]
        );
    }
    echo json_encode($usersList);
}

function getUserById($id)
{
    $user = R::load('users', $id);

    if (!$user['name']) {
        http_response_code(404);

        $res = [
            'status' => false,
            'message' => 'User not found'
        ];
        echo json_encode($res);
    } else {
        echo json_encode($user);
    }
}

function getUserByEmailPassword($email, $password)
{
    $user = R::findOne('users', 'email = ? and password = ?', [$email, $password]);

    if (!$user) {
        $res = [
            'status' => false,
            'message' => 'User not found'
        ];
        echo json_encode($res);
    } else {
        echo json_encode(['id' => $user['id'], 'email' => $user['email'], 'name' => $user['name'], 'img' => $user['img']]);
    }
}

function addUser($data) {
    $user = R::dispense('users');
    $user['email'] = $data['email'];
    $user['name'] = $data['name'];
    $user['password'] = hash('md5', $data['password']);
    R::store($user);

    http_response_code(201);

    $res = [
        'status' => true,
        'user_id' => $user->id
    ];

    echo json_encode($res);
}

function updateUser($id, $data) {
    $user = R::findOne('users', 'id = ?', [$id]);

    if (isset($data['newEmail'])) $user -> email = $data['newEmail'];
    if (isset($data['newName'])) $user -> name = $data['newName'];
    if (isset($data['newPassword'])) $user -> password = hash('md5', $data['newPassword']);
    if (isset($data['newImg'])) $user -> img = $data['newImg'];

    R::store($user);

    http_response_code(200);

    $res = [
        'status' => true,
        'message' => 'User is updated'
    ];

    echo json_encode($res);
}

function deleteUser($id) {
    $user = R::load('users', $id);
    R::trash($user);

    http_response_code(200);

    $res = [
        'status' => true,
        'message' => 'User is deleted'
    ];

    echo json_encode($res);
}