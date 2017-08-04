<?php
$_SESSION["loginUser"]="trainee";
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainer.php';
include("views/inc/_header.php");?>

<section>
<div class="container">
    <div class= "page-header row">
      <div class= "col-sm-11">
        <h3><span class="glyphicon glyphicon-th-list"></span> Available courses<h3/></h3>
      </div>
      <div class="col-sm-1">
        <button data-toggle='modal' data-target='#suggestPopup' class="btn btn-primary pull-right" style="margin-left: 10px;">Suggest new course</button>
      </div>
  </div>
  <table class="order-column cell-border hover dataTable" id="courseInfo">
        <thead>
           <tr>
              <th>CourseID</th>
              <th>Major</th>
              <th>Trainer</th>
              <th>Start day</th>
              <th>End day</th>
              <th>Detail</th>
              <th>Register</th>
           </tr>
        </thead>

        <tfoot>
           <tr>
              <th>CourseID</th>
              <th>Major</th>
              <th>Trainer</th>
              <th>Start day</th>
              <th>End day</th>
              <th>Detail</th>
              <th>Register</th>
           </tr>
        </tfoot>

        <tbody>
             <?php
                if($data['course']!=null){
                  foreach ($data['course'] as $tem){
                    echo "<tr>";
                    foreach ($tem as $key => $value){
                      echo"<td>$value</td>";
                      $courseId=$tem['id'];
                    }
                    echo"<td class='btncol'><button type='button' class='btn btn-warning btn-xs' id='btnDetailCourse' data-toggle='modal' data-target='#detailCoursePopup' onclick='showDetailCourse($courseId)'  ><span class='glyphicon glyphicon-th-list'></span></button></td>";
                    echo"<td class='btncol'>
                      <input type='button' class='btn-submit btn btn-success btn-xs btnAdd' value='Submit'></td>";
                    echo "</tr>";
                  }
                }
              ?>
        </tbody>
     </table>
</div>
</section>

<div class="modal fade" id="suggestPopup" role="dialog">
         <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form name="formSuggestNewCourse" id="formSuggestNewCourse" class="form-horizontal" method="post" >
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">SUGGEST NEW COURSE</h4>
                  </div>
                  <div class="modal-body row" style="margin: 10px;">
                    
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="availableMajor">
                                <input type="radio" id="availableMajor" name="choose" value="available_major" checked="checked">Available major
                            </label>
                        </div>

                        <div class="form-group">
                            <select id="major" name="major" value="major" class="form-control">
                            <?php
                                $majorModel= new Trainer();
                                $data= $majorModel->getMajorId();
                                foreach($data as $row){
                                    foreach ($row as $key => $value) {
                                      if($key=="name"){
                                         echo "<option value='".$row['id']."' >".$row['name']."</option>";
                                       }
                                     }
                                 
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                     <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-6" for="newMajor">
                                <input type="radio" id="newMajor" name="choose" value="new_major" checked="checked" >New major
                            </label>
                        </div>

                        <div class="form-group">
                             <div class="control-label col-sm-4">
                               <label for="majorName">Name</label>
                             </div>
                           <div class="col-sm-8">
                               <input type="text" class="form-control" name="majorName" id="majorName" >
                           </div>
                         </div>

                         <div class="form-group">
                           <div class="control-label col-sm-4">
                               <label for="description">Description</label>
                           </div>
                           <div class="col-sm-8">
                               <TEXTAREA id="majorDescription"  name="majorDescription" rows="4" cols="41" maxlength="200" style="resize: none;"></TEXTAREA>
                           </div>
                         </div>
                     </div>
                       
                  </div>
                  <div class="modal-footer">
                     <input type="submit" id="btnSuggestNewCourse" class="btn btn-success" name="btnSuggestNewCourse" value='Send'>
                     <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                  </div>
                 </form>
               
            </div>
         </div>
      </div>
<!--POPUP SHOW DETAIL COURSE -->
  <div class="modal fade" id="detailCoursePopup" role="dialog">
     <div class="modal-dialog" style="width:600px;">
        <!-- Modal content-->
        <div class="modal-content">
           <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">SCHEDULE</h4>
           </div>

           <div class="modal-body" style="margin: 10px;" >
              <table id="tableDetailCourse" class="order-column cell-border hover" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                            <th>CourseID</th>
                            <th>Room</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>Weekday</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
              </table>
            </div>
      </div>
    </div>
  </div>

<?php include("views/inc/_footer.php");?>