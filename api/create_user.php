<?php
include_once 'config/post_headers.php';
include_once 'config/database.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents('php://input'));
$user = new User($db, $data->firstname, $data->lastname, $data->password, $data->email);

if ($user->loadByEmail()) {
    http_response_code(409);
    exit(json_encode(array('message' => 'User already exists.')));
} else if ($user->isValid() && $user->create()) {
    http_response_code(200);
    exit(json_encode(array('message' => 'User was created.')));
} else {
    http_response_code(400);
    exit(json_encode(array('message' => 'Unable to create user.')));
}