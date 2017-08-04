<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';
/**
*
*/
class Account extends Model {
	// add new user to database
	public function addAccount($args = []){
		try {
			$conn = $this->connect();
			$trainee = new Trainee();
			$trainer = new Trainer();
			$rs = false;

			$sql  = "INSERT INTO account (password,autho,fullName,gender,dateOfBirth,address,phoneNumber,email)"
					." VALUES (:password,:autho,:fullName,:gender,:dateOfBirth,:address,:phoneNumber,:email)";
			$stmt = $conn->prepare($sql);

			$password = md5($args['password']);
			$stmt->bindParam(':password',$password);
			$stmt->bindParam(':autho',$args['autho']);
			$stmt->bindParam(':fullName',$args['fullName']);
			$stmt->bindParam(':gender',$args['gender']);
			$stmt->bindParam(':dateOfBirth',$args['dateOfBirth']);
			$stmt->bindParam(':address',$args['address']);
			$stmt->bindParam(':phoneNumber',$args['phoneNumber']);
			$stmt->bindParam(':email',$args['email']);

			$stmt->execute();
			$id = $conn->lastInsertId();

			switch ($args['autho']) {
				case 'trainee':
					$school = $args['school'];
					$faculty = $args['faculty'];
					$typeOfStudent = $args['typeOfStudent'];
					$rs = $trainee->addTrainee(compact('id','school','faculty','typeOfStudent'));
					break;
				case 'trainer':
					$majorId = $args['majorId'];
					$experience = $args['experience'];
					$rs = $trainer->addTrainer(compact('id','majorId','experience'));
					break;
				default:
					return false;
					break;
			}
			$rs = ($rs) ? $id : false;
			return $rs;
	    }catch(PDOException $e){
	    	echo $e;
	    	return false;//error 404
	    }
	}


	public function getAccById($acc_id){
		try{
			$conn = $this->connect();
			$sql = 'select * from account where id = :acc_id';
			$stmt = $conn->prepare($sql);
			$stmt->execute(array(':acc_id'=>$acc_id));
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			if($result){
				if($result[0]['autho']=='trainee'){
					$traineeModel= new Trainee();
					$extendedInfo= $traineeModel->getExtendedInfo($result[0]['id']);
					$result[0]['school']= $extendedInfo['school'];
					$result[0]['faculty']= $extendedInfo['faculty'];
					$result[0]['typeOfStudent']= $extendedInfo['typeOfStudent'];
				}elseif($result[0]['autho']=='trainer'){
					$trainerModel= new Trainer();
					$extendedInfo= $trainerModel->getExtendedInfo($result[0]['id']);
					$result[0]['majorId']= $extendedInfo['majorId'];
					$result[0]['experience']= $extendedInfo['experience'];
				}
				return $result[0];
			}else{
				return false;
		}
		}catch(PDOException $e){
			return false;
		}
	}

	public function updateUser($args = []){
		try {
			$conn = $this->connect();
			$trainee = new Trainee();
			$trainer = new Trainer();
			$sql = 'UPDATE account SET ';
			foreach ($args as $key => $arg) {
				switch ($key) {
					case 'autho':
					case 'id':
					case 'school':
					case 'faculty':
					case 'typeOfStudent':
					case 'majorId':
					case 'experience':
					case 'password':
					break;
					default:
						$sql .= $key . '=:' . $key . ',';
						break;
				}

			}
			$sql = rtrim($sql, ',');
			$sql .= ' WHERE id =:id';
			$stmt = $conn->prepare($sql);
			$param = [];
			foreach ($args as $key => $arg) {
				switch ($key) {
					case 'school':
					case 'autho':
					case 'id':
					case 'faculty':
					case 'typeOfStudent':
					case 'majorId':
					case 'experience':
					case 'password':
					break;
					default:
						$param[$key] = $arg;
						break;
				}

			}

			$param['id'] = $args['id'];
			$stmt->execute($param);

			if ($args['autho']=='trainee')
			{
				$id = $args['id'];
				$school = $args['school'];
				$faculty = $args['faculty'];
				$typeOfStudent = $args['typeOfStudent'];
				$trainee->updateExtendedInfor($args);
			}
			elseif ($args['autho']=='trainer') {
				$majorId = $args['majorId'];
				$experience = $args['experience'];
				$trainer->updateExtendedInfor($args);
			}

			$accModel= new Account();
			return $accModel->getAccById($args['id']);
		   }catch(PDOException $e){
	    	return false;
	    }
	}

	
	public function deleteAccount($accountId) {
		try{
			$conn = $this->connect();
			$sql = "UPDATE account SET deleteAt = NOW() WHERE id =:accountId";

			$stmt = $conn->prepare($sql);
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$stmt->bindParam(':accountId',$accountId);

			return $stmt->execute();
		}catch(PDOException $e){
			echo  $e;
			return false;
		}
	}
	public function checkLogin($id, $password) {
		$status = false;
		try{
			$conn = $this->connect();
			if($rs = $conn->getAccById($id)){
				$pass = md5($password);
				$status = ($pass == $rs['password']) ? $rs : false;
			}
		}catch(PDOException $e){
			return false;
		}
		return $status;
	}

	public function getAccByEmail($email){
		try{
			$conn = $this->connect();
			$sql = 'select * from account where email = :email';
			$stmt = $conn->prepare($sql);
			$stmt->execute(array(':email'=>$email));
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			if($result){
				return $result[0];
			}else{
				return false;
		}
		}catch(PDOException $e){
			return false;
		}
	}

	public function updatePassword($args = []){
		try{
			$conn = $this->connect();
			$sql = 'select * from account where email = :email';
			$sql = "UPDATE account SET password = :password WHERE id =:id";

			$stmt = $conn->prepare($sql);
			$password = md5($args['password']);
			$stmt->bindParam(':password', $password);
			$stmt->bindParam(':id',$args['id']);
			return $stmt->execute();

		}catch(PDOException $e){
			return false;
		}
	}

}
