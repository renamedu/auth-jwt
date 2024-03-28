<?php

header("Access-Control-Allow-Origin: http://authentication-jwt/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "./api/config/Database.php";
include_once "./api/Objects/User.php";

// Create a new instance of the Database class and establish a connection
$database = new Database();
$db = $database->getConnection();

// Create a new instance of the User class
$user = new User($db);

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"));

// Check if email and password are provided in the request
if (isset($data->password) && isset($data->email)) {
    $password_strength = 0;

    // Check the password strength
    if (strlen($data->password) >= 8) {
        $password_strength = "perfect";
    } elseif (strlen($data->password) >= 6) {
        $password_strength = "good";
    }

    // Set email and password properties of the User object
    $user->email = $data->email;
    $user->password = $data->password;

    // Validate the email format
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(401);
        echo json_encode(array("error" => "Email not valid"));
    }
    // Check if the email is already in use
    elseif ($user->emailExists()) {
        http_response_code(401);
        echo json_encode(array("error" => "Email is already in use"));
    }
    // Check if the password is weak
    elseif ($password_strength === 0) {
        http_response_code(401);
        echo json_encode(array("error" => "weak_password"));
    } else {
        // Create a new user
        $user->create();
        // Get user data
        $user->emailExists();
        http_response_code(200);

        echo json_encode(array(
            "user_id" => $user->id,
            "password_check_status" => $password_strength));
    }
} else {
    // If the email or password is missing, send error
    http_response_code(401);
    echo json_encode(array("error" => "Not valid data"));
}
