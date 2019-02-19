

<?php
    
    $json_string = $url.'/api/all_plans_list.php';
    $jsondata = file_get_contents($json_string);
    
    $arr = (array) json_decode($jsondata,true);
    $count = count($arr);

    $i=0;
	$total = 0;
	foreach ($arr["records"] as $value) {
		$total = $total+1;
	}

?>

<div class="container">
	<div class="row">
		<div class="col-md-4 marginauto"> 
		<?php
			for($i = 0, $l = $total; $i < $l; $i++) {

				echo '<div class="frame"><a href="/'.$file_name.'/editplan/'.($arr["records"][$i]["plan_id"]).'/"><button type="button" class="btn-lg btn-outline-secondary">'.($arr["records"][$i]["plan_name"]).'</button></a></div>';
			}
		?>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">

		<div class="col-md-3">&nbsp;</div>
		<div class="col-md-4">
			<a class="btn btn-primary btn-lg" href="/<?php echo $file_name;?>/newuser/" role="button"><h2>(+) Add User</h2></a>
		</div>
		<div class="col-md-4">
			<a class="btn btn-primary btn-lg" href="/<?php echo $file_name;?>/createplan/" role="button"><h2>(+) Add Workout</h2></a>
		</div>
		<div class="col-md-3">&nbsp;</div>
	</div>
</div>


