<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Notification.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
class NoticeController extends Controller{
	public function newNotis(){
		$this->render("notifications");
	}

	public function loadNewNotis()
	{
		$userId= NULL;
		if(isset($_SESSION['trainee_infor'])) {
			$userId= $_SESSION['trainee_infor']['id'];
		}elseif(isset($_SESSION['trainer_infor'])){
        	$userId= $_SESSION['trainer_infor']['id'];
		}else return ;

		$table = 'new_notification';
		$primaryKey = 'id';
		$columns = array(
			array( 'db' => 'id', 'dt' => 'id', 'field' => 'id'),
			array( 'db' => 'type',  'dt' => 'type', 'field' => 'type'),
			array( 'db' => 'content',   'dt' => 'content', 'field' => 'content'),
			array( 'db' => 'createAt', 'dt' => 'createAt', 'field' => 'createAt',
			'formatter' => function( $d, $row ) {
				return date( 'Y-m-d H:i:s', strtotime($d));
			})
		);

		// SQL server connection information
		require($_SERVER['DOCUMENT_ROOT'].'/libs/config.php');
		require($_SERVER['DOCUMENT_ROOT'].'/libs/ssp.customized.class.php' );

		$where = "receiver=$userId";

		$data= json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, NULL,$where )
		);

		$notiModel= new Notification();
		$newNotis= $notiModel->selectNewNotificationBy('receiver', $userId);
		foreach($newNotis as $noti)
			$notiModel->insertReadNotification($noti);
        $notiModel->deleteNewNotificationBy('receiver', $userId);
		echo $data;
    }

	public function loadReadNotis() 
	{
		$userId= NULL;
		if(isset($_SESSION['trainee_infor'])) {
			$userId= $_SESSION['trainee_infor']['id'];
		}elseif(isset($_SESSION['trainer_infor'])){
        	$userId= $_SESSION['trainer_infor']['id'];
		}else return ;

		$table = 'read_notification';
		$primaryKey = 'id';
		$columns = array(
			array( 'db' => 'id', 'dt' => 'id', 'field' => 'id'),
			array( 'db' => 'type',  'dt' => 'type', 'field' => 'type'),
			array( 'db' => 'content',   'dt' => 'content', 'field' => 'content'),
			array( 'db' => 'readAt', 'dt' => 'readAt', 'field' => 'readAt',
			'formatter' => function( $d, $row ) {
				return date( 'Y-m-d H:i:s', strtotime($d));
			})
		);
		
		// SQL server connection information
		require($_SERVER['DOCUMENT_ROOT'].'/libs/config.php');
		require($_SERVER['DOCUMENT_ROOT'].'/libs/ssp.customized.class.php' );

		$where = "receiver=$userId";        
		
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, NULL,$where )
		);
    }

	public function getNumOfNewNotis(){
		$userId= NULL;
		if(isset($_SESSION['trainee_infor'])) {
			$userId= $_SESSION['trainee_infor']['id'];
		}elseif(isset($_SESSION['trainer_infor'])){
        	$userId= $_SESSION['trainer_infor']['id'];
		}else{
			return;
		}
		$noti= new Notification();
        echo $noti->countNewNotifications($userId);
	}

	public function sendRequest(){

		$major=$_POST["choose"];
		$rs=[];
		// $availableMajor=$_POST["available_major"];

		if(isset($_SESSION['trainee_infor'])) {
			$userId=$_SESSION['trainee_infor']['id'];
		}elseif(isset($_SESSION['trainer_infor'])){
        	$userId=$_SESSION['trainer_infor']['id'];
		}else{
			$this->render('auth/login');
			return;
		}
		$args= [];
		$args['sender']=$userId;
		$args['receiver']=NULL;

		if(isset($_SESSION['trainer_infor'])){
			$args['type']="requested schedule";
		    $args['content']=$_POST['content'];

		} else{
			if	($major=="available_major")	{
				$args['type']="requested course";
				$args['content'] = $_POST['majorId'] . ";" . $_POST['majorName'];
			}else if($major=="new_major"){
				$args['type']="requested major";
				$args['content'] = $_POST['name'] . ";" . $_POST['description'];
			}

		}

		$notification= new Notification();
		if($notification->insertNewNotification($args)){
			$rs['success']= "Send succesfully !";
		}else{
			$rs['error']= "Error !";
		}
		echo json_encode($rs);
	}


}	
