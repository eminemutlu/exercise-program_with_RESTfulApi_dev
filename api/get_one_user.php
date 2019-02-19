<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/users.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new Users($db);
 
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$user->readOne();
 
if($user->firstname!=null){
  
    $user_arr = array(
        "id" =>  $user->id,
        "firstname" => $user->firstname,
        "lastname" => $user->lastname,
        "email" => $user->email
 
    );
 
    http_response_code(200);

    echo json_encode($user_arr);
}
 
else{
  
    http_response_code(404);
 
    echo json_encode(array("message" => "User does not exist."));
}
?>