<?php

// Заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "./api/config/kernel.php";
include_once "./api/libs/php-jwt/BeforeValidException.php";
include_once "./api/libs/php-jwt/ExpiredException.php";
include_once "./api/libs/php-jwt/SignatureInvalidException.php";
include_once "./api/libs/php-jwt/JWT.php";
include_once "./api/libs/php-jwt/Key.php";

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function feed($key) {
    // Get JSON data from the request
    $data = json_decode(file_get_contents("php://input"));

    // Get JWT token from the data or set it to an empty string
    $jwt = $data->jwt ?? "";

    try {
        // Decode the JWT token using the provided key and algorithm
        JWT::decode($jwt, new Key($key, 'HS256'));

        // If decoding is successful, set the HTTP response code to 200 (OK)
        http_response_code(200);
        } catch (Exception $e) {

        // If an exception is caught (e.g., token is invalid), set the HTTP response code to 401 (Unauthorized) and error message
        http_response_code(401);
        echo json_encode(array(
            "error" => "unauthorized",
        ));
    }
}

feed($key);

