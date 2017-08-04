
<?php
include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_header.php");?>

<section>
  <div class="container">
    <div class= "page-header row">
      <div class= "col-sm-11">
        <h3><span class="glyphicon glyphicon-blackboard"></span> List of major</h3>
      </div>
      <div class="col-sm-1">
        <button title="Add new major" data-toggle='modal' data-target='#popupAddMajor' class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span></button>
      </div>
    </div>
      <table id="majorTable" class="order-column cell-border hover dataTable" cellspacing="0" width="100%">

        <thead>
            <tr>
              <th>Major ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
               <th>Major ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
         <?php
            foreach ($data['major'] as $tem){
              echo "<tr>";
              foreach ($tem as $value){
                echo "<td >".htmlspecialchars($value)."</td>";
              }
              $id= json_encode($tem['id']);
              $name= json_encode($tem['name']);
              $description= json_encode($tem['description']);
              echo "<td class='btncol'><button onclick='loadTrainerList($id)' title='List of trainer' data-toggle='modal' data-target='#trainerListPopup' class='btn btn-warning btn-xs' ><span class='glyphicon glyphicon-list'></span></button>";
              echo "<button onclick='fillFormUpdateMajor($id,$name, $description)' title='Update major' data-toggle='modal' data-target='#updateMajorPopup' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-pencil' ></span></button>";
              echo "<button class='btn btn-danger btn-xs' onclick='deleteMajor($id,$name)' title='Delete this major' data-toggle='confirmation'><span class='glyphicon glyphicon-remove'></span></button></td>";
              echo "</tr>";
            }
          ?>
          </tbody>
      </table>
  </div>
</section>
  <!--POPUP-->
   <!--POPUP ADD MAJOR FORM-->
  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_formAddMajor.php");?>

   <!--POPUP-->
  <!--POPUP UPDATE ROOM FORM-->
  <div class="modal fade" id="updateMajorPopup" role="dialog">
     <div class="modal-dialog" style="width:600px;">
        <!-- Modal content-->
        <div class="modal-content">
           <form id="formUpdateMajor" method="POST" class="form-horizontal" >
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">UPDATE MAJOR</h4>
               </div>
               <div class="modal-body" style="margin: 10px;">

                 <div class="form-group">
                  <div class="control-label col-sm-4">
                     <label for="updateMajorId">Major ID</label>
                   </div>
                   <div class="col-sm-8">
                    <input type="text" class="form-control" name="updateMajorId" id="updateMajorId" readonly value="">
                   </div>
                 </div>

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="updateMajorName">Name</label>
                   </div>
                   <div class="col-sm-8">
                    <input type="text" class="form-control" name="updateMajorName" id="updateMajorName">
                   </div>
                 </div>

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="updateMajorDescription">Description</label>
                   </div>
                   <div class="col-sm-8">
                    <TEXTAREA id="updateMajorDescription" name="updateMajorDescription" rows="3" cols="41" axlength="200" style="resize: none;"></TEXTAREA>
                   </div>
                 </div>
             </div>

             <div class="modal-footer">
                <input type="submit" class="btn btn-success" value='Submit'>
                <input type="button" class="btn btn-default btn-close-popup" data-dismiss="modal" value='Close'>
             </div>
          </form>
      </div>
    </div>
  </div>

  <!--POPUP SHOW TRAINER LIST -->
  <div class="modal fade" id="trainerListPopup" role="dialog">
     <div class="modal-dialog" style="width:600px;">
        <!-- Modal content-->
        <div class="modal-content">
           <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">LIST OF TRAINER</h4>
           </div>

           <div class="modal-body" style="margin: 10px;" >
              <table id="tableTrainerList" class="order-column cell-border hover" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                            <th>Trainer ID</th>
                            <th>Fullname</th>
                            <th>Experience (years)</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
              </table>
            </div>
      </div>
    </div>
  </div>
<?php
include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_footer.php");
?>
