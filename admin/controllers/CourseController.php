<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Lesson.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Notification.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/controllers/NoticeController.php';

class CourseController extends Controller{
	public function passCourseToJS(){
		if(isset($_SESSION['admin_infor'])&&isset($_POST['courseId'])){
			$courseId= $_POST['courseId'];
			$courseModel= new Course();
			$course= $courseModel->getCourseById($courseId);
			if($course) echo json_encode($course);
		}
	}

	public function getUpdateCourseView(){
		$courseModel= new Course();
		$data= array();
		$data['daysOfWeek']= $courseModel->selectDetailDaysInWeek($_GET['courseId']);
		$data['course']=$courseModel->getCourseById($_GET['courseId']);
		$this->render('updateCourse',$data);
	}

	public function postSchedule () {
		//echo 'heeeee';
		$course = new Course();
		//$course_id = $_SESSION['user_infor']['id'];
		$data = [];
		$data['course'] = $course->getSchedule();
		$data['approved']= $course->getCourseList();
		$data['default']= $course->getDefaultCourse($course->getNumberOfTrainee());
		$this->render('courseManagement',$data);
	}

	public function postTraineeListByCourseId () {
		$course = new Course();
		$data = [];
		$data['trainee'] = $course->getTraineeListByCourseId($_GET['id']);
		$data['course']=$course->getCourseById($_GET['id']);
		$this->render('listOfTrainees',$data);
	}



