
<?php
$_SESSION["loginUser"]="trainer";
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';

include("inc/_header.php");?>

<section>
<div class="container">




  <div class= "page-header">
      <h3><span class="glyphicon glyphicon-education"></span> List of trainees in course</h3>
  </div>
  <form method="post" action="index.php?ctr=course&act=postUpdateStatus" >
    <table class="order-column cell-border hover dataTable" id="listOfTrainees">
        <thead>
          <tr>
            <th>Trainee ID</th>
            <th>Full name</th>
            <th>Gender</th>
            <th>Phone number</th>
            <th>Address</th>
            <th>Email</th>
            <th>Status</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <th>Trainee ID</th>
            <th>Full name</th>
            <th>Gender</th>
            <th>Phone number</th>
            <th>Address</th>
            <th>Email</th>
            <th>Status</th>
          </tr>
        </tfoot>

        <tbody>

        <?php
          if($data['trainee'] != null){
            $count=0;
            $count1=0;
            foreach ($data['trainee'] as $tem) {
              if($tem['status']=="passed" ||  $tem['status']=="failed"){
                  $count++;
              } else if($tem['status']=='waiting'){
                  $count1++;
              }
              echo '<tr>';
              echo '<input type="hidden" name="id" value="'.$tem['id'].'">';
              foreach ($tem as $key => $value) {
                if($key=="status"){
                  $status=$tem['status'];
                } else{
                echo '<td >'.$value.'</td>';
                }
              };
        ?>
            <td class="btncol">
                <?php
                  $model= new Model();
                  $enum= $model->getEnumOf('learning', 'status');
                  foreach($enum as $type){
                    if($type==$status) echo "$type";
                  }
                ?>
            </td>
            </tr>
        <?php }
          }
        ?>

        </tbody>
      </table>
      <?php
        if($data['course']['state']=='opened' && $count == sizeof($data['trainee'])){
          echo "<input title='Close this course' type='button' class='btn-close btn btn-danger pull-right btnClose' value='Close' >";
        }

        if($data['course']['state']=='approved' && $count1 >=3){
          echo "<input title='Open this course' type='button' class='btn-open btn btn-success pull-left btnOpen' value='Open'>";
        }
      ?>
    </form>
</div>

</section>

<?php include("inc/_footer.php");?>
