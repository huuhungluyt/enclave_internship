
<?php
include("inc/_header.php");?>

<section>
  <div class="container">
    <div class= "page-header">
      <div class= "row">
        <div class= "col-sm-11">
          <h3><span class="glyphicon glyphicon-th-large"></span> List of rooms</h3>
        </div>
        <div class="col-sm-1">
          <button data-toggle='modal' title='Add new room' data-target='#addPopup' class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span></button>
        </div>
      </div>
    </div>
      <table id="roomTable" class="order-column cell-border hover dataTable" cellspacing="0" width="100%">

        <thead>
            <tr>
              <th>Room ID</th>
              <th>Capacity</th>
              <th>Quality</th>
              <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Room ID</th>
              <th>Capacity</th>
              <th>Quality</th>
              <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
         <?php
            foreach ($data['room'] as $row){
              echo "<tr>";
              foreach ($row as $key => $value){
                echo "<td>$value</td>";
              }
              $roomId= json_encode($row['id']);
              $roomCapacity= json_encode($row['capacity']);
              $roomQuality= json_encode($row['quality']);
              echo "<td class='btncol'><button data-toggle='modal' title='Update this room' data-target='#updatePopup' onclick='fillFormUpdateRoom($roomId, $roomCapacity, $roomQuality)' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-pencil'></span></button>";
              echo "<button class='btn btn-danger btn-xs' onclick='deleteRoom($roomId)' title='Delete this room' data-toggle='confirmation'><span class='glyphicon glyphicon-remove'></span></button></td>";
              echo "</tr>";
            }
          ?>
          </tbody>
      </table>
  </div>
</section>
  <!--POPUP-->
   <!--POPUP ADD ROOM FORM-->
   <div class="modal fade" id="addPopup" role="dialog">
      <div class="modal-dialog" style="width:600px;">
         <!-- Modal content-->
         <div class="modal-content">
            <form id="formAddRoom" method="POST" class="form-horizontal" >
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">ADD NEW ROOM</h4>
               </div>
               <div class="modal-body" style="margin: 10px;">

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="addRoomId">Room</label>
                   </div>
                   <div class="col-sm-8">
                    <input type="text" class="form-control" name="addRoomId" id="addRoomId">
                   </div>
                 </div>

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="addRoomCapacity">Capacity</label>
                   </div>
                   <div class="col-sm-8">
                    <input type="number" class="form-control" step="5" name="addRoomCapacity" id="addRoomCapacity" placeholder="<=50" min="0" max="50">
                   </div>
                 </div>

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="addRoomQuality">Quality</label>
                   </div>
                   <div class="col-sm-8">
                    <select name="addRoomQuality" id="addRoomQuality" class="form-control">
                       <option value="normal">normal</option>
                       <option value="high">high</option>
                       <option value="low">low</option>
                    </select>
                   </div>
                 </div>
             </div>

             <div class="modal-footer">
                <!-- <button id="btnAddRoom" class="btn btn-success">Submit</button> -->
                <input type="submit" id="btnAddRoom" class="btn btn-success btnAdd" value='Submit'>
                <button class="btn btn-default btn-close-popup" data-dismiss="modal">Close</button>
             </div>
          </form>
        </div>
      </div>
  </div>

   <!--POPUP-->
  <!--POPUP UPDATE ROOM FORM-->
  <div class="modal fade" id="updatePopup" role="dialog">
     <div class="modal-dialog" style="width:600px;">
        <div class="modal-content">
           <form id="formUpdateRoom" method="POST" class="form-horizontal" >
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">UPDATE ROOM</h4>
               </div>
               <div class="modal-body" style="margin: 10px;">

                 <div class="form-group">
                  <div class="control-label col-sm-4">
                     <label for="updateRoomId">Room</label>
                   </div>
                   <div class="col-sm-8">
                    <input type="text" class="form-control" name="updateRoomId" id="updateRoomId" readonly value="">
                   </div>
                 </div>

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="updateRoomCapacity">Capacity</label>
                   </div>
                   <div class="col-sm-8">
                    <input type="number" class="form-control" step="5" name="updateRoomCapacity" id="updateRoomCapacity" placeholder="<=50" min="0" max="50">
                   </div>
                 </div>

                 <div class="form-group">
                   <div class="control-label col-sm-4">
                     <label for="updateRoomQuality">Quality</label>
                   </div>
                   <div class="col-sm-8">
                    <select name="updateRoomQuality" id="updateRoomQuality" class="form-control">
                       <option value="normal" selected="selected">normal</option>
                       <option value="high">high</option>
                       <option value="low">low</option>
                    </select>
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



<?php include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_footer.php");?>
