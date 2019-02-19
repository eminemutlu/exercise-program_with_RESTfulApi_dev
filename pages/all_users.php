
    
<?php
    
    echo '<div><h2 class="well">'.$page_title.'</h2></div>';
    
    $json_string = $url.'/api/all_users_list.php';
    $jsondata = file_get_contents($json_string);
   
    $arr = (array) json_decode($jsondata,true);
    
    $total = 0;
    foreach ($arr["records"] as $value) {
        $total = $total+1;
    }

    echo'
        <table class="table table-striped users">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Email</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>';

                $k=0;
                for($i = 0, $l = $total; $i < $l; $i++) {

                        echo '
                            <tr>
                                <th scope="row">'.($i+1).'</th>
                                <td>'.($arr["records"][$i]["firstname"]).' '.($arr["records"][$i]["lastname"]).'</td>
                                <td>'.($arr["records"][$i]["email"]).'</td>
                                <td>
                                    <a href="/'.$file_name.'/edituser/'.($arr["records"][$i]["id"]).'/"><button type="button" class="btn btn-default" aria-label="Left Align">Edit</button></a>

                                </td>
                                <td>
                                    <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="deleteUser('.($arr["records"][$i]["id"]).');">Delete</button>
                                    </p>
                                </td>   
                            </tr>
                        ';

                     $k++;
                }

             echo'
                </tbody>
            </table>';



?>


<script type="text/javascript" language="javascript">


    function deleteUser(idx)
    {
        var confirmmsg = confirm("Are you sure want to delete this item?");

        if(confirmmsg){
      
            var id = idx;

            if(id != ""){
           
                $.ajax({
                  type: "POST",
                  url: '<?php echo $url;?>/api/delete_user.php?id='+id,
                  data : '{"id":"'+id+'"}',
                  success: function(){},
                  dataType: "json",
                  contentType : "application/json",
                  success : function(result) {
                       
                        console.log(result);
                        window.location.assign("<?php echo $url;?>/allusers/")

                    },
                    error: function(xhr, textStatus, errorThrown) {
                    
                        console.log(errorThrown);
                    }                  

                });
            }
        }


    }


        
</script>