	//HUNTER - Load conflict lesson when add new course
	public function loadConflictLessons(){
		$weekDays=NULL;
		$trainerId=$roomId=$startDate=$endDate=$startHour=$endHour= NULL;
		if(isset($_POST['trainerId'])) $trainerId= $_POST['trainerId'];
		else return;
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else return;
		if(isset($_POST['rangeOfDate'])) list($startDate, $endDate)= explode(" - ", $_POST['rangeOfDate']);
		else return;
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else return;
		if($_POST['weekDays']) $weekDays= explode(";", $_POST['weekDays']);
		else return;
		$courseId= isset($_POST['courseId'])?$_POST['courseId']:NULL;
		$lessons= array();
		$iDate= strtotime($startDate);
		$count= 0;
		while(true){
			foreach($weekDays as $day){
				if(date("D", $iDate)===$day){
					$lessons[$count]['startTime']=date("Y-m-d", $iDate)." ".$startHour;
					$lessons[$count]['endTime']= date("Y-m-d", $iDate)." ".$endHour;
					$count++;
					break;
				}
			}
			if(date("Y-m-d", $iDate)===$endDate) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		$courseModel= new Course();
		foreach($lessons as $lesson){
			$data= $courseModel->selectConflictLessons(NULL, NULL, $trainerId, $roomId, $lesson['startTime'], $lesson['endTime']);
			foreach($data as $row){
				echo "<tr>";
				foreach($row as $field){
					echo "<td>".htmlspecialchars($field)."</td>";
				}
				echo "</tr>";
			}
		}
	}


	public function loadConflictTrainees(){
		$weekDays=NULL;
		$courseId=$startDate=$endDate=$startHour=$endHour= NULL;
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;

		if(isset($_POST['rangeOfDate'])) list($startDate, $endDate)= explode(" - ", $_POST['rangeOfDate']);
		else return;
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else return;
		if($_POST['weekDays']) $weekDays= explode(";", $_POST['weekDays']);
		else return;
		$courseId= isset($_POST['courseId'])?$_POST['courseId']:NULL;
		$lessons= array();
		$iDate= strtotime($startDate);
		$count= 0;
		while(true){
			foreach($weekDays as $day){
				if(date("D", $iDate)===$day){
					$lessons[$count]['startTime']=date("Y-m-d", $iDate)." ".$startHour;
					$lessons[$count]['endTime']= date("Y-m-d", $iDate)." ".$endHour;
					$count++;
					break;
				}
			}
			if(date("Y-m-d", $iDate)===$endDate) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		$courseModel= new Course();
		foreach($lessons as $lesson){
			$data= $courseModel->getConflictOfTrainee($courseId,null,$lesson['startTime'], $lesson['endTime']);
			foreach($data as $row){
				echo "<tr>";
				foreach($row as $key=>$value){
					if($key=='traineeId' || $key=='startTime' || $key=='endTime' || $key=='state')
						echo "<td>".htmlspecialchars($value)."</td>";
				}
				echo "</tr>";
			}
		}
	}



	//HUNTER - Load conflict when change trainer
	public function loadConflictLessons2(){
		$trainerId=$courseId= NULL;
		if(isset($_POST['trainerId'])) $trainerId= $_POST['trainerId'];
		else return;
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;

		$courseModel= new Course();
		$course= $courseModel->getCourseById($courseId);
		if($course['state']=='closed'||$course['state']=='reopened') return;
		if(strtotime($course['endDate'])<strtotime(date("Y-m-d"))) return;
		$lessons= $courseModel->getDetailCourse($courseId);
		foreach($lessons as $lesson){
			$data= $courseModel->selectConflictLessons($courseId, NULL, $trainerId, $lesson['roomId'], $lesson['startTime'], $lesson['endTime']);
			foreach($data as $row){
				echo "<tr>";
				foreach($row as $field){
					echo "<td>".htmlspecialchars($field)."</td>";
				}
				echo "</tr>";
			}
		}
	}

	//HUNTER - Load conflict when update lesson by day of week
	public function loadConflictLessons3(){
		$result= array();
		$courseId=$trainerId=$dayOfWeek=$roomId=$rangeOfHour= NULL;
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;
		if(isset($_POST['trainerId'])) $trainerId= $_POST['trainerId'];
		else return;
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else return;
		if(isset($_POST['dayOfWeek'])) $dayOfWeek= $_POST['dayOfWeek'];
		else return;
		if(isset($_POST['oldDayOfWeek'])) $oldDayOfWeek= $_POST['oldDayOfWeek'];
		else return;
		if(isset($_POST['rangeOfHour'])) $rangeOfHour= $_POST['rangeOfHour'];
		else return;
		if(isset($_POST['oldRangeOfHour'])) $oldRangeOfHour= $_POST['oldRangeOfHour'];
		else return;

		$courseModel= new Course();
		$course= $courseModel->getCourseById($courseId);
		$oldLessons= $courseModel->selectRemainLessonByDay($courseId, $oldDayOfWeek, $oldRangeOfHour);
		$oldRoomId= $oldLessons[0]['roomId'];
		$numOfOldLessons= sizeof($oldLessons);
		if(!$numOfOldLessons){
			$result['error']= "All lessons were finished!";
			echo json_encode($result);
			return;
		}

		//Find out begin date to search new lesson
		$beginDate= NULL;
		$now24h= date("Y-m-d", strtotime("+24hours", strtotime(date("Y-m-d H:i:s"))));
		if(strtotime($now24h)>strtotime($course['endDate'])||$course['state']=="closed"){
			$result['error']= "This course was finished!";
			echo json_encode($result);
			return;
		}
		$beginDate= ($now24h<$course['startDate'])? $course['startDate']: $now24h;


		//Check if dayOfWeek and rangeOfHour was changed
		if($dayOfWeek===$oldDayOfWeek&&$rangeOfHour===$oldRangeOfHour&&$oldRoomId===$roomId) return;

		//Get startHour and endHour
		list($startHour, $endHour)= explode(" - ", $rangeOfHour);


		$result['conflictLessons']= "";
		$result['conflictTrainees']= "";
		//Create new array of lessons
		$newLessons= array();
		$iDate= strtotime($beginDate);
		$numOfNewLessons= 0;
		while(true){
			if(date("D", $iDate)===$dayOfWeek){
				$startTime= date("Y-m-d", $iDate)." ".$startHour;
				$endTime= date("Y-m-d", $iDate)." ".$endHour;
				$data= $courseModel->selectConflictLessons(NULL, $oldLessons[$numOfNewLessons]['id'], $trainerId, $roomId, $startTime, $endTime);
				$data2= $courseModel->selectConflictTrainees($oldLessons[$numOfNewLessons]['id'], $startTime, $endTime);
				if($data||$data2){
					foreach($data as $row){
						$result['conflictLessons'].= "<tr>";
						foreach($row as $field){
							$result['conflictLessons'].= "<td>".htmlspecialchars($field)."</td>";
						}
						$result['conflictLessons'].= "</tr>";
					}
					foreach($data2 as $row){
						$result['conflictTrainees'].= "<tr>";
						foreach($row as $field){
							$result['conflictTrainees'].= "<td>".htmlspecialchars($field)."</td>";
						}
						$result['conflictTrainees'].= "</tr>";
					}
				}else{
					$newLessons[$numOfNewLessons]['id']= $oldLessons[$numOfNewLessons]['id'];
					$newLessons[$numOfNewLessons]['courseId']= $courseId;
					$newLessons[$numOfNewLessons]['roomId']= $roomId;
					$newLessons[$numOfNewLessons]['dayOfWeek']= $dayOfWeek;
					$newLessons[$numOfNewLessons]['startTime']= $startTime;
					$newLessons[$numOfNewLessons]['endTime']= $endTime;
					$numOfNewLessons++;
				}
			}
			if($iDate>strtotime($course['endDate'])) break;
			if($numOfNewLessons>=$numOfOldLessons) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		//Check number of lessons
		if($numOfOldLessons>$numOfNewLessons){
			$result['error']= "Number of lessons is not enough!";
			echo json_encode($result);
			return;
		}

		$result['success']= "Suggestion can be accepted!";

		echo json_encode($result);
	}


	//HUNTER - Load conflict when update lesson one by one
	public function loadConflictLessons4(){
		$result= array();
		$lessonId= $date= $rangeOfHour= NULL;
		if(isset($_POST['lessonId'])) $lessonId= $_POST['lessonId'];
		else return;
		if(isset($_POST['date'])) $date= $_POST['date'];
		else return;
		if(isset($_POST['rangeOfHour'])) $rangeOfHour= $_POST['rangeOfHour'];
		else return;

		$courseModel= new Course();
		$course= $courseModel->selectCourseFromLesson($lessonId);
		$now24h= strtotime("+24hours", strtotime(date("Y-m-d H:i:s")));
		if(strtotime($date)<strtotime($course['startDate'])||strtotime($date)>strtotime($course['endDate'])){
			$result['error']= "Out of date range (from ".$course['startDate']." to ".$course['endDate'].")";
			echo json_encode($result);
			return;
		}
		if(strtotime($date)<$now24h){
			$result['error']= "Too late to change. Schedule must be changed after 24hours!";
			echo json_encode($result);
			return;
		}

		list($startHour, $endHour)= explode(" - ", $rangeOfHour);
		$startTime= $date." ".$startHour;
		$endTime= $date." ".$endHour;
		$lessonModel= new Lesson();
		$lesson= $lessonModel->selectLessonById($lessonId);
		$data= $courseModel->selectConflictLessons(NULL, $lessonId, $course['trainerId'], $lesson['roomId'], $startTime, $endTime);
		$data2= $courseModel->selectConflictTrainees($lessonId, $startTime, $endTime);
		if($data||$data2){
			$result['conflictLessons']="";
			foreach($data as $row){
				$result['conflictLessons'].="<tr>";
				foreach($row as $field){
					$result['conflictLessons'].="<td>$field</td>";
				}
				$result['conflictLessons'].="</tr>";
			}
			$result['conflictTrainees']="";
			foreach($data2 as $row){
				$result['conflictTrainees'].="<tr>";
				foreach($row as $field){
					$result['conflictTrainees'].="<td>$field</td>";
				}
				$result['conflictTrainees'].="</tr>";
			}
		}else{
			$result['success']="Suggestion can be accepted!";
		}

		echo json_encode($result);
	}

	public function changeLessonOneByOne(){
		$result= array();
		$lessonId= $date= $rangeOfHour= NULL;
		if(isset($_POST['lessonId'])) $lessonId= $_POST['lessonId'];
		else return;
		if(isset($_POST['date'])) $date= $_POST['date'];
		else return;
		if(isset($_POST['rangeOfHour'])) $rangeOfHour= $_POST['rangeOfHour'];
		else return;
		$notiId= ($_POST['notiId'])?$_POST['notiId']:NULL;

		$courseModel= new Course();
		$course= $courseModel->selectCourseFromLesson($lessonId);
		$now24h= strtotime("+24hours", strtotime(date("Y-m-d H:i:s")));
		if(strtotime($date)<strtotime($course['startDate'])||strtotime($date)>strtotime($course['endDate'])){
			$result['error']= "Out of date range (from ".$course['startDate']." to ".$course['endDate'].")";
			echo json_encode($result);
			return;
		}
		if(strtotime($date)<$now24h){
			$result['error']= "Too late to change. Schedule must be changed after 24hours!";
			echo json_encode($result);
			return;
		}

		list($startHour, $endHour)= explode(" - ", $rangeOfHour);
		$startTime= $date." ".$startHour;
		$endTime= $date." ".$endHour;
		$lessonModel= new Lesson();
		$lesson= $lessonModel->selectLessonById($lessonId);
		$data= $courseModel->selectConflictLessons(NULL, $lessonId, $course['trainerId'], $lesson['roomId'], $startTime, $endTime);
		$data2= $courseModel->selectConflictTrainees($lessonId, $startTime, $endTime);
		if($data||$data2){
			$result['error']= "Schedule conflict occurred!";
			echo json_encode($result);
			return;
		}else{
			$lesson['startTime']= $startTime;
			$lesson['endTime']= $endTime;
			$lesson['dayOfWeek']= date("D", strtotime($date));
			if($lessonModel->updateLesson($lesson)){
				$result['success']="Update lesson successfully!";
				$noticeCtrl= new NoticeController();

				//Send notification to inform that course was updated
				$noticeCtrl->sendNotification($course['id'], "updated course");

				//Approve suggest new course notifications
				if($notiId){
					$approve['newCourseId']= $course['id'];
					if($noticeCtrl->approveNotification($notiId, $approve))
						$result['success'].="\nSuggestion was approved!";
				}
			}
		}

		echo json_encode($result);
	}


	//HUNTER - Change lesson by day
	public function changeLessonByDay(){
		$result= array();
		$courseId=$trainerId=$dayOfWeek=$roomId=$rangeOfHour= NULL;
		$notiId= ($_POST['notiId'])?$_POST['notiId']:NULL;
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;
		if(isset($_POST['trainerId'])) $trainerId= $_POST['trainerId'];
		else return;
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else return;
		if(isset($_POST['dayOfWeek'])) $dayOfWeek= $_POST['dayOfWeek'];
		else return;
		if(isset($_POST['oldDayOfWeek'])) $oldDayOfWeek= $_POST['oldDayOfWeek'];
		else return;
		if(isset($_POST['rangeOfHour'])) $rangeOfHour= $_POST['rangeOfHour'];
		else return;
		if(isset($_POST['oldRangeOfHour'])) $oldRangeOfHour= $_POST['oldRangeOfHour'];
		else return;

		$courseModel= new Course();
		$course= $courseModel->getCourseById($courseId);
		$oldLessons= $courseModel->selectRemainLessonByDay($courseId, $oldDayOfWeek, $oldRangeOfHour);
		$oldRoomId= $oldLessons[0]['roomId'];
		$numOfOldLessons= sizeof($oldLessons);
		if(!$numOfOldLessons){
			$result['error']= "All lessons were finished!";
			echo json_encode($result);
			return;
		}

		//Find out begin date to search new lesson
		$beginDate= NULL;
		$now24h= date("Y-m-d", strtotime("+24hours", strtotime(date("Y-m-d H:i:s"))));
		if(strtotime($now24h)>strtotime($course['endDate'])||$course['state']=="closed"){
			$result['error']= "This course was finished!";
			echo json_encode($result);
			return;
		}
		$beginDate= ($now24h<$course['startDate'])? $course['startDate']: $now24h;


		//Check if dayOfWeek and rangeOfHour was changed
		if($dayOfWeek===$oldDayOfWeek&&$rangeOfHour===$oldRangeOfHour&&$oldRoomId===$roomId){
			$result['error']= "Didn't change any thing!";
			echo json_encode($result);
			return;
		}

		//Get startHour and endHour
		list($startHour, $endHour)= explode(" - ", $rangeOfHour);

		//Create new array of lessons
		$newLessons= array();
		$iDate= strtotime($beginDate);
		$numOfNewLessons= 0;
		while(true){
			if(date("D", $iDate)===$dayOfWeek){
				$startTime= date("Y-m-d", $iDate)." ".$startHour;
				$endTime= date("Y-m-d", $iDate)." ".$endHour;
				$data= $courseModel->selectConflictLessons(NULL, $oldLessons[$numOfNewLessons]['id'], $trainerId, $roomId, $startTime, $endTime);
				$data2= $courseModel->selectConflictTrainees($oldLessons[$numOfNewLessons]['id'], $startTime, $endTime);
				if($data||$data2){
				}else{
					$newLessons[$numOfNewLessons]['id']= $oldLessons[$numOfNewLessons]['id'];
					$newLessons[$numOfNewLessons]['courseId']= $courseId;
					$newLessons[$numOfNewLessons]['roomId']= $roomId;
					$newLessons[$numOfNewLessons]['dayOfWeek']= $dayOfWeek;
					$newLessons[$numOfNewLessons]['startTime']= $startTime;
					$newLessons[$numOfNewLessons]['endTime']= $endTime;
					$numOfNewLessons++;
				}
			}
			if($iDate>strtotime($course['endDate'])) break;
			if($numOfNewLessons>=$numOfOldLessons) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		//Check number of lessons
		if($numOfOldLessons>$numOfNewLessons){
			$result['error']= "Schedule conflict occurred!";
			echo json_encode($result);
			return;
		}

		$lessonModel= new Lesson();
		foreach($newLessons as $newLesson){
			if(!($lessonModel->updateLesson($newLesson))){
				$result['error']= "Update lesson failed!";
				echo json_encode($result);
				return;
			}
		}

		$result['success']= "Update lessons successfully!";

		$noticeCtrl= new NoticeController();
		//Send notification to inform that course was updated
		$noticeCtrl->sendNotification($courseId, "updated course");

		//Approve suggest new course notifications
		if($notiId){
			$approve['newCourseId']= $course['id'];
			if($noticeCtrl->approveNotification($notiId, $approve))
				$result['success'].="\nSuggestion was approved!";
		}
		echo json_encode($result);
	}


	//HUNTER
	function loadLessonsByDay(){
		$courseId=$dayOfWeek= NULL;
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;
		$dayOfWeek= ($_POST['dayOfWeek'])?$_POST['dayOfWeek']:NULL;
		$rangeOfHour= ($_POST['rangeOfHour'])?$_POST['rangeOfHour']:NULL;

		$courseModel= new Course();
		$lessons= $courseModel->selectLessonByDay($courseId, $dayOfWeek, $rangeOfHour);
		$now= strtotime(date("Y-m-d H:i:s"));
		foreach($lessons as $lesson){
			$startTime= strtotime($lesson['startTime']);
			$endTime= strtotime($lesson['endTime']);
			$date= explode(" ", $lesson['startTime'])[0];
			$hour= explode(" ", $lesson['startTime'])[1]." - ".explode(" ", $lesson['endTime'])[1];
			if($now>$endTime){
				echo "<tr style='background-color: lightgray;'>";
			}elseif($now>=$startTime&&$now<=$endTime){
				echo "<tr style='background-color: lightyellow;'>";
			}else{
				echo "<tr>";
			}
			echo "<td>".$lesson['id']."</td>";
			echo "<td>".$date."</td>";
			echo "<td>".$hour."</td>";
			echo "<td>".$lesson['dayOfWeek']."</td>";
			if($now>$endTime){
				echo "<td>Done</td>";
			}elseif($now>=$startTime&&$now<=$endTime){
				echo "<td>Learning</td>";
			}else{
				echo "<td></td>";
			}
			echo "</tr>";
		}
	}




	//HUNTER
	public function addCourse(){
		$result= NULL;
		//Get POST params
		$notiId= isset($_POST['notiId'])?$_POST['notiId']:NULL;
		$course= [];
		$course['majorId']= isset($_POST['majorId'])?$_POST['majorId']:NULL;
		$course['trainerId']= isset($_POST['trainerId'])?$_POST['trainerId']:NULL;
		$course['state']='approved';
		if(isset($_POST['rangeOfDate'])) list($course['startDate'], $course['endDate'])= explode(" - ", $_POST['rangeOfDate']);
		else $course['startDate']= $course['endDate']= NULL;

		$roomId=$startHour=$endHour= NULL;
		$weekDays= [];
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else{
			$result['error']= "Required field is empty!";
			echo json_encode($result);
			return;
		}
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else{
			$result['error']= "Required field is empty!";
			echo json_encode($result);
			return;
		}
		if(isset($_POST['weekDays'])) $weekDays= explode(";", $_POST['weekDays']);
		else{
			$result['error']= "Required field is empty!";
			echo json_encode($result);
			return;
		}
		foreach($course as $field){
			if($field==NULL){
				$result['error']= "Required field is empty!";
				echo json_encode($result);
				return;
			}
		}

		//Handle code
		$lessons= array();
		$numOfLessons= 0;
		$iDate= strtotime($course['startDate']);
		while(true){
			foreach($weekDays as $day){
				if(date("D", $iDate)===$day){
					$lessons[$numOfLessons]['startTime']=date("Y-m-d", $iDate)." ".$startHour;
					$lessons[$numOfLessons]['endTime']= date("Y-m-d", $iDate)." ".$endHour;
					$lessons[$numOfLessons]['dayOfWeek']= $day;
					$numOfLessons++;
					break;
				}
			}
			if(date("Y-m-d", $iDate)===$course['endDate']) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		for($i= 0; $i<sizeof($lessons); $i++){
			$lessons[$i]['roomId']= $roomId;
		}
		$course['numOfLessons']= $numOfLessons;

		//Check conflict
		$courseModel= new Course();
		foreach($lessons as $lesson){
			if(sizeof($courseModel->selectConflictLessons(NULL, NULL, $course['trainerId'], $lesson['roomId'], $lesson['startTime'], $lesson['endTime']))){
				$result['error']= "Schedule conflict occurred!";
				echo json_encode($result);
				return;
			}
		}

		//Add course
		$lessonModel= new Lesson();
		if($course['id']=$courseModel->insertCourse($course)){
			for($i= 0; $i<sizeof($lessons); $i++){
				$lessons[$i]['courseId']= $course['id'];
				if(!$lessonModel->insertLesson($lessons[$i])){
					$result['error']= "Add new lesson failed !";
					echo json_encode($result);
					return;
				}
			}
			$result['success']= "Add new course succesfully!";

			//Approve suggest new course notifications
			if($notiId){
				$approve['newCourseId']= $course['id'];
				$noticeCtrl= new NoticeController();
				if($noticeCtrl->approveNotification($notiId, $approve))
					$result['success'].="\nSuggestion was approved!";
			}
		}
		else $result['error']= "Add new course failed!";

		echo json_encode($result);
	}


	//HUNTER
	public function changeTrainer(){
		$courseModel= new Course();
		$noticeController= new NoticeController();
		$result= [];
		$courseId= $trainerId= NULL;
		if(isset($_POST['courseId'])) $courseId= $_POST["courseId"];
		else{
			$result["error"]= "Unknow course ID!";
			echo json_encode($result);
			return;
		}
		if(isset($_POST['trainerId'])) $trainerId= $_POST["trainerId"];
		else{
			$result["error"]= "Unknow new trainer ID!";
			echo json_encode($result);
			return;
		}
		$course= $courseModel->getCourseById($courseId);
		if($course['state']=="closed"||$course['state']=="reopened"||strtotime($course['endDate'])<strtotime(date("Y-m-d"))){
			$result["error"]= "This course was closed!";
			echo json_encode($result);
			return;
		}

		if($course['trainerId']==$trainerId){
			$result['error']= "Trainer wasn't changed!";
			echo json_encode($result);
			return;
		}

		$lessons= $courseModel->getDetailCourse($courseId);
		foreach($lessons as $lesson){
			$data= $courseModel->selectConflictLessons($courseId, NULL, $trainerId, $lesson['roomId'], $lesson['startTime'], $lesson['endTime']);
			if(sizeof($data)){
				$result['error']= "Schedule conflict occurred!";
				echo json_encode($result);
				return;
			}
		}


		$newCourse['id']= $courseId;
		$newCourse['trainerId']= $trainerId;
		if($courseModel->updateCourse($newCourse)){
			$noticeController->announceChangedTrainer($courseId, $course['trainerId']);
			$result['success']= "Changed trainer succesfully!";
		}else{
			$result['error']= "Changed trainer failed!";
		}
		echo json_encode($result);
	}


	//HUNTER
	public function updateLessonByDayOfWeek(){
		$courseId=$dayOfWeek=$roomId=$rangeOfHour=NULL;
		//Get course ID from PORT
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else{
			$result["error"]= "Unknow course ID!";
			echo json_encode($result);
			return;
		}
		$courseModel= new Course();
		$course= $courseModel->getCourseById($courseId);
		//Check course if closed
		if($course['state']=="closed"){
			$result["error"]= "This course was closed!";
			echo json_encode($result);
			return;
		}
		$lessons= $courseModel->getDetailCourse($courseId);
	}


	public function showCourseList(){
			$courseModel= new Course();
			$data= $courseModel->getCourseList();
			if($data){
				foreach($data as $row){
					echo "<tr>";
					foreach ($row as $value) {
						echo "<td>".$value."</td>";
					}
					echo "<td id=".$row['id']."><center><input type='checkbox' name='course' ></center></td>";
					echo "</tr>";
				}
			}
	}
	public function showDefaultCourse(){
			$courseModel= new Course();
			$numOfTrainee = $courseModel->getNumberOfTrainee();
			$data= $courseModel->getDefaultCourse($numOfTrainee);
			if($data){
				foreach($data as $row){
					echo "<tr>";
					foreach ($row as $value) {
						echo "<td>".$value."</td>";
					}
					echo "<td id=".$row['id']."><center><input type='checkbox' name='defaultCourse' ></center></td>";
					echo "</tr>";
				}
			}
	}

	public function setDefaultCourse(){
		$i=0;
		$result=NULL;
		$st=($_POST['id']);
		$args= explode(" ", $st);
		$courseModel= new Course();
		$noticeController= new NoticeController();
		foreach ($args as $key => $value) {
			if($courseModel->setDefaultCourse($value)){
				$noticeController->sendNotification($value, 'opened course');
				$result['success']=true;
			}else
				$result['fail']=true;
		}
		 echo json_encode($result	);
	}


	public function removeDefaultCourse(){
		$i=0;
		$result=NULL;
		$string=($_POST['id']);
		if($string==null){
			$result['fail']=true;
		} else{
			$args= explode(" ", $string);
			$courseModel= new Course();
			foreach ($args as $key => $value) {
				if($courseModel->removeDefaultCourse($value)){
					$result['success']=true;
				}else{
					$result['fail']=true;
				}
			}
		}
		echo json_encode($result);

	}




	public function postDefaultCourse()
	{
		$data=[];
		$course= new Course();
		if($course->setDefaultCourse($_POST['id'])) $data["success"]= "Set default this course success !";
		else $data["error"]= "Set default this course failed !";
		echo json_encode($data);
	}

	public function openCourse(){
		$result=NULL;
		$courseModel= new Course();
		$noticeController= new NoticeController();
		$courseId = (!empty($_POST['courseId'])) ? $_POST['courseId'] : false;
		if($courseId){
			$course= $courseModel->getCourseById($courseId);
			if($courseModel->updateStateCourse($courseId, 'opened')){
				$rss = $courseModel->learningTrainee($courseId);
				if($rss){
					$noticeController->sendNotification($courseId, 'opened course');
					$result['success'] = true;
				}else {$result['error'] = $rss;}
			}else {$result['error'] = true;}
		}else {$result['error'] = true;}
	echo json_encode($result);
 	}

	public function closeCourse(){
	 	//$result=NULL;
	 	$courseModel= new Course();
		//$notify= new NoticeController();
	 	$courseId = (!empty($_POST['courseId'])) ? $_POST['courseId'] : false;
	 	if($courseId){
			$course= $courseModel->getCourseById($courseId);
	 			$rs = $courseModel->updateStateCourse($courseId, 'closed');
				$status = $courseModel->checkCourseCondition($courseId);
				if($status){
					$data['success'] = true;
				}
				// $count=0;
				// foreach($status as $row){
				// 	if($row['status']=='passed'|| $row['status']=='failed') $count++;
				// }
				// if ($course['state']=='opened') {
				// 	if($count == count($status)){
				// 		if($rs){
			 //
			 // 			}else {$data['error'] = $rs;}
				// }//else{$data['error'] = true;}
			else{
				$data['fail']= true;
			}

	 	}else{
	 		$data['error'] = true;
	 	}
	 	echo json_encode($data);
	}


 	public function reopenCourse(){
		$result= NULL;
		$noticeController= new NoticeController();
		//Get POST params
		$course= [];
		$oldId= isset($_POST['courseId'])?$_POST['courseId']:NULL;
		$course['majorId']= isset($_POST['majorId'])?$_POST['majorId']:NULL;
		$course['trainerId']= isset($_POST['trainerId'])?$_POST['trainerId']:NULL;
		$course['state']='opened';
		if(isset($_POST['rangeOfDate'])) list($course['startDate'], $course['endDate'])= explode(" - ", $_POST['rangeOfDate']);
		else $course['startDate']= $course['endDate']= NULL;
		$roomId=$startHour=$endHour= NULL;
		$weekDays= [];
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else{
			$result['error']= "Required field is empty!";
			echo json_encode($result);
			return;
		}
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else{
			$result['error']= "Required field is empty!";
			echo json_encode($result);
			return;
		}
		if(isset($_POST['weekDays'])) $weekDays= explode(";", $_POST['weekDays']);
		else{
			$result['error']= "Required field is empty!";
			echo json_encode($result);
			return;
		}
		foreach($course as $field){
			if($field==NULL){
				$result['error']= "Required field is empty!";
				echo json_encode($result);
				return;
			}
		}

		//Handle code
		$lessons= array();
		$numOfLessons= 0;
		$iDate= strtotime($course['startDate']);
		while(true){
			foreach($weekDays as $day){
				if(date("D", $iDate)===$day){
					$lessons[$numOfLessons]['startTime']=date("Y-m-d", $iDate)." ".$startHour;
					$lessons[$numOfLessons]['endTime']= date("Y-m-d", $iDate)." ".$endHour;
					$lessons[$numOfLessons]['dayOfWeek']= $day;
					$numOfLessons++;
					break;
				}
			}
			if(date("Y-m-d", $iDate)===$course['endDate']) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		for($i= 0; $i<sizeof($lessons); $i++){
			$lessons[$i]['roomId']= $roomId;
		}
		$course['numOfLessons']= $numOfLessons;

		if($numOfLessons==0){
			$result['error']="You choose the wrong weekday!";
			echo json_encode($result);
			return;
		}

		//Check conflict
		$courseModel= new Course();
		foreach($lessons as $lesson){
			if(sizeof($courseModel->selectConflictLessons(NULL,NULL, $course['trainerId'], $lesson['roomId'], $lesson['startTime'], $lesson['endTime']))){
				$result['error']= "Conflict room or trainer's schedule !";
				echo json_encode($result);
				return;
			}
			if(sizeof($courseModel->getConflictOfTrainee($oldId,NULL,$lesson['startTime'],$lesson['endTime']))){
				$result['error']= "Conflict trainee's schedule !";
				echo json_encode($result);
				return;

			}
		}

		//Reopen course
		$lessonModel= new Lesson();
		if($newId=$courseModel->insertCourse($course)){
			if($courseModel->insertLearning($newId,$oldId)){
				for($i= 0; $i<sizeof($lessons); $i++){
					$lessons[$i]['courseId']= $newId;
					if(!$lessonModel->insertLesson($lessons[$i])){
						$result['error']= "Can not reopen!";
						echo json_encode($result);
						return;
					}
				}
				$noticeController->sendNotification($newId, 'reopened course');
				$result['success']= "Reopen course succesfully!";
				echo json_encode($result);
				return;

			} else{
				$result['error']="Can not reopen!";
			}
		} else{
			$result['error']="Can not reopen!";
		}

		echo json_encode($result);
	}


	public function getConflictOfTrainee(){
		$courseModel= new Course();
		$result= NULL;
		$args= [];
		$args['courseId']= ($_POST['id'])?$_POST['id']:NULL;
		if($_POST['courseTime']) list($args['startTime'], $args['endTime'])= explode(" - ", $_POST['courseTime']);
		else $args['startTime']=$args['endTime']= NULL;
		$data=$courseModel->getConflictOfTrainee($args['courseId'],$args['startTime'],$args['endTime']);
		if($data){
			foreach($data as $row){
				echo "<tr>";
				foreach($row as $key =>$value){
					if($key=='traineeId' || $key=='startTime' || $key=='endTime')
					echo "<td>".htmlspecialchars($value)."</td>";
				}
				echo "</tr>";
			}
		} else{}
	}
	public function getConflictOfTrainer(){
		$courseModel= new Course();
		$result= NULL;
		$args= [];
		$args['trainerId']= ($_POST['trainerId'])?$_POST['trainerId']:NULL;
		if($_POST['courseTime']) list($args['startTime'], $args['endTime'])= explode(" - ", $_POST['courseTime']);
		else $args['startTime']=$args['endTime']= NULL;
		$data=$courseModel->getConflictOfTrainer($args['trainerId'],$args['startTime'],$args['endTime']);
		if($data){
			foreach($data as $row){
				echo "<tr>";
				foreach($row as $key =>$value){
					echo "<td>".htmlspecialchars($value)."</td>";
				}
				echo "</tr>";
			}
		} else{}
	}
	public function getConflictOfRoom(){
		$courseModel= new Course();
		$result= NULL;
		$args= [];
		$args['roomId']= ($_POST['roomId'])?$_POST['roomId']:NULL;
		if($_POST['courseTime']) list($args['startTime'], $args['endTime'])= explode(" - ", $_POST['courseTime']);
		else $args['startTime']=$args['endTime']= NULL;
		$data=$courseModel->getConflictOfRoom($args['roomId'],$args['startTime'],$args['endTime']);
		if($data){
			foreach($data as $row){
				echo "<tr>";
				foreach($row as $key =>$value){
					echo "<td>".htmlspecialchars($value)."</td>";
				}
				echo "</tr>";
			}
		} else{}
	}
 	public function postDelete()
	{
		$id = (empty($_POST['id'])) ? false : filter_var($_POST['id'], FILTER_SANITIZE_STRING);
		if($id){
			$course = new Course();
			$rs = $course->deleteCourse($id);
			if($rs){
				$_SESSION['success'] = "You deleted successfully";
				$this->redirect('ctr=course&act=postSchedule');
			}else{
				$this->redirect('ctr=error&act=error404');
			}
		}else{
			$this->redirect('ctr=error&act=error404');
		}

	}



}
