<div class="modal fade" id="popupUpdateLesson" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formUpdateLesson" method="POST" class="form-horizontal">




                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">UPDATE LESSON</h4>
                    <input type="hidden" name="notiId">
                    <input type="hidden" name="courseId">
                    <input type="hidden" name="trainerId">
                    <input type="hidden" name="oldDayOfWeek">
                    <input type="hidden" name="oldRangeOfHour">
                </div>




                <div class="modal-body row" style="margin: 10px;">


                    <div class="col-md-4">

                        <div class="form-group">
                          <label class="control-label col-sm-6">Day of week: </label>
                          <div class="col-sm-6">
                            <select class="form-control" name="dayOfWeek">
                                <?php
                                $model= new Model();
                                $enum= $model->getEnumOf('lesson', 'dayOfWeek');
                                foreach($enum as $type){
                                    echo "<option value='$type'>$type</option>";
                                }
                                ?>
                            </select>
                          </div>
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="form-group">
                          <label class="control-label col-sm-4">Room: </label>
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


                    <div class="col-md-4">

                        <div class="form-group">
                          <label class="control-label col-sm-4">Hours: </label>
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

                    </div>
                     
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="alert alert-success">
                        </div>
                    </div>

                    <div class="col-md-12">
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

                    <div class="col-md-12">
                        <table class="tableConflictTrainees table table-striped table-bordered" cellspacing="0" width="100%">
                            <caption><b>Conflict trainees</b></caption>
                            <thead>
                                <tr>
                                    <th>Trainee ID</th>
                                    <th>Full name</th>
                                    <th>Course ID</th>
                                    <th>Major</th>
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