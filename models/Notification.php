<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainee.php';

/**
*
*/
class Notification extends Model
{
    public function selectReadNotifications($userId){
         try{
			$conn = $this->connect();
			$accModel= new Account();
			$userInfo= $accModel->getAccById($userId);
			if($userInfo['autho']=='admin'){
				$sql = "SELECT id, sender, receiver, type, content, createAt from read_notification where receiver is NULL";			
				$stmt = $conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
			}else{
				$sql = "SELECT id, sender, receiver, type, content, createAt from read_notification where receiver= :userId";
				$stmt = $conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$stmt->bindParam(':userId',$userId);
			}
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
    }

	public function selectNewNotificationBy($field, $value){
		 try{
			$conn = $this->connect();
			$sql = "SELECT * from new_notification where $field".(($value)?"=:value":" is NULL");			
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$array=[];
			if($value) $array["value"]= $value;
			$stmt->execute($array);
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}


	public function countNewNotifications($userId){
		try{
			$conn = $this->connect();
			$accModel= new Account();
			$userInfo= $accModel->getAccById($userId);
			if($userInfo['autho']=='admin'){
				$sql = "SELECT count(id) 'num' from new_notification where receiver is NULL";			
				$stmt = $conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
			}else{
				$sql = "SELECT count(id) 'num' from new_notification where receiver= :userId";
				$stmt = $conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$stmt->bindParam(':userId',$userId);
			}
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result[0]['num'];
		}catch(PDOException $e){
			return false;
		}
	}

	public function deleteNewNotificationBy($field, $value){
		try{
			$conn = $this->connect();
			$sql = "DELETE from new_notification WHERE $field = :value";
			$stmt = $conn->prepare($sql);
			return $stmt->execute(array(':value'=>$value));
		}catch(PDOException $e){
			return false;
		}
	}

	public function insertReadNotification($readNoti){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO read_notification VALUES (:id,:sender,:receiver, :type, :content, NOW(), ";
			$param= array(
					':id'=>$readNoti['id'],
					':sender'=>$readNoti['sender'],
					':receiver'=>$readNoti['receiver'],
					':type'=>$readNoti['type'],
					':content'=>$readNoti['content']);
			if(isset($readNoti['isApproved'])){
				$sql.=" :isApproved)";
				$param['isApproved']= $readNoti['isApproved'];
			}else
				$sql.=" NULL)";

			$stmt = $conn->prepare($sql);
			return $stmt->execute($param);
	    }catch(PDOException $e){
	    	return false;
	    }
	}
	
	public function insertNewNotification($args){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO new_notification VALUES (0, :sender,:receiver, :type, :content, NOW())";

			$stmt = $conn->prepare($sql);
			$stmt->execute(
				array(
					':sender'=>$args['sender'],
					':receiver'=>$args['receiver'],
					':type'=>$args['type'],
					':content'=>$args['content']
				)
			);
			return true;
	    }catch(PDOException $e){
	    	return false;//error 404
	    }
	}

  

}
