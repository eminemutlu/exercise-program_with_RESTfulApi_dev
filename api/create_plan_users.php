<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/plans.php';

$database = new Database();
$db = $database->getConnection();

$planuser = new Plans($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->plan_id)&&
    !empty($data->plan_name)&&
    !empty($data->user_ids)      
){
    $planuser->plan_id = $data->plan_id;
    $planuser->plan_name = $data->plan_name;
    $planuser->user_ids = $data->user_ids;
    $planuser->status = 1;
    
    if($planuser->create_plan_user()){
        
        http_response_code(201);
 
        echo json_encode(array("message" => "OKuserplan"));
    }
    else{
 
        http_response_code(503);
 
        echo json_encode(array("message" => "Unable to create plan user."));
    }
}
 
else{
 
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to create plan user. Data is incomplete."));
}


?>