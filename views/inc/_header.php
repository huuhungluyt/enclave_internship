<!DOCTYPE html>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/models/Notification.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Enclave Training Center</title>

     <!--Bootstrap style -->
    <link href="../assets/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom style -->
    <link href="../assets/css/general.css" rel="stylesheet">
    <link href="../assets/css/public.css" rel="stylesheet">

    <!-- Daterangepicker style -->
    <link href="../assets/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="../assets/datatable/css/jquery.dataTables.min.css" rel="stylesheet">



    <!-- Jquery - Bootstrap js -->
    <script src="../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  </head>

  <body>
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="../../assets/img/logos/enclave2_logo.png" alt="Dispute Bills"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

          <ul class="nav navbar-nav">
            <li><a href='index.php?ctr=home&act=index'><span class="glyphicon glyphicon-home"></span> Home</a></li>
            <?php
              if(isset($_SESSION["trainee_infor"])){
            ?>
                    <li><a href='index.php?ctr=schedule&act=getSchedule'><span class='glyphicon glyphicon-education'></span> Schedule and result</a></li>
                    <!-- <li><a href='index.php?ctr=course&act=getAvailableCourse'><span class='glyphicon glyphicon-send'></span> Register course</a></li> -->
                    <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class='glyphicon glyphicon-send'></span> Register course
                      <span class="caret"></span></a>
                      <ul class="dropdown-menu register-course-menu">
                        <li><a href="index.php?ctr=course&act=getAvailableCourse">Available Course</a></li>
                        <li><a href="index.php?ctr=course&act=getRegisteredCourse">Registered Course</a></li>       
                      </ul>
                    </li>
                    <li><a href='index.php?ctr=trainee&act=getInforById'><span class='glyphicon glyphicon-user'></span> Account</a></li>

            <?php
              }elseif(isset($_SESSION["trainer_infor"])){
            ?>
                    <li><a href='index.php?ctr=schedule&act=getSchedule'><span class='glyphicon glyphicon-briefcase'></span> Teaching schedule</a></li>
                    <li><a href='index.php?ctr=trainer&act=getInforById'><span class='glyphicon glyphicon-user'></span> Account</a></li>
            <?php
              }
            ?>
          </ul>


          <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-bell"></span>
                        <span id="numOfNotis" class="badge" style="background-color:red; color:black;">
                        </span>
                        <strong>
                        <?php
                          if(isset($_SESSION["trainee_infor"])) echo $_SESSION["trainee_infor"]["fullName"];
                                          elseif(isset($_SESSION["trainer_infor"])) echo $_SESSION["trainer_infor"]["fullName"];
                        ?>
                        </strong>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="navbar-login">
                                <div class="row">
                                    <div class="col-lg-4">
                                       
                                     <?php
                                            if (isset($_SESSION["trainee_infor"])){
                                                if ($_SESSION['trainee_infor']['profilePic']=='account.jpg'|| $_SESSION['trainee_infor']['profilePic']==null){

                                     ?>
                                                    <span class="glyphicon glyphicon-user icon-size"></span>

                                                <?php
                                                    }else{
                                                 ?>

                                                    <img style="width:100px;height:100px;" src="<?php echo  "http://" . $_SERVER['HTTP_HOST']. "/upload/Trainee/" . $_SESSION["trainee_infor"]["profilePic"] ?>">
                                                <?php
                                                     }
                                                ?>
                                     <?php  
                                            }elseif (isset($_SESSION["trainer_infor"])) {
                                                if ($_SESSION['trainer_infor']['profilePic']=='account.jpg'|| $_SESSION['trainer_infor']['profilePic']==null){

                                     ?>
                                                    <span class="glyphicon glyphicon-user icon-size"></span>
                                                <?php
                                                    }else{
                                                 ?>

                                                    <img style="width:100px;height:100px;" src="<?php echo  "http://" . $_SERVER['HTTP_HOST']. "/upload/Trainer/" . $_SESSION["trainer_infor"]["profilePic"] ?>">
                                                <?php
                                                     }
                                                 }
                                                 ?>
                                      
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="text-left"><strong>
                                        <?php
                                          if(isset($_SESSION["trainee_infor"])) echo $_SESSION["trainee_infor"]["fullName"];
                                          elseif(isset($_SESSION["trainer_infor"])) echo $_SESSION["trainer_infor"]["fullName"];
                                        ?>
                                        </strong></p>
                                        <?php
                                                if(isset($_SESSION["trainee_infor"])){
                                        ?>
                                                <p class="text-left large">Trainee</p>
                                                <p class="text-left small"><?php echo $_SESSION['trainee_infor']['email'];?></p>
                                        <?php
                                                }elseif(isset($_SESSION["trainer_infor"])){
                                        ?>

                                                <p class="text-left large">Trainer</p>
                                                <p class="text-left small"><?php echo $_SESSION['trainer_infor']['email'];?></p>
                                        <?php
                                                }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="navbar-login navbar-login-session">
                                <div class="row">
                                    <div class="col-lg-7">
                                            <a href="index.php?ctr=notice&act=newNotis" class="btn btn-primary btn-block">Notifications </a>
                                    </div>
                                    <div class="col-lg-5">
                                            <a href="index.php?ctr=auth&act=logout" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-log-out"></span> Log out</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
</header>
