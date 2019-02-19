
<?php

	include '../config/variables.php';	

	//Exercise list 	
	$json_string_exercise_Api = $url.'/api/all_exercise_list.php';
	$jsondata_exercise_Api = file_get_contents($json_string_exercise_Api);
	$arr = (array) json_decode($jsondata_exercise_Api,true);
    
    $total = 0;
    foreach ($arr["records"] as $value) {
        $total = $total+1;
    }

	if($total > 0){
		$active="";
		$k=0;

		echo '<ul class="excersizeitem">';
        for($i = 0, $l = $total; $i < $l; $i++) {
        	echo '
				<li>
					<div>
					<input type="text" name="excersize_'.($arr["records"][$i]["id"]).'" value="'.($arr["records"][$i]["title"]).'" class="form-control">
					<span class="close">&times;</span>
					</div>
				</li>
        	';

        }
        echo '</ul>';
	}
							

?>


