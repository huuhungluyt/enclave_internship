<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';	
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';

class TrainerController extends Controller{
	public function getList () 
	{
		$major = new Major();
		$data['major'] = $major->getAllMajor();
		$trainer = new Trainer();
		$data['trainer'] = $trainer->getListTrainer();
		$this->render('trainerManagement',$data);			
	}		
	
	public function postAddTrainer ()
	{
		$account = new Account();
		$trainer = new Trainer();
		$args['autho'] = 'trainer';
		
		$args['password'] = '12345678';			
		$args['fullName'] = !empty($_POST['fullName']) ? $_POST['fullName'] : false;
		$args['gender'] = !empty($_POST['gender']) ? $_POST['gender'] : false;
		$args['dateOfBirth'] = !empty($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : false;
		$args['address'] = !empty($_POST['address']) ? $_POST['address'] : false;
		$args['phoneNumber'] = !empty($_POST['phoneNumber']) ? $_POST['phoneNumber'] : false;
		$args['email'] = !empty($_POST['email']) ? $_POST['email'] : false;			
		$args['majorId'] = !empty($_POST['majorId']) ? $_POST['majorId'] : false;
		$args['experience'] = !empty($_POST['experience']) ? $_POST['experience'] : false;
		// $status = true;
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

	public function getTrainerSelector(){
		if(isset($_POST['majorId'])){
			$majorId= $_POST['majorId'];
			$trainerModel= new Trainer();
			$data= $trainerModel->getTrainersByMajorId($majorId);
			if($data){
				foreach($data as $row){
					$text= htmlspecialchars($row['trainerName']." (".$row["experience"].(($row["experience"]>1)?" years":" year").")");
					echo "<option value='".$row["trainerId"]."'>".$text."</option>";
				}
			}
		}else{
		}
	}

	public function showTrainerList(){
		if(isset($_POST['majorId'])){
			$majorId= $_POST['majorId'];
			$trainerModel= new Trainer();
			$data= $trainerModel->getTrainersByMajorId($majorId);
			if($data){
				foreach($data as $row){
					echo "<tr>";
					foreach ($row as $value) {
						echo "<td>".$value."</td>";
					}
					echo "</tr>";
				}
			}
		}else{
		}
	}

	public function getTrainerAjax() 
	{
		$id = (empty($_GET['id'])) ? false : $_GET['id'];
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

	public function postDelete() 
	{
		$id = (empty($_POST['id'])) ? false : filter_var($_POST['id'], FILTER_SANITIZE_STRING);
		if($id){
			$acc = new Account();
			$rs = $acc->deleteAccount($id);
			if($rs){
				$_SESSION['success'] = "You have deleted successfully";
				$this->redirect('ctr=trainer&act=getList');
			}else{
				$this->redirect('ctr=error&act=error404');
			}
		}else{
			$this->redirect('ctr=error&act=error404');
		}
		
	}


	public function postUpdate()
	{		
		// if(!empty($_POST['password'])) {
		// 	if (!empty($_POST['confirmPassword']) && $_POST['password'] == $_POST['confirmPassword']) {
		// 		$args['password'] = md5($_POST['password']);						
		// 	}
		// 	else {
		// 		$error['confirmPassword'] = false;
		// 	}
		// }
		
		// 
		if(!empty($_FILES['profilePic']['name'])) {
			$profilePic = $this->transferFile($_FILES['profilePic']);
			$args['profilePic'] = $profilePic ? $profilePic : false;
		}

		$args['autho'] = 'trainer';
		$args['id'] = !empty($_POST['id']) ? intval($_POST['id']) : false;
		$args['fullName'] = !empty($_POST['fullName']) ? filter_var($_POST['fullName'], FILTER_SANITIZE_STRING) : false;
		$args['gender'] = !empty($_POST['gender']) ? filter_var($_POST['gender'], FILTER_SANITIZE_STRING) : false;
		$args['dateOfBirth'] = !empty($_POST['dateOfBirth']) ? filter_var($_POST['dateOfBirth'], FILTER_SANITIZE_STRING) : false;
		$args['address'] = !empty($_POST['address']) ? filter_var($_POST['address'], FILTER_SANITIZE_STRING) : false;
		$args['phoneNumber'] = !empty($_POST['phoneNumber']) ? filter_var($_POST['phoneNumber'], FILTER_SANITIZE_STRING) : false;
		$args['email'] = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : false;			
		$args['majorId'] = !empty($_POST['majorId']) ? filter_var($_POST['majorId'], FILTER_SANITIZE_STRING) : false;
		$args['experience'] = !empty($_POST['experience']) ? filter_var($_POST['experience'], FILTER_SANITIZE_STRING) : false;

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
					$_SESSION['success'] = 'You have updated successfully a Trainer';				
				}else{
					$this->redirect('ctr=error&act=error404');
				}
			}else{
				$_SESSION['error'] = 'Updating is failed';				
			}
		}
		$this->redirect('ctr=trainer&act=getList');
	}
	/*Supporting function*/
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

}	
