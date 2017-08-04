<?php
$_SESSION["loginUser"]="trainee";
include("views/inc/_header.php");?>

<section>
<div class="container">
      <div class= "page-header">
            <h3><span class="glyphicon glyphicon-th-list"></span> Studying schedule</h3>
      </div>
    <table id="coursesTable" class="order-column cell-border hover dataTable" cellspacing="0" width="100%">
             
      <thead>
          <tr>
            <th>CourseID</th>
            <th>Major</th>
            <th>Trainer</th>
            <th>Start day</th>
            <th>End day</th>
            <th>Status</th>
            <th>Detail</th>
          </tr>
      </thead>
      <tfoot>
          <tr>
            <th>CourseID</th>
            <th>Major</th>
            <th>Trainer</th>
            <th>Start day</th>
            <th>End day</th>
            <th>Status</th>
            <th>Detail</th>
          </tr>
      </tfoot>
      <tbody>
          <?php 
              if($data['course']!=null){
                foreach ($data['course'] as $tem){
                   echo'<tr>';
                  foreach ($tem as $key => $value){
                    echo "<td >".htmlspecialchars($value)."</td>";  
                    $courseId=$tem['id'];               
                  }
                    echo"<td class='btncol'><button class='btn btn-success btn-xs' onclick='showDetailCourse($courseId)' data-toggle='modal' data-target='#detailCoursePopup' ><span class='glyphicon glyphicon-th-list'></span></button></td>"; 
                  } 
                  
                  echo "</tr>";
                } 
          ?>
                 
               
      </tbody>
    </table>
</div>
</section>

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
