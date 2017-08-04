<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Notification.php';

class TrainerController extends Controller{



	public function getInforById()
	{
		if(!isset($_SESSION['trainer_infor']))
		{
			$this->redirect("ctr=auth&act=getLogin");
		}
		$this->render('trainer/updateInfo');

	}

	public function postUpdateInfor()
	{

			$arg = array(
					'id' => $_POST['id'],
					'autho' => $_SESSION['trainer_infor']['autho'],
					'fullName' => trim($_POST['fullName']),
					'gender' => trim($_POST['gender']),
					'dateOfBirth' => trim($_POST['dateOfBirth']),
					'address' => trim($_POST['address']),
					'phoneNumber' => trim($_POST['phoneNumber']),
					'email' => trim($_POST['email']),
					'majorId' => trim($_POST['majorId']),
					'experience' => trim($_POST['experience']),
				);
				if(!empty($_FILES['profilePic']['name'])) {
					$profilePic = $this->transferFile($_FILES['profilePic']);
					$arg['profilePic'] = $profilePic ? $profilePic : false;
				}

				$accountModel = new Account();
				$data= $accountModel -> updateUser($arg);

				if($data){
						$_SESSION['trainer_infor']= $data;
					//$rs['success'] = 'You have updated successfully !';
					$this->redirect('ctr=trainer&act=getInforById');
				}else{
					//$rs['error']= 'Failed';
					$this->redirect('ctr=error&act=error404');
				}

				}

	public function postUpdatePassword(){
		$rs=[];
		if((md5($_POST['currentPass'])==$_SESSION['trainer_infor']['password'])){
			// $args = array(
			// 		'id' => $_SESSION['trainee_infor']['id'],
			// 		'password' => md5($_POST['newPassword'])
			// 	);

			$id= $_SESSION['trainer_infor']['id'];
			$password = md5($_POST['newPassword']);

			$account = new Account();
			$data = $account -> updatePassword($id, $password);
			if($data) {
				$rs['success'] = 'You have updated successfully !';
			}else{
				$rs['error']= 'Failed!';
			}
			echo json_encode($rs);

		}else{
			$rs['error']= 'Please enter your correct current password !';
			echo json_encode($rs);
		}




	}


