
<?php
    
    echo '<div><h2 class="well">'.$page_title.'</h2></div>';

    $json_string_exercise_Api = $url.'/api/all_exercise_list.php';
    $jsondata_exercise_Api = file_get_contents($json_string_exercise_Api);
    $arr = (array) json_decode($jsondata_exercise_Api,true);
    
    $total = 0;
    foreach ($arr["records"] as $value) {
        $total = $total+1;
    } 
       
    if($total > 0)
    {

    $i=0;
?>

<div class="container exc">
    <div class="row">
        <form id="form" class="formday_part">
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-8">
                <div class="form-group">
                    <label>Training name:</label>
                    <input type="text" name="item" id="add" class="form-control">
                    <input type="button" id="addbtn" name="addbtn" value="(+) Add" class="btn btn-lg btn-info" />
                </div>
                <div class="form-group">
                    <ul id="list" class="list-group">
                        <?php
                            for($i = 0, $l = $total; $i < $l; $i++) {

                                $random = rand();

                                echo '
                                    <li id="'.$random.'" class="list-group-item mm_'.$i.'">
                                        <input type="button" data-id="'.$random.'" class="listelement" value="X" /> 
                                        <span class="bodypart_title">'.$arr["records"][$i]["title"].'</span>
                                        <input type="hidden" class="body_part" name="title" value="'.$arr["records"][$i]["title"].'">
                                        <input type="hidden" class="dayid" name="day_'.$random.'" value="'.$random.'">
                                    </li>';
                            }
                             
                        ?>
                    </ul>
                </div>
                <button type="button" class="btn btn-lg btn-info" id="save" onclick="submit_data();">Save</button>
            </div>
            <div class="col-md-2">&nbsp;</div>
        </form>
    </div>
</div>


<?php } ?>


<script type="text/javascript">
    $(document).ready(function(){

        var t=0;
      
        $('#addbtn').click(function(){

            if(document.getElementById("add").value.length == 0)
            {
                alert("Training Name is required!")
                return false;

            } else {

                var newitem = $('#add').val();

                var uniqid = Math.round(new Date().getTime() + (Math.random() * 100));
                
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
    
    function submit_data()
    {
        exercise_title="";

        exersice = ($("#form #list li .body_part").serializeArray());
        for(var k = 0; k < exersice.length; k++){
                exercise_title = exercise_title+','+exersice[k]["value"];
        }
        exercise_title = exercise_title.substring(1);

        if(exersice.length == 0)
        {
            alert("You must add Training Name!");
            window.location.assign("<?php echo $url;?>/exercise/");
        } else {

            console.log(exercise_title);

            var strArray = exercise_title.split(",");

                $.ajax({
                    type: "POST",
                    url: '<?php echo $url;?>/api/create_exercise.php',
                    data : '{"title":"'+exercise_title+'"}',
                    success: function(){},
                    dataType: "json",
                    contentType : "application/json",
                    success : function(result) {
                        if(result['message'] == 'OK'){
                            alert('The datas has been sent successfully.');
                            window.location.assign("<?php echo $url;?>/exercise/");
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }       
                });
        }

    }


</script>