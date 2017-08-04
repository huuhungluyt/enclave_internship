<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';



/**
*
*/
class Trainee extends Model
{
  public function getSchedule($id){
		try{
			$conn = $this->connect();
			$sql = "SELECT distinct	course.id 	 ,
                      major.name 	 ,
                      account.fullName ,
	 				  course.startDate,
	 				  course.endDate,
                      learning.status
              from	learning,	major,	account,	course,	trainer
              where	learning.traineeId= :id
              and	learning.courseId= course.id
              and	course.trainerId= trainer.id
              and	account.id= course.trainerId
              and	course.majorId= major.id
              and learning.status<>'waiting'";

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



	public function getTraineeListByCourseId($id){
		try{
			$conn = $this->connect();
      $sql = "SELECT  account.id,
                      account.fullName,
                      account.gender,
                      account.phoneNumber,
                      account.address,
                      account.email ,
                      learning.status
			FROM  account
      INNER JOIN learning ON account.id=learning.traineeId
			WHERE learning.courseId =:id and account.deleteAt is NULL";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			if($result){
				return $result;
			}else{
				return false;
			}
		}catch(PDOException $e){
			echo $e;
			return false;
		}
	}

	public function updateStatus($args = []){
		try {
			$conn = $this->connect();
			foreach ($data['course'] as $temp) {
				$sql  = " UPDATE `learning`
				SET status=:status
				WHERE traineeId=:id";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':status',$args['status']);
				$stmt->bindParam(':id',$args['id']);
				$stmt->execute();
			     }
			        return true;
	       }catch(PDOException $e){
	    	echo $sql;
	    	echo $e;
	    	return false;//error 404
	       }
	}

	// add new user to database
	public function addTrainee($args = []){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO trainee (id,school,faculty,typeOfStudent)"
					." VALUES (:id,:school,:faculty,:typeOfStudent)";

			$stmt = $conn->prepare($sql);

			$stmt->bindParam(':id',$args['id']);
			$stmt->bindParam(':school',$args['school']);
			$stmt->bindParam(':faculty',$args['faculty']);
			$stmt->bindParam(':typeOfStudent',$args['typeOfStudent']);

			$stmt->execute();
			return true;
	    }catch(PDOException $e){
	    	return false;//error 404
	    }
	}

	public function getExtendedInfo($traineeId){
		 try{
			$conn = $this->connect();
			$sql = "SELECT school, faculty, typeOfStudent from trainee where id= :traineeId";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':traineeId'=>$traineeId));
			$result = $stmt->fetchAll();
			return $result[0];
		}catch(PDOException $e){
			return false;
		}
	}

	public function updateExtendedInfor($args = [])
	{
		try {
			$conn = $this->connect();
			$sql = "UPDATE  trainee
					set     school=:school,
							faculty=:faculty,
							typeOfStudent=:typeOfStudent
					where 	id=:id";

			$stmt = $conn->prepare($sql);

			$stmt->execute(
				array(
					':id'				=>$args['id'],
					':school'			=>$args['school'],
					':faculty'			=>$args['faculty'],
					':typeOfStudent'	=>$args['typeOfStudent']
				)
			);

		} catch (Exception $e) {
				return false;
		}
	}



	public function getListTrainee()
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
						trainee.school ,
						trainee.faculty ,
						trainee.typeOfStudent
					from account
					inner join trainee on account.id = trainee.id
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
}