	public function transferFile($file) {
			$status = false;
			$target_dir = $_SERVER['DOCUMENT_ROOT']."/upload/Trainer/";
			$basename = time() . basename($file["name"]);
			$target_file = $target_dir . $basename;
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			// Check if file already exists
			if (file_exists($target_file)) {
			    $uploadOk = 0;
			}
			// Check file size
			if ($file["size"] > 500000) {
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			// if everything is ok, try to upload file
			} else {
				// var_dump($file['tmp_name']);exit(1);
			    if (move_uploaded_file($file["tmp_name"], $target_file)) {
			    	$status = true;
			    }
			}
			if($status == true){
				return $basename;
			}else{
				return false;
			}
		}
	public function loadConflictLessons(){
		$weekDays=NULL;
		$trainerId=$roomId=$startDate=$endDate=$startHour=$endHour= NULL;
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else return;
		if(isset($_POST['rangeOfDate'])) $date=$_POST['rangeOfDate'];
		else return;
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else return;
		$startTime=$date." - ".$startHour;
		$endTime=$date." - ".$endHour;
		if(isset($_POST['courseId'])) $courseId= isset($_POST['courseId'])?$_POST['courseId']:NULL;
		else return;
		if(isset($_POST['lessonId'])) $lessonId= isset($_POST['lessonId'])?$_POST['lessonId']:NULL;
		$courseModel= new Course();
		$data= $courseModel->selectConflictLessons(NULL, NULL, $_SESSION['trainer_infor']['id'], $roomId, $startTime,$endTime);
		foreach($data as $row){
			echo "<tr>";
			foreach($row as $field){
				echo "<td>".htmlspecialchars($field)."</td>";
			}
			echo "</tr>";
		}
	}
	public function loadConflictTrainees(){
		$weekDays=NULL;
		$courseId=$startDate=$endDate=$startHour=$endHour= NULL;
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;
		if(isset($_POST['rangeOfDate']))  $date=$_POST['rangeOfDate'];
		else return;
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else return;
		if(isset($_POST['courseId'])) $courseId= isset($_POST['courseId'])?$_POST['courseId']:NULL;
		else return;
		if(isset($_POST['lessonId'])) $lessonId= isset($_POST['lessonId'])?$_POST['lessonId']:NULL;
		else return;
		$startTime=$date." - ".$startHour;
		$endTime=$date." - ".$endHour;
		$courseModel= new Course();
		$data= $courseModel->getConflictOfTrainee($courseId,$lessonId,$startTime, $endTime);
		foreach($data as $row){
			echo "<tr>";
			foreach($row as $key=>$value){
				if($key=='traineeId' || $key=='startTime' || $key=='endTime' || $key=='state')
					echo "<td>".htmlspecialchars($value)."</td>";
			}
			echo "</tr>";
		}
	}
	public function sendBusyRequest(){
		$rs=[];
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;
		if(isset($_POST['content'])) $content= $_POST['content'];
		else return;
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else return;
		if(isset($_POST['oldDate'])) $oldDate= $_POST['oldDate'];
		else return;
		if(isset($_POST['oldHour'])) $oldHour= $_POST['oldHour'];
		else return;

		list($oldStartHour, $oldEndHour)= explode(" - ", $_POST['oldHour']);
		list($lessonId, $date, $startHour, $endHour)= explode(";", $_POST['content']);

		if($oldDate==$date && $startHour==$oldStartHour){
			$result['error']="Nothing changed ! Please choose again !";
			echo json_encode($result);
			return;
		}

		$startTime=$date." - ".$startHour;
		$endTime=$date." - ".$endHour;

		$courseModel= new Course();
		$detailCourse=$courseModel->getCourseById($courseId);

		if($date>$detailCourse['endDate'] || $date<$detailCourse['startDate']){
			$result['error']="The date you chose is out of the course time ! Choose again !";
			echo json_encode($result);
			return;
		}
		if(sizeof($courseModel->selectConflictLessons(NULL, NULL, $_SESSION['trainer_infor']['id'], $roomId, $startTime,$endTime))){
			$rs['error']="Conflict lessons !";
			echo json_encode($rs);
			return;
		}
		if(sizeof($courseModel->getConflictOfTrainee($courseId,$lessonId,$startTime, $endTime))){
			$rs['error']="Conflict trainee's schedule !";
			echo json_encode($rs);
			return;
		}
		$args=[];
		$args['sender']=$_SESSION['trainer_infor']['id'];
		$args['receiver']=NULL;
		$args['type']="requested schedule";
	    $args['content']=$content;

	    $notification= new Notification();

		if($notification->insertNewNotification($args)){
			$rs['success']= "Send succesfully !";
		}else{
			$rs['error']= "Error !";
		}
		echo json_encode($rs);

	}
	public function getOldWeekDays(){
		if(isset($_POST['changeTimeCourseId'])){
			$courseId= $_POST['changeTimeCourseId'];
			$courseModel= new Course();
			$data= $courseModel->selectDaysInWeek($courseId);
			// var_dump($data);
			if($data){
				foreach($data as $row){
					$text= htmlspecialchars($row['dayOfWeek']." (".$row["startHour"]." - ".$row["endHour"].")");
					echo "<option value='".$row["dayOfWeek"].";".$row["startHour"]." - ".$row["endHour"]."'>".$text."</option>";
				}
			}
		}else{
		}
	}
	public function loadRoomForDay(){
		if(isset($_POST['courseId'])){
			$courseId= $_POST['courseId'];
		} else return;
		if(isset($_POST['day'])){
			$day= $_POST['day'];
		} else return;
		list($dayOfWeek, $rangeOfHour)= explode(";", $day);
		$courseModel=new Course();
		$rs=$courseModel->selectLessonByDay($courseId, $dayOfWeek, $rangeOfHour);
		echo json_encode($rs[0]);
	}

