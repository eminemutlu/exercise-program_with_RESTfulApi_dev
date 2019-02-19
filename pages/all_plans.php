
<?php
    
    echo '<div><h2 class="well">'.$page_title.'</h2></div>';
    
    $json_string = $url.'/api/all_plans_list.php';
    $jsondata = file_get_contents($json_string);
    
    $arr = (array) json_decode($jsondata,true);
    $count = count($arr);

    if($count>0)
    {
        $total = 0;
        foreach ((array) $arr["records"] as $value) {
            $total = $total+1;
        }

        echo'
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Workout Name</th>
                        <th scope="col">Assigned users</th>
                        <th scope="col">Detail</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>';

                    $k=0;
                    for($i = 0, $l = $total; $i < $l; $i++) {

                            $k++;

                            echo '
                                <tr>
                                    <th>'.$k.'</th>
                                    <td>'.($arr["records"][$i]["plan_name"]).'</td>
                                    <td>'.($arr["records"][$i]["value"]).'</td>
                                    <td>
                                        <a href="/'.$file_name.'/editplan/'.($arr["records"][$i]["plan_id"]).'/"><button type="button" class="btn btn-default" aria-label="Left Align">Edit</button></a>

                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="deletePlan('.($arr["records"][$i]["plan_id"]).');">Delete</button>
                                        </p>
                                    </td>
                                </tr>
                            ';
                         
                    }

                    echo'
                    </tbody>
                </table>';
    }//if($count>0)

?>




<script type="text/javascript" language="javascript">
    
    function deletePlan(idx)
    {

        var confirmmsg = confirm("Are you sure want to delete this item?");
      
        if(confirmmsg) {

            var plan_id = idx;

            if(plan_id != ""){
           
                $.ajax({
                  type: "POST",
                  url: '<?php echo $url;?>/api/delete_plan.php?plan_id='+plan_id,
                  data : '{"plan_id":"'+plan_id+'"}',
                  success: function(){},
                  dataType: "json",
                  contentType : "application/json",
                  success : function(result) {
                       
                        if(result['message'] == 'OK'){
                            alert('The workout deleted is successfully.');
                            window.location.assign("<?php echo $url;?>/allplans/");
                        } 

                    },
                    error: function(xhr, textStatus, errorThrown) {
                    
                        console.log(errorThrown);
                    
                    }
                                        

                    });
            }
        } else {
            return false;
        }


    }


        
</script>


