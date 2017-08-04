
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Hour.php';
include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_header.php");?>


<section>
  <div class="container" >
      <div class= "page-header row">
        <div class= "col-sm-11">
          <h3><span class="glyphicon glyphicon-text-background"></span> List of courses</h3>
        </div>
        <div>
          <tr>

            <button id="btnSetDefaultCourse" data-toggle='modal' data-target='#loadCoursePopup' class="btn btn-info btn-sm">Set default course</button>
            <button   id="btnRemoveDefault" data-toggle='modal' data-target='#loadDefaultCoursePopup' class="btn btn-info btn-sm">Remove default course</button>
            <button id="btnPopupAddCourse" data-toggle='modal' data-target='#popupAddCourse' class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span></button>
          </tr>
        </div>
      </div>
      <table id="coursesTable" class="order-column cell-border hover dataTable" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th>ID</th>
              <th>Major</th>
              <th>Trainer</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>ID</th>
              <th>Major</th>
              <th>Trainer</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
         <?php
             foreach ($data['course'] as $tem) :
          ?>
          <tr>
            <?php
            foreach ($tem as $key => $value){
              echo "<td>".htmlspecialchars($value)."</td>";
            }
            ?>
              <td class="btncol">
                <a href="index.php?ctr=course&act=postTraineeListByCourseId&id=<?php echo $tem['id'];?>" title="List of trainees" class="btn btn-warning btn-xs">
                  <span class="glyphicon glyphicon-list-alt" >
                  </span>
                </a>

                <a href="index.php?ctr=course&act=getUpdateCourseView&courseId=<?php echo $tem['id'];?>" title="Update course" class="btn btn-success btn-xs">
                  <span class="glyphicon glyphicon-pencil" >
                  </span>
                </a>


                <?php
                  $courseModel=new Course();
                  $num=$courseModel->getNumberOfFailer($tem['id']);
                  $detail= json_encode($courseModel->getCourseById($tem['id']));

                  if($num>=3){
                     echo "<button type='button' title= 'Reopen course' class='btn btn-info btn-xs' data-toggle='modal' data-target='#reopenPopup' onclick='loadDetailCourse($detail)' id='btnReopen'><span class='glyphicon glyphicon-repeat'></span></button>";
                  } else{
                    echo "<button type='button' class='btn btn-info btn-xs' data-toggle='modal' data-target='#reopenPopup' id='btnReopen' disabled='true' ><span class='glyphicon glyphicon-repeat'></span></button>";
                  }

                  if($tem['state']!='closed')
                    echo '<button disabled="true" class="btn btn-danger btn-xs" data-toggle="confirmation"><span class="glyphicon glyphicon-remove"></button>';
                  else
                    echo '<button title="Delete course" class="btn btn-danger btn-xs btn-delete-course" data-toggle="confirmation"><span class="glyphicon glyphicon-remove"></span></button>';
                ?>
              </td>

              </tr>
        <?php endforeach;?>
          </tbody>
      </table>
  </div>
</section>

<?php include("inc/_formAddCourse.php");?>

       <!--POPUP-->
            <!--POPUP UPDATE COURSE FORM-->
            <div class="modal fade" id="updatePopup" role="dialog">
               <div class="modal-dialog modal-lg">
                  <!-- Modal content-->
                    <div class="modal-content">
                      <form id="formUpdateCourse" method="POST" class="form-horizontal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">UPDATE COURSE</h4>
                        </div>

                        <div class="modal-body row" style="margin: 10px;">

                          <div class="col-md-4">

                              <div class="form-group">
                                <label class="control-label col-sm-4">Major</label>
                                <div class="col-sm-8">
                                  <input type="hidden" id="updateCourseId" name="updateCourseId">
                                  <select id="updateMajorId" name="updateMajorId" class="form-control">
                                  <?php
                                      $majorModel= new Major();
                                      $temp= $majorModel->getAllMajor();
                                      foreach($temp as $row){
                                        echo "<option value='".$row['id']."' >".$row['name']."</option>";
                                      }
                                  ?>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group">
                                  <label class="control-label col-sm-4">Trainer</label>
                                  <div class="col-sm-8">
                                  <select id="updateTrainerId" name="updateTrainerId" class="form-control">
                                  </select>
                                </div>
                              </div>

                            </div>

                          <div class="col-md-8">
                              <div class="form-group">
                                  <label class="control-label col-sm-4">Room</label>
                                <div class="col-sm-8">
                                  <select id= "updateRoomId" name="updateRoomId" class="form-control">
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
                                <label class="control-label col-sm-4">Course time: </label>
                                <div class="col-sm-8">
                                    <div  class="input-group date">
                                      <input class="form-control dateTimeRange" type="text" id="updateCourseTime" name= "updateCourseTime" >
                                      <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar">
                                        </i>
                                      </span>
                                    </div>
                                </div>
                              </div>
                          </div>

                          <div class="col-md-12 note" style="height: 250px; overflow-y:auto; overflow-x: hidden;">
                              <table id="tableConflictCourses2" class="table table-striped table-bordered" cellspacing="0" width="100%">
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

                               <table id="tableConflictTrainees" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <caption><b>List of trainee who have conflict schedule</b></caption>
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
                            <!-- <label class="checkbox-inline pull-left"><input name="isRequiredCourse" id="isRequiredCourse" type="checkbox" value=""> Required course</label> -->
                            <input type="submit" id="btnAddCourse" class="btn btn-success" value='Submit'>
                            <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                        </div>
                      </form>
                    </div>
                  </div>
               </div>
</div>

