<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';

/**
*
*/
class Trainer extends Model
{
	public function getSchedule($id){
		try{
			$conn = $this->connect();
			$sql = "SELECT distinct course.id, course.startDate, course.endDate, course.state
							FROM  course, lesson
							WHERE trainerId =:id
							and 	course.id=lesson.courseId" ;

			$stmt = $conn->prepare($sql);

			$stmt->bindParam(':id',$id);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}


	// add new user to database
	public function addTrainer($args = []){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO trainer (id,majorId,experience)"
					." VALUES (:id,:majorId,:experience)";

			$stmt = $conn->prepare($sql);

			$stmt->bindParam(':id',$args['id']);
			$stmt->bindParam(':majorId',$args['majorId']);
			$stmt->bindParam(':experience',$args['experience']);

			$stmt->execute();
			return true;
	    }catch(PDOException $e){
	    	return false;//error 404
	    }

	}
	
	public function getExtendedInfo($trainerId){
		try{
			$conn = $this->connect();
			$sql = "SELECT majorId, experience from trainer where id= :trainerId";			
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':trainerId'=>$trainerId));
			$result = $stmt->fetchAll();
			return $result[0];	
		}catch(PDOException $e){
			return false;
		}
	}


	public function getTrainersByMajorId($majorId){
		try{
			$conn = $this->connect();
			$sql = "SELECT trainer.id 'trainerId', account.fullName 'trainerName', trainer.experience 'experience' from trainer, account where majorId= :majorId and account.id=trainer.id and deleteAt is NULL order by experience desc";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':majorId'=>$majorId));
			$result = $stmt->fetchAll();
			if($stmt->rowCount()){
				return $result;
			}else{
				return NULL;
			}
		}catch(PDOException $e){
			return NULL;
		}
	}


	public function updateExtendedInfor($args = [])
	{
		try {
			$conn = $this->connect();

			$sql = "UPDATE  trainer
					set     majorId=:majorId, 
							experience=:experience
					where 	id=:id";

			$stmt = $conn->prepare($sql);

			$stmt->execute(
				array(
					':id'				=>$args['id'],
					':majorId'			=>$args['majorId'],
					':experience'		=>$args['experience']
				)
			);
			
		} catch (Exception $e) {
				return false;
		}	
	}
	// 
	public function getListTrainer()
	{
		try{
			$conn = $this->connect();
			$sql = 'select 	account.id , 
							account.email , 
							account.fullName , 
							account.gender , 
							account.dateOfBirth , 
							account.address , 
							account.phoneNumber , 
							account.profilePic , 
							trainer.majorId , 
							trainer.experience 
					from account
					inner join trainer on account.id = trainer.id
					where account.deleteAt is NULL';
			$stmt = $conn->prepare($sql);			
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			if($result){
				return $result;
			}else{
				return false;
			}
		}catch(PDOException $e){
			return false;
		}
	}
	public function deleteMajor($id){
		try{
			$conn = $this->connect();
			$sql = "UPDATE trainer SET majorId = NULL WHERE majorId = :id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
			return true;
		}catch(PDOException $e){
			return false;
		}
		return false;
	}

	public function getMajorId(){
		try{
			$conn = $this->connect();
			$sql = "SELECT * from major where major.id in (Select majorId from trainer)";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}
	

	
}


