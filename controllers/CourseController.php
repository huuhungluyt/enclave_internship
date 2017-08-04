
<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Learning.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Notification.php';

class CourseController extends Controller{
	public function getTraineeList () {
		$trainee = new Trainee();
		$course = new Course();
		$data = [];
		$data['courseId'] = $_GET['id'];
		$courseItem = $course->getCourseById($_GET['id']);
		$data['state'] = $courseItem['state'];
		$data['trainee'] = $trainee->getTraineeListByCourseId($_GET['id']);
		$this->render('trainer/listOfTrainees',$data);
	}


	public function postUpdateStatusTrainee(){
		if(isset($_POST['updateStatus'])){
			// dump($_POST);
			$learning = new Learning();
			$course = new Course();
			$id= $_POST['id'] ? $_POST['id'] : false;
			$status = $_POST['status'] ? $_POST['status'] : false;
			$courseId = $_POST['courseId'] ? $_POST['courseId'] : false;
			$courseItem = $course->getCourseById($courseId);
			
			if($id == false && $status == false && $courseId == false && $courseItem['state'] != 'opened'){
				$this->redirect('ctr=error&act=error404');
			}else{
				$args = array_combine($id, $status);
			}

			foreach ($args as $key => $arg) {
				if(!$learning->updateStatus($key,$courseId,$arg)) {
					$this->redirect('ctr=error&act=error404');
				}
			}
			$_SESSION['updateTraieeSuccess'] = "You updated successfully the status of the trainees in this course";
			$this->redirect('ctr=course&act=getTraineeList&id='. $courseId);
		}else{
			$this->redirect('ctr=error&act=error404');
		}
	}

	public function postSchedule () {
		$course = new Course();
		$data = [];
		$data['course'] = $course->getSchedule();
		$this->render('courseManagement',$data);
	}

	public function getAvailableCourse() {
		$course=new Course();
		$trainee_id = $_SESSION['trainee_infor']['id'];
		$data['course'] = $course->getAvailableCourse($trainee_id);
		$this->render('trainee/registerCourse',$data);
	}

	public function registerCourse() {
		$result=NULL;
		$traineeId = $_SESSION['trainee_infor']['id'];
		$courseId = !empty($_POST['courseId']) ? $_POST['courseId'] : false;

		//check conflict
		$courseModel = new Course();
		$lessons=$courseModel->getDetailCourse($courseId);

		foreach ($lessons as $lesson) {
			// dump($courseModel->checkConflictCourse($traineeId,$lesson['startTime'], $lesson['endTime']));
			if($courseModel->checkConflictCourse($traineeId,$lesson['startTime'], $lesson['endTime'])){
				$result['error']= "Conflict with already schedule !";
				echo json_encode($result);
				return;
			}
		}
		//register course
		$courseModel->registerCourse($traineeId, $courseId);
		$result['success']= "Register new course succesfully !";
		echo json_encode($result);
	}


	public function getDetailScheduleByCourseId(){
		$course = new Course();
		$data = [];
		$data['course'] = $course->getDetailScheduleByCourseId($_GET['id']);
		echo json_encode($data['course']);
	}


	public function getRegisteredCourse(){
		if(!isset($_SESSION['trainee_infor']))
		{
			$this->redirect("ctr=auth&act=getLogin");
		} else{
			$traineeId=$_SESSION['trainee_infor']['id'];
			$course=new Course();
			$data=[];
			$data['registered']=$course->getRegisteredCourse($traineeId);
			$data['suggest']=$course->getSuggestNewCourse($traineeId);
			$this->render('trainee/getRegisteredCourse',$data);
		}
	}
	
	public function postDeleteRegisteredCourse()
	{
		$id = (empty($_POST['id'])) ? false : filter_var($_POST['id'], FILTER_SANITIZE_STRING);
		if($id){
			$learning=new Learning();
			$rs = $learning->deleteLearning($id);
			if($rs){
				$_SESSION['success'] = "You have deleted successfully";
				$this->redirect('ctr=course&act=getRegisteredCourse');
			}else{
				$this->redirect('ctr=error&act=error404');
			}
		}else{
			$this->redirect('ctr=error&act=error404');
		}

	}

	// public function getSuggestNewCourse(){
		
	// 		$traineeId=$_SESSION['trainee_infor']['id'];
	// 		$course=new Course();
	// 		$data=[];
	// 		$this->render('trainee/getRegisteredCourse',$data);
		
	// }


	public function getDetailCourse(){
		$id= $_POST['courseId'] ? $_POST['courseId'] : false;
		$data=[];
		$courseModel=new Course();
		$data=$courseModel->getDetailCourse($id);
		if($data!=null){
		foreach($data as $row){
			echo "<tr>";
			foreach($row as $key =>$value){
				if($key!='id')
				echo "<td>".htmlspecialchars($value)."</td>";
			}
			echo "</tr>";
		}
	} else{
	}

	}
	public function getDetailCourseOfTrainer(){
		$id= $_GET['id'];
		$data=[];
		$courseModel=new Course();
		$data['course']=$courseModel->getDetailCourse($id);
		$data['state']=$courseModel->getCourseById($id);
		$this->render('trainer/courseSchedule',$data);
		
	}

}
