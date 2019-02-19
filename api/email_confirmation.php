<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../objects/plans.php';

$database = new Database();
$db = $database->getConnection();

$user = new Users($db);
$plan = new Plans($db);

$data = json_decode(file_get_contents("php://input"));

$user->plan_id = $data->plan_id;
$plan->plan_id = $data->plan_id;


if(!empty($user->plan_id)){

    $stmt_plan = $plan->read_plan_detail();
    $num_plan = $stmt_plan->rowCount();

    if($num_plan>0){

        $plans_arr=array();
        $plans_arr["records"]=array();
     
        while ($row_plan = $stmt_plan->fetch(PDO::FETCH_ASSOC)){
            
            extract($row_plan);
     
            $plan_item=array(
                "id" => $id,
                "plan_id" => $plan_id,
                "body_part_title" => $body_part_title,
                "exercise_id" => $exercise_id,
                "plan_name" => $plan_name,
                "user_ids" => $user_ids
            );

            array_push($plans_arr["records"], $plan_item);
        }
   
        $total = 0;
        $firstname = "";
        $plan_name ="";
        $data = "";

        foreach ($plans_arr["records"] as $value) {
            $total = $total+1;

            $plan_name=$value["plan_name"]; //Plan name
        }
        
            $data .=
                    '<html>
                        <head>
                            <title>Virguagym workout days</title>
                        </head>
                        <body>
                        <p>
                            Hi, '.$firstname.'
                            <br>
                            This mail is your exercise program.
                            <br><br>

                            '.$plan_name.'
                        </p>
                        <table>';
                            foreach ($plans_arr["records"] as $value) {
                            $data .='
                            <tr>
                                <td>
                                    '.$value["body_part_title"].'
                                    <br>';
                                    $exercise = explode(',',$value["exercise_id"]);
                                    $x=0;
                                    foreach($exercise as $key) {   
                                        $x++;
                                        
                                        $data .=' - '.$key.'<br>';
                                    }
                        $data .='
                                </td>
                            </tr>
                            <tr><td height="10"></td></tr>
                            ';
                            }
                        $data .='
                        </table>
                        <p>This is an mail confirmation!</p>
                        </body>
                    </html>';

        $stmt = $user->user_plan_readOne();
        $num = $stmt->rowCount();

        if($num > 0){
         
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                extract($row);
         
                $user_plan=array(
                    "id" => $id,
                    "plan_id" => $plan_id,
                    "plan_name" => $plan_name,
                    "user_ids" => $user_ids,
                    "value_email" => $value_email,
                    "value_name" => $value_name
                );

            }

            print_r($user_plan);

            if (!empty($user_plan)) {

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <virtuagym@example.com>' . "\r\n";
                $subject = "Virguagym workout days";
                $message = $data;

                $user_id = explode(',',$user_plan["user_ids"]);
                $user_name = explode(',',$user_plan["value_name"]);
                $user_email = explode(',',$user_plan["value_email"]);
                $ok="";
                $i=0;
                foreach ($user_email as $value) {

                    //$firstname=$user_name[$i];
                    
                    $to= $value;

                    //$email=@mail($to, $subject, $message, $headers);
                    //if($email){
                       
                        $user->user_id = $user_id[$i];
                        $user->plan_id = $user->plan_id;
                        $user->mail_situation = 'The mail has been sent successfully!';
                        $user->record_date = date('Y-m-d H:i:s');

                        if($user->logs()){
                            $ok=1;
                        }
                        else{
                            $ok=0;
                        }

                    //}
                    

                    $i = $i+1;
                }

                if($ok==1){
                    http_response_code(201);
                    echo json_encode(array("message" => "OK"));
                } else {

                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to create log."));
                }
                
            }

        }//if($num > 0){
        
    }//if($num_plan>0){


}
 
else{
 
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to create log. Data is incomplete."));
}


?>