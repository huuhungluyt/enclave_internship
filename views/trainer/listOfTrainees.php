
<?php
$_SESSION["loginUser"]="trainer";
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';

include("views/inc/_header.php");
?>
<section id="section-list-trainee">
<div class="container">

  <form method="post" action="index.php?ctr=course&act=postUpdateStatusTrainee" >
    <div style="margin-bottom: 20px; border-bottom: 1px solid #ccc;">
      <h3 class="pull-left">
        <span class="glyphicon glyphicon-education"></span> 
        List of trainees in course
      </h3>
      <?php if ($data['state'] == 'opened' ): ?>
      <input type="hidden" name="courseId" value="<?php echo $data['courseId'] ?>">
      <input style="margin-top: 17px;" type="submit" class="btn btn-success pull-right" id='update-status-trainee' name="updateStatus" id="submit" value="Submit">  
      <?php endif ?>   
      <div style="clear: both;"></div>       
    </div>

    <table class="order-column cell-border hover dataTableListTrainee" id="listOfTrainees">
        <thead>
          <tr>
            <th>Trainee ID</th>
            <th>Full name</th>
            <th>Gender</th>
            <th>Phone number</th>
            <th>Address</th>
            <th>Email</th>
            <th>Result</th>
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
            <th>Result</th>
          </tr>
        </tfoot>
                  
        <tbody>
          
        <?php
          if($data['trainee'] != null){
            foreach ($data['trainee'] as $tem) {
              echo '<tr>';
              echo '<input type="hidden" name="id[]" value="'.$tem['id'].'">';
              foreach ($tem as $key => $value) {
                if($key=="status"){
                  $status=$tem['status'];
                } else{
                echo '<td>'.$value.'</td>';
                }
              };
        ?>
            <td class="btncol">
            <?php if ($data['state'] != 'opened'): ?>
            <b><?php echo $status; ?></b>
            <?php else: ?>
              <select class="form-control status" name="status[]">
                <?php
                  $model= new Model();
                  $enum= $model->getEnumOf('learning', 'status');
                  foreach($enum as $type){
                    echo "<option value='$type' ";
                    if($type==$status) echo "selected='selected'";
                    echo " >$type</option>";
                  }
                ?>
              </select>
            <?php endif ?>
              
            </td>
            </tr>
        <?php }
          }
        ?>

        </tbody>
      </table>
    </form>
</div>
</section>
 
<!-- popup display sucessful notification -->
<div class="popup-cart">
  <div class="content">
      <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
      <p></p>
  </div>
</div>
<?php if(isset($_SESSION['updateTraieeSuccess'])): ?>
<script> 
var data = "<?php echo $_SESSION['updateTraieeSuccess']; ?>";
$('.popup-cart .content p').text(data);        
$('.popup-cart').fadeIn();
setTimeout(
    function() 
    {
    //do something special
    $('.popup-cart').fadeOut();
}, 3000);       
</script>
<?php unset($_SESSION['updateTraieeSuccess']); ?>
<?php endif; ?>  

<?php include("views/inc/_footer.php");?>
