
<?php

	echo '<div><h2 class="well">'.$page_title.'</h2></div>';

	$plan_id="";
	//USER DATA
	$json_string = $url.'/api/all_users_list.php';
    $jsondata = file_get_contents($json_string);
   	$arr = (array) json_decode($jsondata,true);
    $total = 0;
    foreach ($arr["records"] as $value) {
        $total = $total+1;
    }
    $all_users= "";
	foreach($arr['records'] as $item) {
		$all_users = $all_users.",".$item['id'];
	}
	$all_users = ltrim($all_users, ',');

   	//PLAN DATA
   	$json_string_plan = $url.'/api/all_plans_list.php';
    $jsondata_plan = file_get_contents($json_string_plan);
    $arr_p = (array) json_decode($jsondata_plan,true);
    $count_p = count($arr_p);
    if($count_p>0)
    {
        $use_plan_user= "";
		foreach($arr_p['records'] as $item) {
			$use_plan_user = $use_plan_user.",".$item['user_ids'];
		}
		$use_plan_user = ltrim($use_plan_user, ',');
    }

    $users_arr = explode(',',$use_plan_user);

    $plan_name="";

  	if(isset($_GET["id"])){
		$plan_id= $_GET["id"];
		//PLAN DETAIL
		$json_string2 = $url.'/api/detail_plan_days.php?id='.$plan_id;
		$jsondata2 = file_get_contents($json_string2);
		$arr2 = (array) json_decode($jsondata2,true);
		$count = count($arr2);

		if($count>0)
		{

			$total2 = 0;
			foreach ($arr2["records"] as $value2) {
				$total2 = $total2+1;

				$user_ids=$value2["user_ids"];//Users using the plan
				$plan_name=$value2["plan_name"]; //Plan name
			}
			
		}

		//Check to users of the plan and users of all the plans
		$plan_users = explode(',',$user_ids);
		foreach($plan_users as $key) {    
			while(($i = array_search($key, $users_arr)) !== false) {
	        	unset($users_arr[$i]);
	    	}
		}

	} 


?>
<div class="col-12">
	<div class="container plan-container">
	    <div class="row">
	        <div class="col-md-12 center">
	        	<form id="form" class="formday_part">
		        	<div class="planName">
		        		<span><label>Workout's Name: </label></span>
		        		<span><input type="text" name="plan_name" id="plan_name" value="<?php echo $plan_name;?>" class="form-control"></span>
		        	</div>
		        	<div>
		        		<label>Assigned users: </label>
		        		<span>
			        		<select data-placeholder="Let's user choose" class="chosen-select" multiple tabindex="4"  id="userlist" name="userlist">
							   
							    <?php
							    	$selectedItem="";

							    	$tags = explode(',',$user_ids);

							    	for($i = 0, $l = $total; $i < $l; $i++) {

							    		$selectedItem="";
							    		foreach($tags as $key) {    
										    if($key == $arr["records"][$i]["id"])  {
										    	$selectedItem ='selected';
										    } 
										}
										
										if (!in_array($arr["records"][$i]["id"], $users_arr))
										{

							    			echo '<option '.$selectedItem.' value="'.($arr["records"][$i]["id"]).'" >'.($arr["records"][$i]["firstname"]).'&nbsp;'.($arr["records"][$i]["lastname"]).'</option>';
							    		}
							    	}
							    ?>

							  </select>
						</span>
		        	</div><br><br>
                    <div class="form-group">
                        <label>Training day name:</label>
                        <input type="text" name="item" id="add" class="form-control">
        		        <input type="button" id="addbtn" name="addbtn" value="(+) Add" class="btn btn-lg btn-info" />
        		    </div>
	                <div class="form-group">
	                    <ul id="list" class="list-group">
	                    	<?php
	                    		if(isset($_GET["id"])){

		                    		for($ii = 0, $k = $total2; $ii < $k; $ii++) {
		                    			
		                    			$random = rand();

		                    			echo '
											<li id="'.$random.'" class="list-group-item mm_'.$ii.'">
												<input type="button" data-id="'.$random.'" class="listelement" value="X" /> 
												<span class="bodypart_title">'.$arr2["records"][$ii]["body_part_title"].'</span>
												<input type="hidden" class="body_part" name="listed_'.$ii.'" value="'.$arr2["records"][$ii]["body_part_title"].'">
												<input type="hidden" class="dayid" name="day_'.$random.'" value="'.$random.'">
												<div class="exercisedata">
													<ul class="excersizeitem">';

														$exercise = explode(',',$arr2["records"][$ii]["exercise_id"]);
														$x=0;
														foreach($exercise as $key) {   
															$x++;
														    
														    echo '<li>
																	<div>
																	<input type="text" name="excersize_'.$x.'" value="'.$key.'" class="form-control">
																	<span class="close">&times;</span>
																	</div>
																</li>';
														}

														echo'
													</ul>
												</div>
											</li>';
									}
	                    		}
	                    	?>

	                    </ul>
	                	
	                </div>
	                <input type="hidden" name="plan_id" id="plan_id" value="<?php echo $plan_id;?>">
	                <div><hr></div>
	                <button type="button" class="btn btn-lg btn-info" id="save" onclick="submit_data();">Save</button>
	    		</form>
	    		<br>
			</div>
		</div>
	</div>
