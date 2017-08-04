
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Hour.php';
$_SESSION["loginUser"]="trainer";
include("views/inc/_header.php");?>

<section>
<div class="container">
  <div class= "page-header">
      <h3><span class="glyphicon glyphicon-th-list"></span> Detail of course</h3>
  </div>
  <table class="order-column cell-border hover dataTable" id="schedule">
        <thead>
          <tr>
            <th>Lesson ID</th>
            <th>Course ID</th>
            <th>Room</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Weekday</th>
            <th>Busy</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <th>Lesson ID</th>
            <th>Course ID</th>
            <th>Room</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Weekday</th>
            <th>Busy</th>
          </tr>
        </tfoot>
                  
        <tbody>
          
        <?php
          if($data['course'] != null){
            foreach ($data['course'] as $tem) {
              echo '<tr>';
              foreach ($tem as $key => $value) {
                  echo '<td>'.htmlspecialchars($value).'</td>';
              }
              $detail=json_encode($tem);
              if($data['state']['state']=="closed" || $tem['endTime']<date("Y-m-d h:i:sa")){
                echo "<td class='btncol'><button class='btn btn-danger btn-xs' id='btnBusy' data-toggle='modal' data-target='#sendChangeScheduleRequestPopup' disabled='true'><span class='glyphicon glyphicon-dashboard' ></span></button></td>";
              } else{
                echo "<td class='btncol'><button class='btn btn-danger btn-xs' id='btnBusy' data-toggle='modal' data-target='#sendChangeScheduleRequestPopup' onclick='fillFormRequest($detail)' ><span class='glyphicon glyphicon-dashboard' ></span></button></td>";
              }
              echo '</tr>';
              
            }
            
          }
        ?>
        </tbody>
      </table>
</div>
</section>
<!--POPUP changing-schedule -->
<div class="modal fade" id="sendChangeScheduleRequestPopup" role="dialog">
          <div class="modal-dialog modal-lg">
             <!-- Modal content-->
             <div class="modal-content">
                <form id="formChangeScheduleRequest" method="POST" class="form-horizontal">
                   <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">SEND CHANGING LESSON'S TIME REQUEST</h4>
                   </div>

                  <div class="modal-body row" style="margin: 10px;">
                    <div class="col-md-4">
                        
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="rangeOfDate">Date: </label>
                          <div class="col-sm-8">
                              <div  class="input-group date">
                                <input class="form-control datee" type="text" id="rangeOfDate" name= 'rangeOfDate' >
                                <span class="input-group-addon">
                                  <i class="glyphicon glyphicon-calendar">
                                  </i>
                                </span>
                              </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-8">
                            <input type="hidden" id="lessonId" readonly name="lessonId" class="form-control" >
                            <input type="hidden" id="courseId" readonly name="courseId" class="form-control" >
                            <input type="hidden" id="roomId" readonly name="roomId" value="NULL" class="form-control" >
                            <input type="hidden" id="oldRangeOfHour" readonly name="oldRangeOfHour" class="form-control">
                            <input type="hidden" id="oldRangeOfDate" readonly name="oldRangeOfDate" class="form-control">
                          </div>
                        </div>

                       <!--  <div class="form-group">
                          <label class="control-label col-sm-4" for="roomId">Room</label>
                          <div class="col-sm-8">
                            <select id= "roomId" name="roomId" class="form-control">
                            <?php
                                $roomModel= new Room();
                                $temp= $roomModel->getRoom();
                                foreach($temp as $room){
                                  echo "<option value='".$room['id']."' >".$room['id']." (capacity: ".$room['capacity'].", quality: ".$room['quality'].")</option>";
                                }
                            ?>
                            </select>
                          </div>
                        </div> -->
                      </div> 

                     <div class="col-md-8">
                        

                        <div class="form-group">
                          <label class="control-label col-sm-4">Range of hours: </label>
                          <div class="col-sm-8">
                            <select id= "rangeOfHour" name="rangeOfHour" class="form-control">
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

                    <div class="col-md-12 note" style="height: 250px; overflow-y:auto; overflow-x: hidden;">
                     <div class="col-md-12 note">
                     <table id="tableTraineeConflict" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <caption><b>Conflict time of trainee</b></caption>
                          <thead>
                              <tr>
                                <th>Trainee ID</th>
                                <th>Time start</th>
                                <th>Time end</th>
                                <th>State</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                     </div>

                     <div class="col-md-12 note" >
                        <table id="tableCourseConflict" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <caption><b>Conflict courses</b></caption>
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
  

<?php include("views/inc/_footer.php");?>
