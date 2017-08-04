<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/PHPMailerAutoload.php';
class AuthController extends Controller {
    public function getLogin() {        
        $this->render("auth/login");
    }
   
	public function postLogin(){
		
		$acc = new Account();

		if(isset($_POST['signin'])){
			$id = $_POST['id'];
			$password = $_POST['password'];			
			$pass = md5($password);			

			if($rs = $acc->getAccById($id)) {
				if($rs['password'] == $pass) {	

					switch ($rs['autho']) {
						case 'trainer':
						{
							if(isset($_SESSION['trainee_infor'])) {
								unset($_SESSION['trainee_infor']);
								unset($_SESSION['user_infor']);
							}
							$_SESSION['user_infor'] = true;
							$_SESSION['trainer_infor'] = $rs;
							$this->redirect('ctr=home&act=index');
							break;
						}
						case 'trainee':
						{
							if(isset($_SESSION['trainer_infor'])) {
								unset($_SESSION['trainer_infor']);
								unset($_SESSION['user_infor']);
							}
							$_SESSION['user_infor'] = true;	
							$_SESSION['trainee_infor'] = $rs;
							$this->redirect('ctr=home&act=index');
							break;
						}
						case 'admin':
						{													
							$_SESSION['admin_infor'] = $rs;
							$this->redirect('ctr=home&act=index','/admin');
							break;
						}
						default:
							$error = true;			
							break;
					}	
																											
				}else{
					$_SESSION['warning'] = "The password is incorrect.";
				}
			}else{
				$_SESSION['warning'] = "The ID don't exist.";
			}	

			if(isset($_SESSION['warning'])){				
				$this->redirect('ctr=auth&act=getLogin');
			}
		}
	}

	public function logout(){	
		if(isset($_SESSION['trainer_infor'])) {
			unset($_SESSION['trainer_infor']);
			unset($_SESSION['user_infor']);
		}
		if(isset($_SESSION['trainee_infor'])) {
			unset($_SESSION['trainee_infor']);
			unset($_SESSION['user_infor']);
		}
		
		$this->redirect('ctr=auth&act=getLogin');
	}

	public function forgotPassword()
	{
		if(!empty($_POST['email'])) {		
			$recipient = $_POST['email'];
			$acc = new Account();
			// $recipient = 'hoanvv.it@gmail.com';
			if($rs = $acc->getAccByEmail($recipient)){
				if($rs['autho'] != 'admin') {
					$fullName = $rs['fullName'];
					$args['id'] = $rs['id'];

					$mail = new PHPMailer;
					$args['password'] = $this->generateRandomString();
					$acc->updatePassword($args);				

					// $mail->SMTPDebug = 3;                               // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = 'enclavetrainingcourse@gmail.com';                 // SMTP username
					$mail->Password = 'nghile12345';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;                                    // TCP port to connect to

					$mail->setFrom('enclavetrainingcourse@gmail.com', 'Enclave Training Course');
					$mail->addAddress($recipient, $fullName);     // Add a recipient
					$mail->isHTML(true);                                  // Set email format to HTML

					$mail->Subject = 'Enclave Training Course - Password Reset Request';
					$mail->Body    = $fullName . ', in order to reset your password at Enclave Training Course.'
									.'<h3>Your ID: <b><i>'.$args['id'].'</i></b></h3>'
									.'<h3>Your new password: <b><i>'.$args['password'].'</i></b></h3>'
									.'Thanks.<br> Enclave Training Course';

					// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

					if(!$mail->send()) {
						$data['error'] = "Our mail system is being maintained. Sorry you.";
					    // echo 'Message could not be sent.';
					    // echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
					    $data['success'] = "Password Reset Request have been sent. You can check mail to get your new password";				    
					}
				}else{
					$data['error'] = "This email don't exist";	
				}
				
			}else{
				$data['error'] = "This email don't exist";
			}
			echo json_encode($data);			
		}
	}
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}
