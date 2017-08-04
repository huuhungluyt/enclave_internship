<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Notification.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
// require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';

class NoticeController extends Controller{
	public function newNotis(){
		$this->render("notifications");
	}
	
	//HUNTER
	public function loadNewNotifications() 
	{
		$userId= NULL;
		if(isset($_SESSION['admin_infor'])) {
			$table = 'new_notification';
			$primaryKey = 'id';
			$columns = array(
				array( 'db' => '`nn`.`id`', 'dt' => 'id', 'field' => 'id'),
				array( 'db' => '`nn`.`sender`',  'dt' => 'sender', 'field' => 'sender'),
				array( 'db' => '`acc`.`fullName`',   'dt' => 'fullName', 'field' => 'fullName'),
				array( 'db' => '`acc`.`autho`',     'dt' => 'autho', 'field' => 'autho'),
				array( 'db' => '`nn`.`type`',     'dt' => 'type', 'field' => 'type'),
				array( 'db' => '`nn`.`content`',     'dt' => 'content', 'field' => 'content'),
				array( 'db' => '`nn`.`createAt`', 'dt' => 'createAt', 'field' => 'createAt',
				'formatter' => function( $d, $row ) {
					return date( 'Y-m-d H:i:s', strtotime($d));
				})
			);

			// SQL server connection information
			require($_SERVER['DOCUMENT_ROOT'].'/libs/config.php');

			require($_SERVER['DOCUMENT_ROOT'].'/libs/ssp.customized.class.php' );

			$joinQuery = "FROM `new_notification` AS `nn` JOIN `account` AS `acc` ON (`nn`.`sender` = `acc`.`id`)";
			$extraWhere = "`nn`.`receiver` is NULL and `nn`.`type` in ('requested course', 'requested major', 'requested schedule', 'modified schedule')";

			echo json_encode(
				SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
			);
		}
    }

	//HUNTER
	public function loadReadNotifications() 
	{
		$userId= NULL;
		if(isset($_SESSION['admin_infor'])) {
			$table = 'read_notification';
			$primaryKey = 'id';
			$columns = array(
				array( 'db' => '`nn`.`id`', 'dt' => 'id', 'field' => 'id'),
				array( 'db' => '`nn`.`sender`',  'dt' => 'sender', 'field' => 'sender'),
				array( 'db' => '`acc`.`fullName`',   'dt' => 'fullName', 'field' => 'fullName'),
				array( 'db' => '`acc`.`autho`',     'dt' => 'autho', 'field' => 'autho'),
				array( 'db' => '`nn`.`type`',     'dt' => 'type', 'field' => 'type'),
				array( 'db' => '`nn`.`content`',     'dt' => 'content', 'field' => 'content'),
				array( 'db' => '`nn`.`readAt`', 'dt' => 'readAt', 'field' => 'readAt',
				'formatter' => function( $d, $row ) {
					return date( 'Y-m-d H:i:s', strtotime($d));
				}),
				array( 'db' => '`nn`.`isApproved`',     'dt' => 'isApproved', 'field' => 'isApproved'),
			);
			
			// SQL server connection information
			require($_SERVER['DOCUMENT_ROOT'].'/libs/config.php');
			
			require($_SERVER['DOCUMENT_ROOT'].'/libs/ssp.customized.class.php' );
			
			$joinQuery = "FROM `read_notification` AS `nn` JOIN `account` AS `acc` ON (`nn`.`sender` = `acc`.`id`)";
			$extraWhere = "`nn`.`receiver` is NULL and `nn`.`type` in ('requested course', 'requested major', 'requested schedule', 'modified schedule')";        
			
			echo json_encode(
				SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
			);
		}
    }
	
	//HUNTER
    public function getNumOfNewNotis(){
		$userId= NULL;
		if(isset($_SESSION['admin_infor'])) {
			$userId= $_SESSION['admin_infor']['id'];
            $noti= new Notification();
            echo $noti->countNewNotifications($userId);
		}else{
			echo -1;
		}
	}


