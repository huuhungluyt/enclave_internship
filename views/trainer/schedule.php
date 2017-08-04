
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Hour.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
$_SESSION["loginUser"]="trainer";
include("views/inc/_header.php");?>

<section>
<div class="container">
  <div class= "page-header">
      <h3><span class="glyphicon glyphicon-th-list"></span> Teaching schedule</h3>
  </div>
  <table class="order-column cell-border hover dataTable" id="schedule">
      <thead>
         <tr>
            <th>Course ID</th>
            <th>Start day</th>
            <th>End day</th>
            <th>Status</th>
            <th>Action</th>
         </tr>
      </thead>

      <tfoot>
         <tr>
            <th>Course ID</th>
            <th>Start day</th>
            <th>End day</th>
            <th>Status</th>
            <th>Action</th>
         </tr>
      </tfoot>
      <tbody>
         
        <?php
          foreach ($data['course'] as $row){
            echo "<tr>";
            foreach ($row as $key => $value){
              echo "<td >".htmlspecialchars($value)."</td>";

            }
            $jsCourseId= json_encode($row['id']);
          ?>
            <td class='btncol'>
            <a href='index.php?ctr=course&act=getTraineeList&id=<?php echo $row['id'];?>' class='btn btn-warning btn-xs' title="Show trainee list in this course" ><span class='glyphicon glyphicon-list-alt' ></span></a>
            <a href='index.php?ctr=course&act=getDetailCourseOfTrainer&id=<?php echo $row['id'];?>' class='btn btn-success btn-xs' title="Show detail of this course"><span class='glyphicon glyphicon-info-sign'></span></a>
            <?php
                if($row['state']=="closed"){
                  echo "<button class='btn btn-danger btn-xs' data-toggle='modal' data-target='#sendChangeTimeOfCourseRequestPopup' disabled='true' ><span class='glyphicon glyphicon-send' ></span></button></td>";
                } else{
                  echo "<button title='Change the schedule of lessons in this course'class='btn btn-danger btn-xs' data-toggle='modal' data-target='#sendChangeTimeOfCourseRequestPopup' onclick='fillFormChangeTime($jsCourseId)' ><span class='glyphicon glyphicon-send' ></span></button></td>";
                }
            ?>
            
        </tr>
        <?php 
        }
        ?>
      </tbody>
   </table>
</div>
</section>
<div class="modal fade" id="sendChangeTimeOfCourseRequestPopup" role="dialog">
          <div class="modal-dialog modal-lg">
             <!-- Modal content-->
             <div class="modal-content">
                <form id="formChangeTimeRequest" method="POST" class="form-horizontal">
                   <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">SEND MODIFIED COURSE REQUEST</h4>
                   </div>

                  <div class="modal-body row" style="margin: 10px;">
                  <div class="col-md-6">
                         <div class="form-group">
                        <label class="control-label col-sm-4">Old weekdays </label>
                        <div class="col-sm-8">
                          <select id= "oldWeekDay" name="oldWeekDay" class="form-control">
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-4">New weekdays </label>
                        <div class="col-sm-8">
                          <select id= "newWeekDay" name="newWeekDay" class="form-control">
                             <?php
                                $model= new Model();
                                $enum= $model->getEnumOf('lesson', 'dayOfWeek');
                                foreach($enum as $type){
                                    echo "<option value='$type'>$type</option>";
                                }
                                ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-8">
                          <input type="hidden" id="lessonId" readonly name="lessonId" value="1" class="form-control" >
                          <input type="hidden" id="courseId" readonly name="courseId" class="form-control" >
                          <input type="hidden" id="oldRangeOfHour" readonly name="oldRangeOfHour" class="form-control" >
                          <input type="hidden" name="oldRoomId" id="oldRoomId" readonly class="form-control">
                          <input type="hidden" id="rangeOfDate" readonly name="rangeOfDate" class="form-control" value="<?php echo date("Y-m-d");?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                        <label class="control-label col-sm-4">Room </label>
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
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-4">Hours </label>
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
                     <table id="tableTrainee" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
                    <input type="submit"  class="btn btn-success " id='btnChange' value='Submit'>
                    <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                 </div>
                </form>
             </div>
          </div>
       </div>


<?php include("views/inc/_footer.php");?>