	public function sendChangeDayRequest(){
		$rs=[];
		if(isset($_POST['content'])) $content= $_POST['content'];
		else return;
		if(isset($_POST['rangeOfDate'])) $startDate= $_POST['rangeOfDate'];
		else return;
		if(isset($_POST['newWeekDay'])) $newWeekDay= explode(";", $_POST['newWeekDay']);
		else return;
		if(isset($_POST['oldRoomId'])) $oldRoomId= $_POST['oldRoomId'];
		else return;
		list($courseId, $roomId, $oldDay, $oldStartHour,$oldEndHour, $newDay, $startHour, $endHour)= explode(";", $_POST['content']);


		if($oldDay==$newDay && $oldStartHour==$startHour && $oldRoomId==$roomId){
			$result['error']="Nothing changed ! Please choose again !";
			echo json_encode($result);
			return;
		}

		$courseModel= new Course();
		$detailCourse=$courseModel->getCourseById($courseId);
		$endDate=$detailCourse['endDate'];
		$lessons= array();
		$numOfLessons= 0;
		$iDate= strtotime($startDate);
		while(true){
			foreach($newWeekDay as $day){
				if(date("D", $iDate)===$day){
					$lessons[$numOfLessons]['startTime']=date("Y-m-d", $iDate)." ".$startHour;
					$lessons[$numOfLessons]['endTime']= date("Y-m-d", $iDate)." ".$endHour;
					$lessons[$numOfLessons]['dayOfWeek']= $day;
					$numOfLessons++;
					break;
				}
			}
			if(date("Y-m-d", $iDate)===$endDate) break;
			$iDate= strtotime("+1 day", $iDate);
		}
		for($i= 0; $i<sizeof($lessons); $i++){
			$lessons[$i]['roomId']= $roomId;

		}


		//Check conflict
		foreach($lessons as $lesson){

			if(sizeof($courseModel->selectConflictLessons(NULL,NULL, $_SESSION['trainer_infor']['id'], $lesson['roomId'], $lesson['startTime'], $lesson['endTime']))){
				$result['error']= "Conflict room or trainer's schedule !";
				echo json_encode($result);
				return;
			}
			if(sizeof($courseModel->getConflictOfTrainee($courseId,"1",$lesson['startTime'],$lesson['endTime']))){
				$result['error']= "Conflict trainee's schedule !";
				echo json_encode($result);
				return;

			}
		}
		$args=[];
		$args['sender']=$_SESSION['trainer_infor']['id'];
		$args['receiver']=NULL;
		$args['type']="modified schedule";
	    $args['content']=$content;

	    $notification= new Notification();

		if($notification->insertNewNotification($args)){
			$rs['success']= "Send succesfully !";
		}else{
			$rs['error']= "Error !";
		}
		echo json_encode($rs);
	}
	public function getConflictTrainees()
	{
		if(isset($_POST['courseId'])) $courseId= $_POST['courseId'];
		else return;

		if(isset($_POST['rangeOfDate'])) $startDate= $_POST['rangeOfDate'];
		else return;
		//var_dump($rangeOfDate);
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else return;
		if($_POST['newWeekDay']) $newWeekDay= explode(";", $_POST['newWeekDay']);
		else return;
		$courseModel= new Course();
		$detailCourse=$courseModel->getCourseById($courseId);
		$endDate=$detailCourse['endDate'];
		$lessons= array();
		$iDate= strtotime($startDate);

		$count= 0;
		while(true){
			foreach($newWeekDay as $day){
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
		foreach($lessons as $lesson){
			$data= $courseModel->getConflictOfTrainee($courseId,"1",$lesson['startTime'], $lesson['endTime']);
			if($data){
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
	}
	public function getConflictLessons(){
		$weekDays=NULL;
		$trainerId=$roomId=$startDate=$endDate=$startHour=$endHour= NULL;
		$courseId= isset($_POST['courseId'])?$_POST['courseId']:NULL;
		$trainerId= $_SESSION['trainer_infor']['id'];
		if(isset($_POST['roomId'])) $roomId= $_POST['roomId'];
		else return;
		if(isset($_POST['rangeOfDate'])) $startDate=$_POST['rangeOfDate'];
		else return;
		if(isset($_POST['rangeOfHour'])) list($startHour, $endHour)= explode(" - ", $_POST['rangeOfHour']);
		else return;
		if($_POST['newWeekDay']) $newWeekDay= explode(";", $_POST['newWeekDay']);
		else return;
		$courseModel=new Course();
		$detailCourse=$courseModel->getCourseById($courseId);
		$endDate=$detailCourse['endDate'];
		$lessons= array();
		$iDate= strtotime($startDate);
		$count= 0;
		while(true){
			foreach($newWeekDay as $day){
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
			if($data){
				foreach($data as $row){
					echo "<tr>";
					foreach($row as $field){
						echo "<td>".htmlspecialchars($field)."</td>";
					}
					echo "</tr>";
				}
			}
		}
	}
}	
