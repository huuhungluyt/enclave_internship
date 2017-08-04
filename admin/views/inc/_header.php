<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Manager area</title>


     <!-- Bootstrap style -->
      <link href="../assets/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">  
       <!-- <link href="../assets/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet">    -->

    <!-- Daterangepicker style -->
    <link href="../assets/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Data tables -->
     <link href="../assets/datatable/css/jquery.dataTables.min.css" rel= "stylesheet"> 

    <link href="../assets/css/font-awesome.min.css" rel= "stylesheet">

    <!-- Custom style -->
    <link href="../assets/css/general.css" rel= "stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">



    <!-- Jquery - Bootstrap js-->
    <script src="../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  </head>

  <body>
  <!--INCLUDE-->

<header>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php?ctr=home&act=index">Training course - Manager Area</a>
        </div>


        <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
                    <li><a href='index.php?ctr=course&act=postSchedule'>Courses</a></li>
                    <li><a href='index.php?ctr=trainee&act=getList'>Trainees</a></li>
                    <li><a href='index.php?ctr=trainer&act=getList'>Trainers</a></li>
                    <li><a href='index.php?ctr=room&act=getRoom'>Rooms</a></li>
                    <li><a href='index.php?ctr=major&act=getMajor'>Majors</a></li>
                    <li><a href='index.php?ctr=notice&act=newNotis'><span id="numOfNotis" class="badge" style="background-color:red; color:black;">
                        </span> Notifications</a></li>

          </ul>




          <div class="nav navbar-nav navbar-right" style="padding-top: 10px;">

                    <a href="index.php?ctr=auth&act=logout" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-log-out"></span> Log out</a>

            </div>
        </div><!--/.navbar-collapse -->

      </div>
    </nav>
    </header>