</div>
		

<script type="text/javascript" language="javascript">
		
	$(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
    });

	var allowDrop = function (e) {
        e.preventDefault();
    },
    drag = function (e) {
        e.dataTransfer.setData('Text', e.target.id);
    },
    drop = function (e) {
        var data = e.dataTransfer.getData('Text');

        e.target.appendChild(document.getElementById(data));

        e.preventDefault();
    };

	$(document).ready(function(){

		var t=0;
		
		if(document.getElementById("plan_id").value.length > 0)
		{
			closebutton();
		}
		
		$('#addbtn').click(function(){

			if(document.getElementById("add").value.length == 0)
			{
				alert("The item day field can't be empty!")
				return false;

			} else {

				var newitem = $('#add').val();

				var uniqid = Math.round(new Date().getTime() + (Math.random() * 100));

				exercise_list(uniqid);
				
				$('#list').append('<li id="'+uniqid+'" class="list-group-item m_'+t+'"><input type="button" data-id="'+uniqid+'" class="listelement" value="X" /> <span class="bodypart_title">'+newitem+'</span><input type="hidden" class="body_part" name="listed_'+uniqid+'" value="'+newitem+'"><input type="hidden" class="dayid" name="day_'+uniqid+'" value="'+uniqid+'"><div class="exercisedata"></div></li>');
				$('#add').val('');
				t=t+1;
				return false;
			}
		});

	    $('#list').delegate(".listelement", "click", function() {
			var elemid = $(this).attr('data-id');
			$("#"+elemid).remove();
	    });
	});

		

	function exercise_list(idx){

		$.ajax({	
			url:'<?php echo $url;?>/pages/exercise_list.php',
			success	:function(result)
			{
				$("li#"+idx+" .exercisedata").html(result);
				
				closebutton();
			}
		})

	}

	function closebutton(){
		
		var closebtns = document.getElementsByClassName("close");
		var i;
		
		for (i = 0; i < closebtns.length; i++) {
			closebtns[i].addEventListener("click", function() {
				//this.parentElement.style.display = 'none';
				this.parentElement.innerHTML = '';
				
			});
		}

	}


	function submit_data(){

		var users_arr = $('#userlist option:selected').length
		var data_count= ($("#form #list input").serializeArray()).length;
		var plan_name= (document.getElementById("plan_name").value).length; 
		var alert_msg="";

		if(plan_name < 1) { alert_msg = "Workout's name is required!"; }
		if(users_arr < 1) { alert_msg = "Assigned users area is required!"; }
		if(data_count < 1) { alert_msg	= "Fill all empty areas."; }

		if(alert_msg == "")
		{
			var planid_= document.getElementById("plan_id").value;
			
			if(planid_ != "")
			{
				deletePlan(planid_);
			} else {
				addPlan();
			}
		} else {
			alert(alert_msg);
			return false;
		}

	}



	function addPlan()
	{	
		var planid_= document.getElementById("plan_id").value;
		if(planid_ != "")
		{
			create_plan_days(planid_);
			setInterval(create_plan_users(planid_), 60000);

		} else {
			$.ajax({
			    url: "<?php echo $url;?>/inc/get_max_id.php",
			    success: function(idx){ 
			    	if(idx != "")
			    	{
						idx++
					
						create_plan_days(idx);
						setInterval(create_plan_users(idx), 60000);

					}
			    }
			});
		}		

	}


	function create_plan_days(idx){

		var arrays = new Array();
		var array_end = new Array();
		var exercise_title="";

		var data = new Array();
		var dataend = new Array();
		
		var sort=0;

		var diziyarat = {};
		var m=0;
		$('.body_part').each(function(){
		    diziyarat[m] = this.value;
		    m=m+1;
		});

		var days_item = {};
		var s=0;
		$('.dayid').each(function(){
		    days_item[s] = this.value;
		    s=s+1;
		});
		
		var x;

		for (x in diziyarat) {

			exercise_title="";
			
			exersice = ($("#form li#"+days_item[x]+" .excersizeitem input").serializeArray());
			for(var k = 0; k < exersice.length; k++){
					exercise_title = exercise_title+','+exersice[k]["value"];
			}
			exercise_title = exercise_title.substring(1);
			
			
			data[x] = [
				{ 
					"title": diziyarat[x],
					"exersice": exercise_title
				}
			];

		}
		 
		for (x in data) {
			
			sort=sort+1;
			
			title=data[x][0]['title'];
			exersice=data[x][0]['exersice'];

			$.ajax({
				type: "POST",
				url: '<?php echo $url;?>/api/create_plan_days.php',
				 data : '{"plan_id":"'+idx+'", "sort_order":"'+sort+'", "body_part_title":"'+title+'", "exercise_id":"'+exersice+'"}',
				success: function(){},
				dataType: "json",
				contentType : "application/json",
				success : function(result) {

				},
				error: function(xhr, textStatus, errorThrown) {
					console.log(errorThrown);
				}		
			});
			
		}
	}

	function create_plan_users(idx){
		
		var plan_name= document.getElementById("plan_name").value;
		users_arr = ($("#userlist").chosen().val());

		
		if(users_arr.length > 0){
			//add users to plans
	    	$.ajax({
				type: "POST",
				url: '<?php echo $url;?>/api/create_plan_users.php',
				data : '{"plan_id":"'+idx+'","plan_name":"'+plan_name+'","user_ids":"'+users_arr+'"}',
				success: function(){},
				dataType: "json",
				contentType : "application/json",
				success : function(result1) {
				   
				   if(result1['message'] == 'OKuserplan'){

				   		send_confirmation_email(idx);//Confirmation email for users
				    	
				    	var planid_= document.getElementById("plan_id").value;
						if(planid_ == "")
						{
							alert("It has been successfully registered.\n And also, the confirmation email has been sent!");
							window.location.assign("<?php echo $url;?>/editplan/"+idx+"/");
				    	} else {
				    		alert("Update has been done successfully.\n And also, the confirmation email has been sent!");
				    	}
				    	
				    }
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log(xhr);
					console.log(textStatus);
					console.log(errorThrown);
				}			
			});
		}
	}


	function deletePlan(idx)
    {

        if(idx != ""){
       
            $.ajax({
              type: "POST",
              url: '<?php echo $url;?>/api/delete_plan.php?plan_id='+idx,
              data : '{"plan_id":"'+idx+'"}',
              success: function(){},
              dataType: "json",
              contentType : "application/json",
              success : function(result) {
                   
                    if(result['message'] == 'OK'){
                    	addPlan();
                    } 

                },
                error: function(xhr, textStatus, errorThrown) {
                
                    console.log(errorThrown);
                
                }                

            });
        }

    }


    function send_confirmation_email(idx)
    {
    
    	if(idx != ""){
       
            $.ajax({
              type: "POST",
              url: '<?php echo $url;?>/api/email_confirmation.php',
              data : '{"plan_id":"'+idx+'"}',
              success: function(){},
              dataType: "json",
              contentType : "application/json",
              success : function(result) {
                   	

                },
                error: function(xhr, textStatus, errorThrown) {
                
                    console.log(errorThrown);
                    console.log(textStatus);
                    console.log(errorThrown);
                
                }                

            });
        }

    }


</script>

