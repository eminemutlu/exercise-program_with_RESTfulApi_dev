<?php 
    include 'config/variables.php'; 
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <base href="http://localhost:81/vgym_dev/" />

    <title></title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/chosen-select-style.css">

    <script src="js/jquery1.11.2.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
 
  </head>

  <body>

    <div class="container index">

      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/<?php echo $file_name;?>/home/"></a>
        <button class="navbar-toggler" type="button" data-toggle=""collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if($page == 'home' || $page == ''){ echo 'active';}?>">
              <a class="nav-link" href="/<?php echo $file_name;?>/home/">Home <span class="sr-only">(current)</span></a>
            </li>
            
            <li class="nav-item dropdown <?php if($page == 'allplans' || $page == 'createplan' || $page == 'editplan'){ echo 'active';}?>">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Workouts
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/<?php echo $file_name;?>/allplans/">All workouts plan</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/<?php echo $file_name;?>/createplan/">(+) Add Users Plan</a>
              </div>
            </li>
            <li class="nav-item dropdown <?php if($page == 'allusers' || $page == 'newuser' || $page == 'edituser'){ echo 'active';}?>">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Users
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/<?php echo $file_name;?>/allusers/">All Users</a>
                <a class="dropdown-item" href="/<?php echo $file_name;?>/newuser/">(+) Add User</a>
              </div>
            </li>
            <li class="nav-item <?php if($page == 'exercise'){ echo 'active';}?>" >
              <a class="nav-link " href="/<?php echo $file_name;?>/exercise/">Trainings</a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="row "> 

       <?php
      
          switch($page)
          {
            case "allusers"   : 
                $page_title = 'Users List';
                include 'pages/all_users.php'; 
                break;
            case "newuser"    : 
                $page_title = 'Add User';
                include 'pages/user_form.php'; 
                break;
            case "edituser"   : 
                $page_title = 'Edit User Profile';
                include 'pages/user_form.php'; 
                break;
            case "createplan" :
                $page_title = 'Add Workout';
                include 'pages/plan_form.php'; 
                break;
            case "allplans"   : 
                $page_title = 'Workout List';
                include 'pages/all_plans.php'; 
                break;
            case "editplan"   : 
                $page_title = 'Add Workout';
                include 'pages/plan_form.php'; 
                break;
            case "exercise"   : 
                $page_title = 'Add / Update / Delete Training';
                include 'pages/exercise_form.php'; 
                break;
            case "home": include 'pages/home.php'; break;
            default :
                include 'pages/home.php'; break;
          }
          
       ?>
      </div>

      <footer>
        <p><br><br></p>
      </footer>
    </div> 

  </body>
</html>

