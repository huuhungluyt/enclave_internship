<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Hour.php';
$_SESSION["loginUser"]="trainee";
include("inc/_header.php");?>

<section>
  <div class="container">
    <div class="row">

      <div class="col-md-6">
        <div class= "page-header">
            <h3><span class="glyphicon glyphicon-certificate"></span> New notifications</h3>
        </div>
        <table id="tableNewNotis" class="order-column hover" cellspacing="0" width="100%">
          <!-- <caption></caption> -->
          <thead>
              <tr>
                  <th>Detail</th>
                  <th>Type</th>
                  <th>From</th>
                  <th>Time</th>
              </tr>
          </thead>
          <tfoot>
              <tr>
                  <th>Detail</th>
                  <th>Type</th>
                  <th>From</th>
                  <th>Time</th>
              </tr>
          </tfoot>
          <tbody>
          <!--AJAX ALWAYS UPDATE-->
          </tbody>
        </table>
      </div>

      <div class="col-md-6">
        <div class= "page-header">
           <h3><span class="glyphicon glyphicon-comment"></span> Processed notifications</h3>
        </div>
        <table id="tableReadNotis" class="order-column hover" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th>Detail</th>
                  <th>Type</th>
                  <th>From</th>
                  <th>Seen time</th>
              </tr>
          </thead>
          <tfoot>
              <tr>
                  <th>Detail</th>
                  <th>Type</th>
                  <th>From</th>
                  <th>Seen time</th>
              </tr>
          </tfoot>
          <tbody>
          <!--AJAX ALWAYS UPDATE-->
          </tbody>
        </table>
      </div>

    </div>
  </div>
</section>

<?php include("inc/_formAddCourse.php");?>

<?php include("inc/_formAddMajor.php");?>

<?php include("inc/_formUpdateLesson.php");?>


<!--POPUP changing-schedule -->
<div class="modal fade" id="popupUpdateLesson2" role="dialog">
          <div class="modal-dialog modal-lg">
             <!-- Modal content-->
             <div class="modal-content">
                <form id="formUpdateLesson2" method="POST" class="form-horizontal">
                   <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">UPDATE LESSON</h4>
                      <input type="hidden" readonly name="notiId">
                      <input type="hidden" readonly name="lessonId">
                   </div>

                  <div class="modal-body row" style="margin: 10px;">
                    <div class="col-md-6">
                    <div class="form-group">
                          <label class="control-label col-sm-4" for="rangeOfDate">Date: </label>
                          <div class="col-sm-8">
                              <div  class="input-group date">
                                <input class="form-control datee" type="text" name= 'date' >
                                <span class="input-group-addon">
                                  <i class="glyphicon glyphicon-calendar">
                                  </i>
                                </span>
                              </div>
                          </div>
                        </div>
                        
                    </div>

                     <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-sm-4">Range of hours: </label>
                          <div class="col-sm-8">
                            <select name="rangeOfHour" class="form-control">
                            <?php
                                $hourModel= new Hour();
                                $hours= $hourModel->selectHours();
                                foreach($hours as $hour){
                                  echo "<option value='".$hour['startHour']." - ".$hour['endHour']."' >".$hour['startHour']." - ".$hour['endHour']."</option>";
                                }
                            ?>
                            </select>
                          </div>
                        </div>
                     </div>

                    <div class="col-md-12">
                        <div class="alert alert-danger">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="alert alert-success">
                        </div>
                    </div>

                    <div class="col-md-12" style="height: 100px; overflow-y:auto; overflow-x: hidden;">
                        <table class="tableConflictLessons table table-striped table-bordered" cellspacing="0" width="100%">
                          <caption><b>Conflict lessons</b></caption>
                          <thead>
                              <tr>
                                <th>Course ID</th>
                                <th>Major</th>
                                <th>Trainer</th>
                                <th>Room</th>
                                <th>Time start</th>
                                <th>Time end</th>
                                <th>State</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                    </div>

                    <div class="col-md-12" style="height: 100px; overflow-y:auto; overflow-x: hidden;">
                        <table class="tableConflictTrainees table table-striped table-bordered" cellspacing="0" width="100%">
                            <caption><b>Conflict trainees</b></caption>
                            <thead>
                                <tr>
                                    <th>Trainee ID</th>
                                    <th>Full name</th>
                                    <th>Course ID</th>
                                    <th>Major</th>
                                    <th>Room</th>
                                    <th>Time start</th>
                                    <th>Time end</th>
                                    <th>State</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                 <div class="modal-footer">
                    <input type="submit"  class="btn btn-success" value='Submit'>
                    <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                 </div>
                </form>
             </div>
          </div>
       </div>

<?php include("inc/_footer.php");?>