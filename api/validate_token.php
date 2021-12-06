<?php
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

$headers = getallheaders();
if(!$headers) {
    accessDenied();
}

try {
    $bearer = $headers['Authorization'] ?? '';
    $jwt = str_replace('Bearer ', '', $bearer);
    $decoded = JWT::decode($jwt, $key, array('HS256'));
} catch (Exception $e) {
    accessDenied();
}

function accessDenied() {
    http_response_code(401);
    exit(json_encode(array('message' => 'Access denied.')));
}