<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->firstname) &&
    !empty($data->lastname) &&
    !empty($data->email)
){
 
   
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->email = $data->email;
    
    if($user->create()){
        
        http_response_code(201);
 
        echo json_encode(array("message" => "OK"));
    }
    else{
 
        http_response_code(503);
 
        echo json_encode(array("message" => "Unable to create user."));
    }
}
 
else{
 
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}


?>