<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';


class TraineeController extends Controller{
	public function getInforById()
	{
		if(!isset($_SESSION['trainee_infor']))
		{
			$this->redirect("ctr=auth&act=getLogin");
		}
		$this->render('trainee/updateInfo');
	}

	public function postUpdateInfor()
	{
		//$rs=[];	
				$arg = array(
					'id' => $_POST['id'],
					'autho' => $_SESSION['trainee_infor']['autho'],
					'fullName' => trim($_POST['fullName']),
					'gender' => trim($_POST['gender']),
					'dateOfBirth' => trim($_POST['dateOfBirth']),
					'address' => trim($_POST['address']),
					'phoneNumber' => trim($_POST['phoneNumber']),
					'email' => trim($_POST['email']),
					'school' => trim($_POST['school']),
					'faculty' => trim($_POST['faculty']),
					'typeOfStudent' => trim($_POST['typeOfStudent']),
				);
				if(!empty($_FILES['profilePic']['name'])) {
					$profilePic = $this->transferFile($_FILES['profilePic']);
					$arg['profilePic'] = $profilePic ? $profilePic : false;
				}

				$accountModel = new Account();
				$data= $accountModel -> updateUser($arg);

				if($data){
					// $rs['success'] = 'You have updated successfully !';
					// $_SESSION['success'] = 'You have updated successfully a Trainee';
					$_SESSION['trainee_infor']= $data;
				}else{
					//$rs['error']= 'Failed';
					$this->redirect('ctr=error&act=error404');
				}
				//dump($rs);
				// echo json_encode($rs);
					$this->redirect('ctr=trainee&act=getInforById');
				}


	public function postUpdatePassword(){
		$rs=[];
		if((md5($_POST['currentPass'])==$_SESSION['trainee_infor']['password'])){
			$args = array(
					'id' => $_SESSION['trainee_infor']['id'],	
					'password' => $_POST['newPassword']
				);
		 
			$account = new Account();
			$data = $account -> updatePassword($args);
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

}
