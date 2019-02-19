
<?php
	
	echo '<div><h2 class="well">'.$page_title.'</h2></div>';
	
	$id="";
	$firstname = "";
	$lastname = "";
	$email =  "";


	if(isset($_GET["id"])){
		$id= $_GET["id"];

		$json_string = $url.'/api/get_one_user.php?id='.$id;
		$jsondata = file_get_contents($json_string);

		$arr = (array) json_decode($jsondata,true);

		$count = count($arr);
	
		
		if($count>0)
		{
			$firstname = $arr["firstname"];
			$lastname = $arr["lastname"];
			$email =  $arr["email"];
		}

	} 
?>


<div class="col-lg-12 well">
	<div class="row">
		<form id="form" name="myForm">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-md-3">&nbsp;</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<input type="hidden" name="id" value="<?php echo $id;?>">
							<label>First Name</label>
							<input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="Enter First Name Here.." class="form-control">
						</div>
						<div class="col-md-12">
							<label>Last Name</label>
							<input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="Enter Last Name Here.." class="form-control">
						</div>
						<div class="col-md-12">
							<label>Email Address</label>
							<input type="text" name="email" value="<?php echo $email; ?>" placeholder="Enter Email Address Here.." class="form-control">
						</div>
					</div>
					<div class="col-md-3">&nbsp;</div>			
				<div class="col-md-12">
					<button type="button" class="btn btn-lg btn-info" onclick="func_user();">Save</button>
				</div>					
			</div>
		</form> 
	</div>
</div>


<script>
	
	function validateEmail(email) {
		var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		if (filter.test(email)) {
			return true;
		}
		else {
			alert("invalid email!");
			return false;
		}
	}


	function func_user()
	{

		var url ="";
		var data_id="";
		var id = $("input[name=id]").val();
		var firstname = $("input[name=firstname]").val();
		var lastname = $("input[name=lastname]").val();
		var email = $("input[name=email]").val();
		
		var firstName_msg='First Name is required!';
		var lastName_msg='Last Name is required!';
		var emailMsg_msg='E-mail address is required!';
		
		missinginfo = "";
			if (firstname == "") { missinginfo += "\n"+firstName_msg;}
			if (lastname == "") { missinginfo += "\n"+lastName_msg;}
			if (email == "" ) { missinginfo += "\n"+emailMsg_msg; }

		if (missinginfo != "") {
			alert(missinginfo);
		return false;
		} else {

	        if(id != "")
	        {
				urll = '<?php echo $url;?>/api/update_user.php';
				data_id='"id":"'+id+'",';
	        } else {
	        	urll = '<?php echo $url;?>/api/create_new_user.php';
				data_id="";
	        }
	        
	        var formData = JSON.stringify($("#form").serializeArray());
	       
	        if(validateEmail(email)){

	        	$.ajax({
				  type: "POST",
				  url: urll,
				  data : '{'+data_id+'"firstname":"'+firstname+'", "lastname":"'+lastname+'", "email":"'+email+'"}',
				  success: function(){},
				  dataType: "json",
				  contentType : "application/json",
				  success : function(result) {
	                   
	                    console.log(result);
	                    if(result['message'] == 'OK'){
				    		alert('It has been successfully registered.');
				    		window.location.assign("<?php echo $url;?>/allusers/")
				  		} 

	                },
	                error: function(xhr, textStatus, errorThrown) {
				    
						console.log(errorThrown);
				  	
				  	}
								
				});
			}


        }

	}
		
</script>
