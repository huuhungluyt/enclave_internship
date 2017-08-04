
<?php
include("inc/_header.php");?>

<section>
  <div class="container">
    <div class= "page-header row">
      <div class= "col-sm-11">
        <h3><span class="glyphicon glyphicon-knight"></span> List of trainers</h3>
      </div>
      <div class="col-sm-1">
        <button data-toggle='modal'title='Add new trainer' data-target='#addPopup'class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span></button>
      </div>
    </div>
      <table id="trainersTable" class="order-column cell-border hover dataTable" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Major</th>
              <th>Experience(Years)</th>
              <th>Email</th>
              <th>PhoneNumber</th>
              <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Major</th>
              <th>Experience(Years)</th>
              <th>Email</th>
              <th>PhoneNumber</th>
              <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($data['trainer'] as $key => $trainer): ?>
          <tr>
              <td><?php echo $trainer['id'] ?></td>
              <td><?php echo $trainer['fullName'] ?></td>
              <?php $temp = 0; ?>
              <?php foreach ($data['major'] as $key => $major): ?>
              <?php if ($major['id'] == $trainer['majorId']): ?>
              <td><?php echo $major['name'] ?></td>
              <?php else: ?>
              <?php $temp++; ?>
              <?php endif ?>
              <?php endforeach ?>
              <?php if ($temp == sizeof($data['major'])): ?>
              <td></td>
              <td></td>
              <?php else: ?>
              <td><?php echo (($trainer['experience'] == 5) ? '>= 5' : $trainer['experience']) ?></td>
              <?php endif ?>
              <td><?php echo $trainer['email'] ?></td>
              <td><?php echo $trainer['phoneNumber'] ?></td>
              <td class="btncol ">
                <button type='button' title='Update this trainer' class='btn btn-success btn-xs btnUpdatePopupTrainer'><span class='glyphicon glyphicon-pencil'></span></button>
                <button class='btn btn-danger btn-xs btn-delete-account' title='Delete this trainer' data-toggle='confirmation'><span class='glyphicon glyphicon-remove'></span></button>

              </td>
          </tr>
        <?php endforeach ?>

        </tbody>
      </table>
  </div>


      <!--POPUP-->
              <!--POPUP ADD TRAINER FORM-->
  <div class="modal fade" id="addPopup" role="dialog">
     <div class="modal-dialog" style="width: 600px;">
        <!-- Modal content-->
        <div class="modal-content">
           <form id="formAddTrainer" method="POST" class="form-horizontal">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">ADD TRAINER</h4>
              </div>
              <div class="modal-body" style="margin: 10px;">

                <div class="form-group">
                    <div class="control-label col-sm-4">
                        <label for="exampleInputEmail1">Full Name</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: Lucky Luke" name="fullName">
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
                         <input class="form-control datee" style="background-color:white" readonly type="text" id="dateOfBirth" name= 'dateOfBirth' >
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
                        <label for="PhoneNumber">Phone Number</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: 0969696969" name="phoneNumber">
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-label col-sm-4">
                        <label for="majors">Major</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="majorId" class="form-control">
                        <?php if (isset($data['major'])): ?>
                        <?php foreach ($data['major'] as $major): ?>
                        <option value="<?php echo $major['id']?>"><?php echo $major['name'] ?></option>
                        <?php endforeach ?>
                        <?php endif ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                  <div class="control-label col-sm-4">
                    <label for="Experiences">Experiences</label>
                  </div>
                  <div class="col-sm-8">
                    <select name="experience" class="form-control">
                        <option value="1">1 year</option>
                        <option value="2">2 years</option>
                        <option value="3">3 years</option>
                        <option value="4">4 year</option>
                        <option value="5">>=5 years</option>

                    </select>
                  </div>
                </div>
                </div>
                <div class="modal-footer">
                   <input type="submit" id="btnAddTrainer" class="btn btn-success btnAdd" value='Add'>
                   <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                </div>
             </form>
          </div>
       </div>
    </div>
  <!--POPUP-->
  <!--POPUP UPDATE TRAINER FORM-->
  <div class="modal fade" id="updatePopup" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <form id="formUpdateTrainer" action="index.php?ctr=trainer&act=postUpdate" method="POST" class="form-horizontal" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">UPDATE TRAINER</h4>
          </div>
          <!-- end modal header -->
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
                <div class="form-group">
                  <div class="control-label col-sm-4">
                      <label for="exampleInputEmail1">Full Name</label>
                  </div>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" placeholder="@ex: Lucky Luke" name="fullName">
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
                      <label for="dateOfBirth">Date of birth</label>
                   </div>
                   <div class="col-sm-8">
                      <div  class="input-group date" style="width: 100%">
                         <input class="form-control date1" style="background-color: white" readonly type="text" id="dateOfBirth" name= 'dateOfBirth' >
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
              <!-- end col -->
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
                        <label for="PhoneNumber">Phone Number</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="@ex: 0969696969" name="phoneNumber">
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-label col-sm-4">
                        <label for="majors">Major</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="majorId" class="form-control">
                        <?php if (isset($data['major'])): ?>
                        <?php foreach ($data['major'] as $major): ?>
                        <option value="<?php echo $major['id']?>"><?php echo $major['name'] ?></option>
                        <?php endforeach ?>
                        <?php endif ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                  <div class="control-label col-sm-4">
                    <label for="Experiences">Experiences</label>
                  </div>
                  <div class="col-sm-8">
                    <select name="experience" class="form-control">
                        <option value="1">1 year</option>
                        <option value="2">2 years</option>
                        <option value="3">3 years</option>
                        <option value="4">4 year</option>
                        <option value="5">>=5 years</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->
          </div>
          <!-- end modal body -->
          <div class="modal-footer">
             <button type='submit' id="btnUpdateTrainer" class="btn btn-success">Update</button>
             <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
          </div>
          <!-- end modal footer -->
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="delete-account-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="index.php?ctr=trainer&act=postDelete" method="post" accept-charset="utf-8">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">DELETE TRAINER</h4>
          </div>
          <div class="modal-body">

              <input type="hidden" name="id" value="">
              Are you sure to delete the trainer who has ID : <span class="text-danger"></span>
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
