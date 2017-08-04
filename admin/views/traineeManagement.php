
<?php include("inc/_header.php");?>

<section>

  <div class="container">
    <div class= "page-header row">
      <div class= "col-sm-11">
        <h3><span class="glyphicon glyphicon-education"></span> List of trainees</h3>
      </div>
      <div class="col-sm-1">
        <button data-toggle='modal' title='Add new trainee' data-target='#addPopup'class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span></button>
      </div>
    </div>
    <table class="order-column cell-border hover dataTable" cellspacing="0" width="100%" id="traineesTable">
      <thead>
         <tr>
            <th>ID</th>
            <th>Fullname</th>
            <th>Gender</th>
            <th>School</th>
            <th>Faculty</th>
            <th>TypeOfStudent</th>
            <th>Action</th>
         </tr>
      </thead>
      <tfoot>
         <tr>
           <th>ID</th>
           <th>Fullname</th>
           <th>Gender</th>
           <th>School</th>
           <th>Faculty</th>
           <th>TypeOfStudent</th>
           <th>Action</th>
         </tr>
      </tfoot>
      <tbody>
      <?php foreach ($data['trainee'] as $tem): ?>
        <tr>
          <?php foreach ($tem as $key => $trainee):
            if ($key=='id'||$key=='fullName'||$key=='gender'||$key=='school'||$key=='faculty'||$key=='typeOfStudent')
            echo "<td>$trainee</td>";
          ?>
          <?php endforeach ?>
          <td class="btncol">
            <button onclick="window.location.href='index.php?ctr=trainee&act=postCourseList&id=<?php echo $tem['id'];?>'" title='Show schedule for this trainee' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-list-alt'></span></button>
            <button type='button' title='Update this trainee' class='btn btn-success btn-xs btnUpdatePopupTrainee'><span class='glyphicon glyphicon-pencil'></span></button>
            <button class='btn btn-danger btn-xs btn-delete-account' title='Delete this trainee' data-toggle='confirmation'><span class='glyphicon glyphicon-remove'></span></button>
          </td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>

  </div>
  <!--POPUP-->
  <!--POPUP UPDATE TRAINEE FORM-->
  <div class="modal fade" id="updatePopup" role="dialog">
     <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
           <form id="formUpdateTrainee" action="index.php?ctr=trainee&act=postUpdate" method="POST" class="form-horizontal" enctype="multipart/form-data">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">UPDATE TRAINEE</h4>
              </div>
              <div class="modal-body" style="margin: 10px;">
                <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                      <div class="control-label col-sm-4">
                          <label for="exampleInputEmail1">ID</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text"  class="form-control" readonly placeholder="@ex: 102130001" name="id">
                      </div>
                    </div>

                    <!-- <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="pass">Password</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="password" id="password" class="form-control" placeholder="at least 6 characters" name="password">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="pass">Confirmation Password</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" placeholder="at least 6 characters" name="confirmPassword">
                      </div>
                    </div> -->
                    <div class="form-group">
                      <div class="control-label col-sm-4">
                          <label for="exampleInputEmail1">Full Name</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text"  class="form-control" placeholder="@ex: Lucky Luke" name="fullName">
                      </div>
                    </div>
                    <div class="form-group">
                       <div class="control-label col-sm-4">
                          <label for="dateOfBirth">Date of birth</label>
                       </div>
                       <div class="col-sm-8">
                          <div  class="input-group date" style="width: 100%">
                             <input class="form-control date1" style="background-color:white" readonly type="text" id="dateOfBirth" name= 'dateOfBirth' >
                          </div>
                       </div>
                    </div>
                  </div>
                  <!-- end col -->
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                      <div class="col-sm-8" id="profile-picture">

                      </div>
                      <div class="col-sm-4">
                        <input type="file" style="color: transparent;" name="profilePic">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end row -->
                <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="gender">Gender</label>
                      </div>
                      <div class="col-sm-8">
                        <select name="gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="Address">Address</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: 455, Hoang Dieu, Danang" name="address">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="exampleInputEmail1">E-mail</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: abc@enclave.com" name="email">
                      </div>
                    </div>
                  </div>
                  <!-- end col -->
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="phoneNumber">Phone Number</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: 0969696969" name="phoneNumber">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="Schol">School</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: Danang University of Science and Technology" name="school">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="faculty">Faculty</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: Information Technology" name="faculty">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="control-label col-sm-4">
                        <label for="faculty">Type of Student</label>
                      </div>
                      <div class="col-sm-8">
                        <select name="typeOfStudent" class="form-control">
                          <option value="freshman">Freshman</option>
                          <option value="sophomore">Sophomore</option>
                          <option value="junior">Junior</option>
                          <option value="super junior">Super junior</option>
                          <option value="senior">Senior</option>
                        </select>
                      </div>
                    </div>

                  </div>
                  <!-- end col -->
                </div>
              <!-- end row -->
              </div>
              <div class="modal-footer">
                 <button type='submit' id="btnUpdateTrainee" class="btn btn-success">Update</button>
                 <input type="button" id="btn-close-form" class="btn btn-default" data-dismiss="modal" value='Close'>
              </div>
           </form>
        </div>
     </div>
  </div>
  <!--POPUP-->
  <!--POPUP ADD TRAINEE FORM-->
  <div class="modal fade" id="addPopup" role="dialog">
     <div class="modal-dialog" style="width:600px;">
        <!-- Modal content-->
        <div class="modal-content">
           <form id="formAddTrainee" method="POST" class="form-horizontal">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">ADD TRAINEE</h4>
              </div>
            <div class="modal-body" style="margin: 10px;">

              <div class="form-group">
                <div class="control-label col-sm-4">
                    <label for="exampleInputEmail1">Full Name</label>
                </div>
                <div class="col-sm-8">
                  <input type="text"  class="form-control" placeholder="@ex: Lucky Luke" name="fullName">
                </div>
              </div>
              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="gender">Gender</label>
                </div>
                <div class="col-sm-8">
                  <select name="gender" class="form-control">
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="other">Other</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                 <div class="control-label col-sm-4">
                    <label for="dateOfBirth">Birthday </label>
                 </div>
                 <div class="col-sm-8">
                    <div  class="input-group date" style="width: 100%">
                       <input class="form-control datee" type="text" style="background-color: white" readonly id="dateOfBirth" name= 'dateOfBirth' >

                    </div>
                 </div>
              </div>
              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="Address">Address</label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="@ex: 455, Hoang Dieu, Danang" name="address">
                </div>
              </div>

              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="exampleInputEmail1">E-mail</label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="@ex: abc@enclave.com" name="email">
                </div>
              </div>

              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="phoneNumber">Phone Number</label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="@ex: 0969696969" name="phoneNumber">
                </div>
              </div>

              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="Schol">School</label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="@ex: Danang University of Science and Technology" name="school">
                </div>
              </div>

              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="faculty">Faculty</label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="@ex: Information Technology" name="faculty">
                </div>
              </div>

              <div class="form-group">
                <div class="control-label col-sm-4">
                  <label for="typeOfStudent">Type of Student</label>
                </div>
                <div class="col-sm-8">
                  <select name="typeOfStudent" class="form-control">
                      <option value="freshman">Freshman</option>
                      <option value="sophomore">Sophomore</option>
                      <option value="junior">Junior</option>
                      <option value="super junior">Super junior</option>
                      <option value="senior">Senior</option>
                  </select>
                </div>
              </div>

              </div>
              <div class="modal-footer">
                 <input type="submit" id="btnAddTrainee" class="btn btn-success btnAdd" value='Add'>
                 <input type="button" class="btn btn-default btnClose" data-dismiss="modal" value='Close'>
              </div>
           </form>
        </div>
     </div>
  </div>

  <div class="modal fade" id="delete-account-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="index.php?ctr=trainee&act=postDelete" method="post" accept-charset="utf-8">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">DELETE TRAINEE</h4>
          </div>
          <div class="modal-body">

              <input type="hidden" name="id" value="">
              Are you sure to delete the trainee who has ID : <span class="text-danger"></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include("inc/_footer.php");?>
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

<?php if (isset($_SESSION['error'])): ?>
  <script type="text/javascript">
    $('.popup-cart .content p').text("<?php echo $_SESSION['error'] ?>");
    $('.popup-cart').fadeIn();
    setTimeout(
      function() {
          //do something special
          $('.popup-cart').fadeOut();
      },
      3000
    );
  </script>
<?php unset($_SESSION['error']); ?>
<?php endif ?>
