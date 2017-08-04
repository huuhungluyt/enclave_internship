<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';	
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';

class AuthController extends Controller{
	// public function getLogin() 
	// {   	
	// 	if(isset($_SESSION['admin_infor'])) {
	// 		$this->redirect('ctr=home&act=index');	
	// 	}
 //        $this->render("auth/login");
 //    }
   
	// public function postLogin()
	// {		
	// 	$acc = new Account();

	// 	if(isset($_POST['signin'])){
	// 		$id = $_POST['id'];
	// 		$password = $_POST['password'];			
	// 		$pass = md5($password);			

	// 		if($rs = $acc->getAccById($id)) {
	// 			if($rs['password'] == $pass && $rs['autho'] == 'admin') {
	// 				$_SESSION['admin_infor'] = $rs;
	// 				$this->redirect('ctr=home&act=index');	
	// 			}else{
	// 				$error = true;
	// 			}
	// 		}else{
	// 			$error = true;
	// 		}	

	// 		if($error){
	// 			$_SESSION['warning'] = 'ID hoặc mật khẩu không hợp lệ';
	// 			$this->redirect('ctr=auth&act=getLogin');
	// 		}else{
				
	// 		}
	// 	}else{
	// 		$this->redirect('ctr=auth&act=getLogin');
	// 	}
	// }

	public function logout()	
	{
		unset($_SESSION['admin_infor']);
		$this->redirect('ctr=auth&act=getLogin','');
	}
}	
