<div class="modal fade" id="popupAddCourse" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formAddCourse" method="POST" class="form-horizontal">




                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ADD NEW COURSE</h4>
                </div>




                <div class="modal-body row" style="margin: 10px;">


                    <div class="col-md-4">

                        <div class="form-group">
                          <label class="control-label col-sm-4">Major</label>
                          <div class="col-sm-8">
                            <input type="hidden" name="notiId">
                            <select name="majorId" class="form-control">
                            <?php
                                $majorModel= new Major();
                                $temp= $majorModel->getAllMajor();
                                foreach($temp as $row){
                                  echo "<option value='".$row['id']."' >".$row['name']."</option>";
                                }
                            ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-4">Trainer</label>
                          <div class="col-sm-8">
                            <select name="trainerId" class="form-control">
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-4">Room</label>
                          <div class="col-sm-8">
                            <select name="roomId" class="form-control">
                            <?php
                                $roomModel= new Room();
                                $temp= $roomModel->getRoom();
                                foreach($temp as $room){
                                  echo "<option value='".$room['id']."' >".$room['id']." (capacity: ".$room['capacity'].", quality: ".$room['quality'].")</option>";
                                }
                            ?>
                            </select>
                          </div>
                        </div>
                    
                    </div>


                    <div class="col-md-8">

                        <div class="form-group">
                          <label class="control-label col-sm-4">Range of dates: </label>
                          <div class="col-sm-8">
                              <div  class="input-group date">
                                <input class="form-control dateRange" type="text" name= "rangeOfDate" >
                                <span class="input-group-addon">
                                  <i class="glyphicon glyphicon-calendar">
                                  </i>
                                </span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-4">Range of hours: </label>
                          <div class="col-sm-8">
                            <select name="rangeOfHour" class="form-control">
                            <?php
                                $hourModel= new Hour();
                                $hours= $hourModel->selectHours();
                                foreach($hours as $hour){
                                  echo "<option value='".$hour['startHour']." - ".$hour['endHour']."' >".$hour['startHour']." - ".$hour['endHour']."</option>";
                                }
                            ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-4">Day of week: </label>
                          <div class="col-sm-8">
                            <div class="row">
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="mon"> Mon
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="tue"> Tue
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="wed"> Wed
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="thu"> Thu
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="fri"> Fri
                              </label>
                              <label class="control-label col-sm-2">
                                <input type="checkbox" class="week-day" name="sat"> Sat
                              </label>
                            </div>
                            <input type="hidden" name="weekDay">
                          </div>
                        </div>

                    </div>
                     

                    <div class="col-md-12" style="height: 200px; overflow-y:auto; overflow-x: hidden;">
                        <table class="tableConflictLessons table table-striped table-bordered" cellspacing="0" width="100%">
                          <caption><b>Conflict lessons</b></caption>
                          <thead>
                              <tr>
                                <th>Course ID</th>
                                <th>Major</th>
                                <th>Trainer</th>
                                <th>Room</th>
                                <th>Time start</th>
                                <th>Time end</th>
                                <th>State</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                    </div>


                </div>




                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value='Submit'>
                    <input type="button" class="btn btn-default" data-dismiss="modal" value='Close'>
                </div>



                
            </form>
        </div>
    </div>
</div>