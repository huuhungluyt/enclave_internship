<?php
$_SESSION["loginUser"]="trainee";
include("views/inc/_header.php");?>

<section>
<div class="container">
  <div class="row">

    <div class="col-md-8">
      <div class= "page-header">
        <h3><span class="glyphicon glyphicon-education"></span> Registered courses</h3>
      </div>
      <table class="order-column cell-border hover dataTable" cellspacing="0" width="100%" id="registeredCourseInfo">
        <thead>
          <tr>
            <th>CourseID</th>
            <th>Major</th>
            <th>Trainer</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>CourseID</th>
            <th>Major</th>
            <th>Trainer</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </tfoot>
        <tbody>
        <?php 
        foreach ($data['registered'] as $item) {
            echo "<tr>";
            foreach ($item as $key => $value){
              echo'<td>'.htmlspecialchars($value).' </td>';
              $courseId=$item['courseId'];
            }
              echo "<td class='btncol'><button title='Show detail of this course' class='btn btn-success btn-xs' data-toggle='modal' data-target='#detailCoursePopup' onclick='showDetailCourse($courseId)' ><span class='glyphicon glyphicon-th-list'></span></button>";
              echo "<button title='Unregister this course' class='btn btn-danger btn-xs btn-delete-registered-course'  data-toggle='confirmation'><span class='glyphicon glyphicon-remove'></span></button></td>";
              echo "</tr>";

            }
          ?>         
        </tbody>
      </table>
    </div>

    <div class="col-md-4" >
      <div class= "page-header">
        <h3><span class="glyphicon glyphicon-send"></span> Your suggestion</h3>
      </div>
      <table class="order-column cell-border hover dataTable" cellspacing="0" width="100%"  id="" >
        <thead>
          <tr>
            <th>Type</th>
            <th>Content</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Type</th>
            <th>Content</th>
          </tr>
        </tfoot>
        <tbody>
        <?php
          foreach ($data['suggest'] as $key => $item):
          if(empty($item['content'])&&empty($item['type'])) continue;
          $content = explode(';', $item['content']);
        ?>
          <tr>
            <td><?php echo $item['type'] ?></td>
            <?php if ($item['type']=='requested course'){ ?>
            <td><?php echo $content[1]; ?></td>
            <?php }else{?>
            <td><?php echo "Name: ".$content[0]; ?> <br>
                <?php echo "Description: ".$content[1]; ?>
            </td>
            <?php } ?>
          </tr>  
        <?php
          endforeach
        ?>  
        </tbody>
      </table>
    </div>

  </div>
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

<!-- popup delete course -->
<div class="modal fade" id="delete-registered-course-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?ctr=course&act=postDeleteRegisteredCourse" method="post" accept-charset="utf-8">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">DELETE Registered Course</h4>
        </div>
        <div class="modal-body">

            <input type="hidden" name="id" value="">
            Are you sure to delete the Registered Course which has the major name is <span class="text-danger major"></span> by the Trainer is <span class="text-danger trainer"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--  -->


<?php include("views/inc/_footer.php");?>
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