<!--POPUP-->
    <!--POPUP SET DEFAULT COURSE FORM-->
     <div class="modal fade" id="loadCoursePopup" role="dialog">
        <div class="modal-dialog" style="width:1000px;">
           <div class="modal-content">
              <form id="formCourseList" method="POST" class="form-horizontal">
                 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> <span class="glyphicon glyphicon-th-list"></span> LIST COURSES TO SET DEFAULT</h4>
                 </div>
                 <div class="modal-body" style="margin: 10px;" id="checkboxCourse">
                 <table class="order-column cell-border hover dataTable" cellspacing="0" width="100%" id="tableCourseList">
                     <thead>
                       <tr>
                          <th>CourseID</th>
                          <th>Major</th>
                          <th>Trainer</th>
                          <th>Default</th>
                       </tr>
                     </thead>

                     <tbody>
                       <?php
                        // var_dump($data['approved']);
                        foreach ($data['approved'] as $row) {
                           echo "<tr>";
                          foreach ($row as $key => $value) {
                            echo "<td>".htmlspecialchars($value)."</td>";
                          }
                          echo "<td id=".$row['id']."><center><input type='checkbox' name='course' ></center></td>";
                          echo "</tr>";
                        }
                        ?>

                     </tbody>
                   </table>
                 <div class="modal-footer">
                   <button type="button" id="btnSubmitDefaultCourse" class="btn btn-success">Set</button>
                 </div>
                </div>
              </form>
           </div>
        </div>
     </div>

<!--POPUP-->
<!--POPUP REMOVE DEFAULT COURSE FORM-->
   <div class="modal fade" id="loadDefaultCoursePopup" role="dialog">
      <div class="modal-dialog" style="width:1000px;">
         <div class="modal-content">
            <form id="formDefaultCourse" method="POST" class="form-horizontal">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4> <span class="glyphicon glyphicon-th-list"></span> LIST DEFAULT COURSE</h4>
               </div>
               <div class="modal-body" style="margin: 10px;" >
                 <table class="order-column cell-border hover dataTable" cellspacing="0" width="100%" id="tableDefaultCourse">
                     <thead>
                       <tr>
                         <th>Course ID</th>
                         <th>Major</th>
                         <th>Trainer</th>
                         <th>Remove</th>
                       </tr>
                     </thead>

                     <tbody>
                     <?php
                      foreach ($data['default'] as $row) {
                           echo "<tr>";
                          foreach ($row as $key => $value) {
                            echo "<td>".htmlspecialchars($value)."</td>";
                          }
                          echo "<td id=".$row['id']."><center><input type='checkbox' name='course' ></center></td>";
                          echo "</tr>";
                        }
                        ?>
                     </tbody>
                   </table>
                 <div class="modal-footer">
                   <button type="button" id="btnRemoveDefaultCourse" class="btn btn-success">Remove</button>
                 </div>
                </div>
            </form>
         </div>
      </div>
   </div>

<div class="modal fade" id="delete-course-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?ctr=course&act=postDelete" method="post" accept-charset="utf-8">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">DELETE COURSE</h4>
        </div>
        <div class="modal-body">

            <input type="hidden" name="id" value="">
            Are you sure to delete the course which has ID : <span class="text-danger"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="reopenPopup" role="dialog">
          <div class="modal-dialog modal-lg">
             <div class="modal-content">
                <form id="formReopenCourse" method="POST" class="form-horizontal">
                   <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">REOPEN COURSE</h4>
                   </div>

                  <div class="modal-body row" style="margin: 10px;">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="reopenMajor">Major</label>
                          <div class="col-sm-8">
                            <input type="text" id="reopenMajor" readonly name="reopenMajor" class="form-control" >
                            <input type="hidden" id="reopenMajorId" readonly name="reopenMajorId" class="form-control" >
                            <input type="hidden" id="reopenCourseId" readonly name="reopenCourseId" class="form-control" >
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="reopenTrainerId">Trainer</label>
                            <div class="col-sm-8">
                            <select id="reopenTrainerId" name="reopenTrainerId" class="form-control">
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-4" for="reopenRoomId">Room</label>
                          <div class="col-sm-8">
                            <select id= "reopenRoomId" name="reopenRoomId" class="form-control">
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
                      </div>

                     <div class="col-md-8">
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="reopenDay">Course time: </label>
                          <div class="col-sm-8">
                              <div  class="input-group date">
                                <input class="form-control dateRange" type="text" id="rangeOfDate" name= 'rangeOfDate' >
                                <span class="input-group-addon">
                                  <i class="glyphicon glyphicon-calendar">
                                  </i>
                                </span>
                              </div>
                          </div>
                        </div>

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

                        <div class="form-group">
                          <label class="control-label col-sm-4">Day of week: </label>
                          <div class="col-sm-8">
                            <div class="row">
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="monday" id='mon'> Mon
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="tuesday" id='tue'> Tue
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="wednesday" id='wed'> Wed
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="thursday" id='thu'> Thu
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="friday" id='fri'> Fri
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="saturday" id='sat'> Sat
                              </label>
                            </div>
                            <input type="hidden" name="weekDay">
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
                    <input type="submit"  class="btn btn-success" value='Submit'>
                    <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                 </div>
                </form>
             </div>
          </div>
       </div>

<?php include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_footer.php");?>
<?php if (isset($_SESSION['success'])): ?>
  <script type="text/javascript">
    $('.popup-cart .content p').text("<?php echo $_SESSION['success'] ?>");
    $('.popup-cart').fadeIn();
    setTimeout(
      function() {
          //do something special
          $('.popup-cart').fadeOut();
      },
      3000
    );
  </script>
<?php unset($_SESSION['success']); ?>
<?php endif ?>
