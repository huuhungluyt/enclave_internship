<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';

class TraineeController extends Controller{
	public function getList ()
	{
		$trainee = new Trainee();
		$data['trainee'] = $trainee->getListTrainee();
		$this->render('traineeManagement', $data);
	}

	public function postAddTrainee ()
	{
		$account = new Account();

		$args['password'] = '12345678';
		$args['autho'] = 'trainee';
		$args['fullName'] = !empty($_POST['fullName']) ? filter_var($_POST['fullName'], FILTER_SANITIZE_STRING) : false;
		$args['gender'] = !empty($_POST['gender']) ? filter_var($_POST['gender'], FILTER_SANITIZE_STRING) : false;
		$args['dateOfBirth'] = !empty($_POST['dateOfBirth']) ? filter_var($_POST['dateOfBirth'], FILTER_SANITIZE_STRING) : false;
		$args['address'] = !empty($_POST['address']) ? filter_var($_POST['address'], FILTER_SANITIZE_STRING) : false;
		$args['phoneNumber'] = !empty($_POST['phoneNumber']) ? filter_var($_POST['phoneNumber'], FILTER_SANITIZE_STRING) : false;
		$args['email'] = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : false;
		$args['school'] = !empty($_POST['school']) ? filter_var($_POST['school'], FILTER_SANITIZE_STRING) : false;
		$args['faculty'] = !empty($_POST['faculty']) ? filter_var($_POST['faculty'], FILTER_SANITIZE_STRING) : false;
		$args['typeOfStudent'] = !empty($_POST['typeOfStudent']) ? filter_var($_POST['typeOfStudent'], FILTER_SANITIZE_STRING) : false;

		foreach ($args as $key => $arg) {
			if($arg === false){
				$error[$key] = true;
			}
		}
		if($account->getAccByEmail($args['email'])){
			$data['existEmail'] = true;
		}
		if(!isset($data['existEmail'])){
			if (!isset($error)) {
				$rs = $account->addAccount($args);
				if($rs) {
					$data['success'] = 'You have added successfully a new Trainee';
					$data['id']	= $rs;
				} else {
					$this->redirect('ctr=error&act=error404');
				}
			}else{
				$data['error'] = $error;
			}
		}
			
		echo json_encode($data);
	}

	public function getTraineeAjax()
	{
		$id = (empty($_GET['id'])) ? false : filter_var($_GET['id'], FILTER_SANITIZE_STRING);
		if($id) {
			$account = new Account();
			$rs = $account->getAccById($id);
			if($rs) {
				$data['success'] = $rs;
			}else{
				$data['error'] = true;
			}
		}else{
			$data['error'] = true;
		}
		echo json_encode($data);
	}

	public function postUpdate()
	{
		if(!empty($_POST['password'])) {
			if (!empty($_POST['confirmPassword']) && $_POST['password'] == $_POST['confirmPassword']) {
				$args['password'] = md5($_POST['password']);
			}
			else {
				$error['confirmPassword'] = false;
			}
		}

		//

		if(!empty($_FILES['profilePic']['name'])) {
			$profilePic = $this->transferFile($_FILES['profilePic']);
			$args['profilePic'] = $profilePic ? $profilePic : false;
		}

		$args['autho'] = 'trainee';
		$args['id'] = !empty($_POST['id']) ? intval($_POST['id']) : false;
		$args['fullName'] = !empty($_POST['fullName']) ? filter_var($_POST['fullName'], FILTER_SANITIZE_STRING) : false;
		$args['gender'] = !empty($_POST['gender']) ? filter_var($_POST['gender'], FILTER_SANITIZE_STRING) : false;
		$args['dateOfBirth'] = !empty($_POST['dateOfBirth']) ? filter_var($_POST['dateOfBirth'], FILTER_SANITIZE_STRING) : false;
		$args['address'] = !empty($_POST['address']) ? filter_var($_POST['address'], FILTER_SANITIZE_STRING) : false;
		$args['phoneNumber'] = !empty($_POST['phoneNumber']) ? filter_var($_POST['phoneNumber'], FILTER_SANITIZE_STRING) : false;
		$args['email'] = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : false;
		$args['school'] = !empty($_POST['school']) ? filter_var($_POST['school'], FILTER_SANITIZE_STRING) : false;
		$args['faculty'] = !empty($_POST['faculty']) ? filter_var($_POST['faculty'], FILTER_SANITIZE_STRING) : false;
		$args['typeOfStudent'] = !empty($_POST['typeOfStudent']) ? filter_var($_POST['typeOfStudent'], FILTER_SANITIZE_STRING) : false;

		foreach ($args as $key => $arg) {
			if($arg === false){
				$error[$key] = true;
			}
		}
		$account = new Account();
		if($acc = $account->getAccByEmail($args['email'])){
			if($acc['id'] != $args['id']){
				$_SESSION['error'] = "Updating is failed. Because the email existed.";
			}
		}
		if(!isset($_SESSION['error'])){
			if (!isset($error)) {
				$rs = $account->updateUser($args);
				if($rs) {
					$_SESSION['success'] = 'You have updated successfully a Trainee';
				}else{
					$this->redirect('ctr=error&act=error404');
				}
			}else{
				$_SESSION['error'] = 'Updating is failed';
			}
		}
		$this->redirect('ctr=trainee&act=getList');
	}

	public function postDelete()
	{
		$id = (empty($_POST['id'])) ? false : filter_var($_POST['id'], FILTER_SANITIZE_STRING);
		if($id){
			$acc = new Account();
			$rs = $acc->deleteAccount($id);
			if($rs){
				$_SESSION['success'] = "You have deleted successfully";
				$this->redirect('ctr=trainee&act=getList');
			}else{
				$this->redirect('ctr=error&act=error404');
			}
		}else{
			$this->redirect('ctr=error&act=error404');
		}

	}

	/*Supporting function*/
	public function transferFile($file) {
		$status = false;
		$target_dir = $_SERVER['DOCUMENT_ROOT']."/upload/Trainee/";
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
	public function postCourseList() {
		$trainee = new Trainee();
		$data = [];
		$data['course'] = $trainee->getSchedule($_GET['id']);
		// dump($data);
		$this->render('traineeSchedule',$data);
	}
}
