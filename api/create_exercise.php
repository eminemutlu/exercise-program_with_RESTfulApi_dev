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

$plan = new Plans($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->title)){

    $titles = explode(',',$data->title);
    $plan->status = 1;
    $stmt = $plan->read_exercise();
    $num = $stmt->rowCount();
    $state ="";
    if($num>0){ $plan->delete_excercise(); }
        
        foreach($titles as $key){ 

            $plan->title = $key;

            if($plan->create_excercise()){
                    
                $state  = 'true';
                
            }else{
                
                $state = 'false';
            }
        }

    if($state == 'true')
    {
        http_response_code(201);
        echo json_encode(array("message" => "OK"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create exercise."));
    }
}
 
else{
 
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to create exercise. Data is incomplete."));
}


?>