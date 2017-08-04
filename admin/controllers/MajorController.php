<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/controllers/NoticeController.php';

class MajorController extends Controller{
	public function getMajor(){
		$major=new Major();
		$data['major']=$major->getAllMajorVer2();		
		$this->render('majorManagement',$data);
	}
	public function getMajorById(){
		$major= new Major();
		$data['detail'] = $major->selectMajorById($_GET['id']);
		echo json_encode($data['detail']);
	}
	
	public function postAddMajor ()
	{
		$data=[];
		$notiId= isset($_POST['notiId'])?$_POST['notiId']:NULL;
		$majorModel = new Major();
		$major['name'] = !empty($_POST['majorName']) ? filter_var($_POST['majorName'], FILTER_SANITIZE_STRING) : NULL;
		$major['description'] = !empty($_POST['majorDescription']) ? filter_var($_POST['majorDescription'], FILTER_SANITIZE_STRING) : NULL;

		foreach ($major as $field) {
			if(!$field){
				$data['error'] = "Required field is empty!";
				echo json_encode($data);
				return;					
			}
		}		
		if(!$majorModel->isExistedMajor($major)){
			if($majorModel->addMajor($major)) {
				$data['success'] = 'You added successfully a new major!';
				if($notiId){
					$noticeCtrl= new NoticeController();
					if($noticeCtrl->approveNotification($notiId, NULL))
						$result['success'].="\nSuggestion was approved!";
				}		
			}else{ 
				$data['error'] = "Can't add major!";
			} 
		} else{
			$data['error'] ='This major already existed !';
		}
		echo json_encode($data);
	}

	public function postUpdateMajor ()
	{
		$data=[];
		$major = new Major();
		$args['id'] = !empty($_POST['majorId']) ? filter_var($_POST['majorId'], FILTER_SANITIZE_STRING) : false;
		$args['name'] = !empty($_POST['majorName']) ? filter_var($_POST['majorName'], FILTER_SANITIZE_STRING) : false;
		$args['description'] = !empty($_POST['majorDescription']) ? filter_var($_POST['majorDescription'], FILTER_SANITIZE_STRING) : false;
		foreach ($args as $key => $arg) {				
			if($arg === false){
				$data['error'] = "Have empty field !";
				echo json_encode($data);
				return;					
			}
		}

		if(!$major->isExistedMajor($args)){
			$res= $major->editMajor($args);
			if($res){
				$data['success'] = 'You updated successfully !';					
			} else{
				$data['error'] = "Can't update major!";
			}
		} else{
			$data['error'] ='This major already existed !';
		} 
		echo json_encode($data);
	}
	public function postDeleteMajor()
	{
		$data=[];
		$major= new Major();
		if($major->deleteMajor($_POST['id'])) $data['success']= "You deleted major successfully !";
		else $data['error']= "Can't delete major !";
		//dump($data);
		echo json_encode($data);
	}
}

?>