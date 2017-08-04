
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Hour.php';

include("inc/_header.php");?>

<script>
$(function(){
    loadLessonsByDay("<?php echo $data['course']['id']; ?>", "", "");
});
</script>
<section>
<div class="container">
    <div class="row">

        <form id= "formUpdateCourse" action="" method="post" name="formUpdateCourse" class="form-horizontal">
            <div class="col-md-12">
                <div class="page-header">
                    <h3><span class="glyphicon glyphicon-pencil"></span> Course information </h3>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Course ID</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="true" class="form-control" name="courseId" value="<?php echo $data['course']['id'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">From date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="true" class="form-control" value="<?php echo $data['course']['startDate'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">To date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="true" class="form-control" value="<?php echo $data['course']['endDate'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Major</label>
                            <div class="col-sm-8">
                            <select name="majorId" class="form-control" disabled= "true">
                            <?php
                                $majorModel= new Major();
                                $temp= $majorModel->getAllMajor();
                                foreach($temp as $row){
                                    echo "<option value='".$row['id']."' ".(($data['course']['majorId']==$row['id'])?"selected":"").">".$row['name']."</option>";
                                }
                            ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Trainer</label>
                            <div class="col-sm-8">
                            <select name="trainerId" class="form-control">
                            <?php
                                $trainerModel= new Trainer();
                                $temp= $trainerModel->getTrainersByMajorId($data['course']['majorId']);
                                foreach($temp as $row){
                                    echo "<option value='".$row['trainerId']."' ".(($data['course']['trainerId']==$row['trainerId'])?"selected":"").">".$row['trainerName']."</option>";
                                }
                            ?>
                            </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                            <input type="submit" class="btn btn-success pull-right" value="Submit">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
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
            </div>
        </form>




        <div class="col-md-12 jumbotron" style="padding:15px;">
                <div class= "row">
                    <div class= "col-sm-11">
                        <h3><span class="glyphicon glyphicon-list-alt"></span> Lessons of a week</h3>
                    </div>
                    <div class="col-sm-1">
                        <button title='Load lesson by day' class="btn btn-warning btn-sm pull-right" onClick="loadLessonsByDay(<?php echo $data['course']['id'];?>, '', '')">All</button>
                    </div>
                </div>

            <div class="row">
                <div class="col-md-6">
                    <table class="order-column cell-border hover dataTableScroll">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Room</th>
                                <th>Range of hours</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            foreach($data['daysOfWeek'] as $day){
                                echo "<tr>";
                                echo "<td>".$day['dayOfWeek']."</td>";
                                echo "<td>".$day['roomId']."</td>";
                                echo "<td>".$day['startHour']." - ".$day['endHour']."</td>";
                                $courseId= json_encode($data['course']['id']);
                                $trainerId= json_encode($data['course']['trainerId']);
                                $dayOfWeek= json_encode($day['dayOfWeek']);
                                $roomId= json_encode($day['roomId']);
                                $rangeOfHours= json_encode($day['startHour']." - ".$day['endHour']);
                                echo "<td class='btncol'>
                                <button data-toggle='modal' title='Update this lesson' data-target='#popupUpdateLesson' class='btn btn-success btn-xs' onClick='fillUpdateLessonForm($courseId, $trainerId, $dayOfWeek, $roomId, $rangeOfHours)'><span class='glyphicon glyphicon-pencil'></span></button>
                                <button class='btn btn-warning btn-xs' onClick='loadLessonsByDay($courseId, $dayOfWeek, $rangeOfHours)'><span class='glyphicon glyphicon-chevron-right'></span></button>
                                </td>";
                                echo "</tr>";
                            }
                        ?>
                        </tbody>

                    </table>
                </div>

                <div class= "col-md-6" style="max-height: 380px; overflow-y:auto; overflow-x: hidden;">
                    <table class="table table-bordered" id="listOfLessons">
                        <thead>
                            <tr>
                                <th>Lesson ID</th>
                                <th>Date</th>
                                <th>Range of hours</th>
                                <th>Day of week</th>
                                <th>Done ?</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

</section>

<?php include("inc/_formUpdateLesson.php");?>

<?php include("inc/_footer.php");?>
