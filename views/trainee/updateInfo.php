
<?php
$_SESSION["loginUser"]="trainee";
include("views/inc/_header.php");?>


<section>
<div class= "container">
    <div class= "page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Your information</h3>
    </div>
    <div class= "row" id="updateInfor">
        <form id="formUpdateInfoTrainee" name="formUpdateInfoTrainee" class="form-horizontal" method="post" action="index.php?ctr=trainee&act=postUpdateInfor" enctype="multipart/form-data">
            <div class= "col-md-6">

                <div class="form-group">
                    <label class="control-label col-sm-4" for="traineeId">ID</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="id" id="id" readonly value="<?php echo $_SESSION['trainee_infor']['id'];?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="fullName">Full name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="fullName" id="fullName" value="<?php echo $_SESSION['trainee_infor']['fullName'];?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="gender">Gender</label>
                    <div class="col-sm-8">
                        <select type="text" class="form-control" name="gender" id="gender">
                             <?php
                                    $model= new Model();
                                    $enum= $model->getEnumOf('account', 'gender');
                                    foreach($enum as $type){
                                        echo "<option value='$type' ";
                                        if($type==$_SESSION["trainee_infor"]["gender"]) echo "selected='selected'";
                                        echo " >$type</option>";
                                    }
                                ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="dateOfBirth">Date of birth</label>
                    <div class="col-sm-8">
                        <div  class="input-group date" style="width: 100%">
                            <input  class="form-control datee" type="text" id="dateOfBirth" name= 'dateOfBirth' style="background-color:white" readonly value="<?php echo $_SESSION["trainee_infor"]['dateOfBirth'];?>">                            
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="address">Address</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="address" name= 'address' value="<?php echo $_SESSION["trainee_infor"]['address'];?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="phoneNumber">Phone number</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="phoneNumber" name= 'phoneNumber' value="<?php echo $_SESSION["trainee_infor"]['phoneNumber'];?>">
                    </div>
                </div>

            </div>

            <div class= "col-md-6">

                <div class="form-group">
                    <label class="control-label col-sm-4" for="email">Email</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="email" name= 'email' value="<?php echo $_SESSION["trainee_infor"]['email'];?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="profilePic">Profile picture</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="file" id="profilePic" name="profilePic" readonly value="">
                            <br>
                             <?php
                                                if ($_SESSION['trainee_infor']['profilePic']=='account.jpg'|| $_SESSION['trainee_infor']['profilePic']==null){

                                     ?>
                                                    <span class="glyphicon glyphicon-user icon-size"></span>

                                                <?php
                                                    }else{
                                                 ?>

                                                    <img style="width:100px;height:100px;" src="<?php echo  "http://" . $_SERVER['HTTP_HOST']. "/upload/Trainee/" . $_SESSION["trainee_infor"]["profilePic"] ?>">
                                                <?php
                                                     }
                                                ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="school">School</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="school" name= 'school' value="<?php echo $_SESSION["trainee_infor"]['school'];?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="faculty">Faculty</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="faculty" name= 'faculty' value="<?php echo $_SESSION["trainee_infor"]['faculty'];?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="typeOfStudent">Type of student</label>
                    <div class="col-sm-8">
                        <select type="text" class="form-control" name="typeOfStudent" id="typeOfStudent" value>
                            <?php
                                    $model= new Model();
                                    $enum= $model->getEnumOf('trainee', 'typeOfStudent');
                                    foreach($enum as $type){
                                        echo "<option value='$type' ";
                                        if($type==$_SESSION["trainee_infor"]["typeOfStudent"]) echo "selected='selected'";
                                        echo " >$type</option>";
                                    }
                                ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success pull-right" id="btnUpdateTrainee" value="Update">
                    </div>
                   <!--  <div class="col-sm-2">
                        <button class="btn pull-right" name="Cancel"  id="btn-close-form">Cancel</button>
                    </div> -->
                </div>
            </div>
        </form>
    </div>



<!--PASSWORD-->
    <div class="page-header">
        <h4><span class="glyphicon glyphicon-lock"></span> Password </h4>
    </div>

    <div class="row">
        <form id= "changePasswordTrainee" action="" method="post" name="changePasswordTrainee" class="form-horizontal">            
            <div class="col-md-6">

                <div class="form-group">
                    <label class="control-label col-sm-4" for="currentPass">Current password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="currentPass" id="currentPass">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="newPassword">New Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="newPassword" id="newPassword">
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="form-group">
                    <label class="control-label col-sm-4" for="confirmPassword">Confirm password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success pull-right" value="Save" name="savePassword" id="savePassword">
                    </div>
                </div>
            </div>

            
        </form>
    </div>

</div>

</section>





<?php include("views/inc/_footer.php");?>