	public function denyNotification(){
		$data=[];
		if(isset($_SESSION['admin_infor'])){
			if(isset($_POST['notiId'])){
				$notiModel= new Notification();
				$noti= $notiModel->selectNewNotificationBy('id', $_POST['notiId'])[0];

				$denyNoti=[];
				$denyNoti['sender']= NULL;
				$denyNoti['receiver']= $noti['sender'];

				//DENY SUGGEST NEW COURSE FROM TRAINEE
				if($noti['type']=='requested course'){
					$denyNoti['type']= 'denied course';
					$denyNoti['content']= explode(";",$noti['content'])[1];

				//DENY SUGGEST NEW MAJOR FROM TRAINEE
				}elseif($noti['type']=='requested major'){
					$denyNoti['type']= 'denied major';
					$denyNoti['content']= explode(";",$noti['content'])[0];


				}elseif(($noti['type']=='modified schedule')||($noti['type']=='requested schedule')){
					$denyNoti['type']= 'denied schedule';
					$denyNoti['content']= explode(";",$noti['content'])[0];
				}

				if($notiModel->insertNewNotification($denyNoti)){
					$noti['isApproved']=0;
					$notiModel->insertReadNotification($noti);
					$notiModel->deleteNewNotificationBy('id', $noti['id']);
					$data['success']= "Denied suggest new course from ".$noti['sender']." successfully !";
				}else{
					$data['error']= "Deny failed !";
				}
			}else{
				$data['error']= "Unknow which notification to deny !";
			}
		}else{
			$data['error']= "Please login as admin !";
		}
		echo json_encode($data);
	}



	public function approveNotification($notiId, $approve){
		$result=[];
		$data=[];
		if(isset($_SESSION['admin_infor'])){
			$notiModel= new Notification();
			$noti= $notiModel->selectNewNotificationBy('id', $notiId)[0];


			$apprNoti=[];
			$apprNoti['sender']= NULL;
			$apprNoti['receiver']= $noti['sender'];

			//---------------------------------------------
			//APPROVE SUGGEST NEW COURSE FROM TRAINEE
			//----------------------------------------------
			if($noti['type']=='requested course'){
				$apprNoti['type']= 'approved course';
				$apprNoti['content']= explode(";",$noti['content'])[1].";".$approve["newCourseId"];
						
					
			//---------------------------------------------
			//APPROVE SUGGEST NEW MAJOR FROM TRAINEE
			//----------------------------------------------
			}elseif($noti['type']=='requested major'){
				$apprNoti['type']= 'approved major';
				$apprNoti['content']= explode(";",$noti['content'])[0];


			//---------------------------------------------
			//APPROVE SUGGEST CHANGE LESSON BY DAY
			//----------------------------------------------
			}elseif(($noti['type']=='modified schedule')||($noti['type']=='requested schedule')){
				$apprNoti['type']= 'approved schedule';
				$apprNoti['content']= explode(";",$noti['content'])[0];
			}else return false;


			if($notiModel->insertNewNotification($apprNoti)){
				$noti['isApproved']=1;
				$notiModel->insertReadNotification($noti);
				$notiModel->deleteNewNotificationBy("id", $noti['id']);
				return true;
			}else return false;
		}else return false;
	}

	public function sendNotification($courseId, $typeOfNoti){
	    $courseModel= new Course();
	    $traineeModel= new Trainee();
	    $notiModel= new Notification();
	    $course= $courseModel->getCourseById($courseId);
	    $receiverList=[];
	    $receiverList[0]= $course['trainerId'];

	    $traineeList= $traineeModel->getTraineeListByCourseId($courseId);
	    for($i=0; $i<sizeof($traineeList); $i++){
	      $receiverList[$i+1]= $traineeList[$i]['id'];
	    }
	    foreach($receiverList as $receiver){
	      $temp=[];
	      $temp['sender']= NULL;
	      $temp['receiver']= $receiver;
	      $temp['type']= $typeOfNoti;
	      $temp['content']= $courseId.";".$course['majorName'].";".$course['trainerName'];
	      $notiModel->insertNewNotification($temp);
	    }
	  }

	public function announceChangedTrainer($courseId, $oldTrainerId){
	    $courseModel= new Course();
		$accModel= new Account();
	    $traineeModel= new Trainee();
	    $notiModel= new Notification();
	    $course= $courseModel->getCourseById($courseId);
		$oldTrainer= $accModel->getAccById($oldTrainerId);
	    $receiverList=[];
	    $receiverList[0]= $course['trainerId'];

	    $traineeList= $traineeModel->getTraineeListByCourseId($courseId);
		if($traineeList){
			for($i=0; $i<sizeof($traineeList); $i++){
			$receiverList[$i+1]= $traineeList[$i]['id'];
			}
		}
		$receiverList[sizeof($receiverList)]= $oldTrainerId;
	    foreach($receiverList as $receiver){
	      $temp=[];
	      $temp['sender']= NULL;
	      $temp['receiver']= $receiver;
	      $temp['type']= "changed trainer";
	      $temp['content']= $courseId.";".$course['majorName'].";".$oldTrainerId.";".$oldTrainer['fullName'].";".$course['trainerId'].";".$course['trainerName'];
	      $notiModel->insertNewNotification($temp);
	    }
	}
}	
