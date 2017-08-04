<?php
// $_SESSION["loginUser"]="trainee";
include("inc/_header.php");?>

<section>
<div class="container">
  <div class= "page-header">
        <h3><span class="glyphicon glyphicon-th-list"></span> Course list</h3>
    </div>
  <form method="post" action="index.php?ctr=trainee&act=postCourseList" >
    <table id="coursesTable" class="order-column cell-border hover dataTable" cellspacing="0" width="100%">

      <thead>
          <tr>
            <th>CourseID</th>
            <th>Major</th>
            <th>Trainer</th>
            <th>StartDate</th>
            <th>EndDate</th>
            <th>Status</th>
          </tr>
      </thead>
      <tfoot>
          <tr>
            <th>CourseID</th>
            <th>Major</th>
            <th>Trainer</th>
            <th>StartDate</th>
            <th>EndDate</th>
            <th>Status</th>
          </tr>
      </tfoot>
      <tbody>
          <?php
              if($data['course']!=null):
                foreach ($data['course'] as $tem) :
              ?>
              <tr>
                  <?php
                    foreach ($tem as $key => $value) :
                  ?>
                   <td style="vertical-align: middle; text-align:center;"><?php echo $value; ?></td>
                   <?php endforeach;?>
                  </tr>
                <?php endforeach;?>
            <?php endif;?>
      </tbody>
    </table>
  </form>
</div>

</section>
<?php include("inc/_footer.php");?>
