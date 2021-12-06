<?php
include_once 'config/post_headers.php';
include_once 'config/database.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents('php://input'));
$user->email = $data->email;
$email_exists = $user->loadByEmail();

if (!$email_exists || !password_verify($data->password, $user->password)) {
    http_response_code(401);
    exit(json_encode(array('message' => 'Logon denied!')));
}

include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

$token = array(
    'iat' => $issued_at,
    'exp' => $expiration_time,
    'iss' => $issuer,
    'data' => array(
        'id' => $user->id,
        'firstname' => $user->firstname,
        'lastname' => $user->lastname,
        'email' => $user->email
    )
);

http_response_code(200);
exit(json_encode(array(
    'message' => 'Login Successful.',
    'jwt' => JWT::encode($token, $key)
)));
