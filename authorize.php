<?php

header("Access-Control-Allow-Origin: http://authentication-jwt/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "./api/config/Database.php";
include_once "./api/Objects/User.php";
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();

// Create a new instance of the User class
$user = new User($db);

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"));

// Check if email and password are provided in the request
if (isset($data->email) && isset($data->password)) {
    // Set the email property of the User object
    $user->email = $data->email;

    // Include necessary files for JWT token generation
    include_once "./api/config/kernel.php";
    include_once "./api/libs/php-jwt/BeforeValidException.php";
    include_once "./api/libs/php-jwt/ExpiredException.php";
    include_once "./api/libs/php-jwt/SignatureInvalidException.php";
    include_once "./api/libs/php-jwt/JWT.php";

    // If the email exists and the password is correct, generate a JWT token
    if ($user->emailExists() && password_verify($data->password, $user->password)) {

        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "data" => array(
                "id" => $user->id,
                "email" => $user->email
            )
        );

        // Encode the token and send it as a response
        $jwt = JWT::encode($token, $key, 'HS256');
        http_response_code(200);
        echo json_encode(array("access_token" => $jwt));
    }

    else {
        // If the email or password is incorrect, send error
        http_response_code(401);
        echo json_encode(array("error" => "Not valid data"));
    }
} else {
    // If the email or password is missing, send error
    http_response_code(401);
    echo json_encode(array("error" => "Not enough data"));
}
