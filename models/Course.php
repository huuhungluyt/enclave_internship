<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Learning.php';
class Course extends Model
{

	public function getCourseById($courseId){
    	try{
			$conn = $this->connect();
			$sql = "SELECT course.id 'id', major.id 'majorId', major.name 'majorName', trainerId, account.fullName 'trainerName', state, required, numOfLessons, startDate, endDate
							from course, account, major
							where course.id= :courseId and majorId= major.id and trainerId= account.id";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':courseId'=>$courseId));
			$result = $stmt->fetchAll();
			return $result[0];
		}catch(PDOException $e){
			return false;
		}
  	}

	public function selectCourseFromLesson($lessonId){
		try{
			$conn = $this->connect();
			$sql = "SELECT course.id 'id', major.id 'majorId', major.name 'majorName', trainerId, account.fullName 'trainerName', state, required, numOfLessons, startDate, endDate
							from course, account, major
							where course.id in (select courseId from lesson where id=:lessonId) and majorId= major.id and trainerId= account.id";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':lessonId'=>$lessonId));
			$result = $stmt->fetchAll();
			return $result[0];
		}catch(PDOException $e){
			return false;
		}
	}

	public function selectDaysInWeek($courseId){
		try {
	 		$conn = $this -> connect();
	 		$sql= "SELECT dayOfWeek, substring(startTime, 12, 8) startHour,substring(endTime, 12, 8) endHour FROM lesson WHERE courseId=:courseId GROUP BY dayOfWeek, 2,3";
	 		$stmt = $conn->prepare($sql);
	 		$stmt->bindParam(':courseId',$courseId);
	 		$stmt->setFetchMode(PDO::FETCH_ASSOC);
	 		$stmt->execute();
	 		return $stmt->fetchAll();
	 	} catch (PDOException $e) {
	 		return false;
	 	}
	}

	public function selectDetailDaysInWeek($courseId){
		try {
	 		$conn = $this -> connect();
			$result= $this->selectDaysInWeek($courseId);
	 		$lessons= $this->getDetailCourse($courseId);
			for($i=0; $i<sizeof($result); $i++){
				foreach($lessons as $lesson){
					if($result[$i]['dayOfWeek']==$lesson['dayOfWeek']&&$result[$i]['startHour']==explode(" ", $lesson['startTime'])[1]){
						$result[$i]['roomId']= $lesson['roomId'];
						break;
					}
				}
			}
			return $result;
	 	} catch (PDOException $e) {
	 		return false;
	 	}
	}


	public function getSchedule(){
		try{
			$conn = $this->connect();
			$sql = "SELECT	distinct  course.id 'id',
												 				major.name,
												 				account.fullName,
												 				course.state
					 		from	major,account,course,trainer
					 		where	course.trainerId= trainer.id
					 		and     trainer.id= account.id
					 		and		course.majorId= major.id";
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

	public function getDetailCourse($courseId){
	 	try {
	 		$conn = $this -> connect();
	 		$sql 	= "SELECT * FROM lesson WHERE courseId = :courseId";
	 		$stmt = $conn->prepare($sql);
	 		$stmt->bindParam(':courseId',$courseId);
	 		$stmt->setFetchMode(PDO::FETCH_ASSOC);
	 		$stmt->execute();
	 		$result = $stmt->fetchAll();
	 		return $result;
	 	} catch (PDOException $e) {
	 		return false;
	 	}
	}


	public function selectLessonByDay($courseId, $dayOfWeek, $rangeOfHour){
		try {
	 		$conn = $this -> connect();
	 		$sql 	= "SELECT * FROM lesson WHERE courseId = :courseId";
			$param['courseId']= $courseId;
			if($dayOfWeek&&$rangeOfHour){
				$sql.=" and dayOfWeek=:dayOfWeek and substring(startTime, 12, 8)=:startHour";
				$param["dayOfWeek"]= $dayOfWeek;
				$param["startHour"]= explode(" - ", $rangeOfHour)[0];
			}
	 		$stmt = $conn->prepare($sql);
	 		$stmt->setFetchMode(PDO::FETCH_ASSOC);
	 		$stmt->execute($param);
	 		$result = $stmt->fetchAll();
	 		return $result;
	 	} catch (PDOException $e) {
	 		return false;
	 	}
	}


	//Select the lessons which start at least 24h after then
	public function selectRemainLessonByDay($courseId, $dayOfWeek, $rangeOfHour){
		try {
	 		$conn = $this -> connect();
	 		$sql 	= "SELECT * FROM lesson WHERE courseId = :courseId and timediff(startTime, NOW())>=24";
			$param['courseId']= $courseId;
			if($dayOfWeek){
				$sql.=" and dayOfWeek=:dayOfWeek and substring(startTime, 12, 8)=:startHour";
				$param["dayOfWeek"]= $dayOfWeek;
				$param["startHour"]= explode(" - ", $rangeOfHour)[0];
			}
	 		$stmt = $conn->prepare($sql);
	 		$stmt->setFetchMode(PDO::FETCH_ASSOC);
	 		$stmt->execute($param);
	 		$result = $stmt->fetchAll();
	 		return $result;
	 	} catch (PDOException $e) {
	 		return false;
	 	}
	}


	public function getCourseList(){
		try{
			$conn = $this->connect();
			$sql = "SELECT	distinct course.id 'id',
		                           major.name 'major',
		                           account.fullName 'trainer'
              FROM	major,account,course
              WHERE	course.state = 'approved'
	          	AND   course.trainerId= account.id
	          	AND		course.majorId= major.id
							AND 	course.id
								NOT IN(SELECT distinct courseId FROM learning
												INNER JOIN course ON learning.courseId=course.id WHERE course.state='approved')";


			$stmt = $conn->prepare($sql);

			//$stmt->bindParam(':id',$id);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			if($stmt->rowCount()){
				return $result;
			}else{
				return NULL;
			}
		}catch(PDOException $e){
			return false;
		}
	}

	public function getNumberOfTrainee()	{
		try {
			$conn = $this->connect();
			$sql  = "SELECT count(id) 'num' from trainee";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result[0]['num'];
			}catch(PDOException $e){
				return 0;
			}
	}

	public function getDefaultCourse($numOfTrainee){
		try{
			$conn = $this->connect();
			$sql = "SELECT	distinct course.id 'id', major.name 'major',  account.fullName 'trainer'
							FROM	major,account,course
							WHERE	course.required = 1
							AND   	course.trainerId= account.id
							AND		course.majorId= major.id
							AND 	course.id
							IN (select distinct courseId from learning
							where status='learning'
							and courseId in (select id from course where required=1)
							group by courseId
							having count(status)=:numOfTrainee)";

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':numOfTrainee',$numOfTrainee);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			if($stmt->rowCount()){
				return $result;
			}else{
				return NULL;
			}
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
			WHERE learning.courseId =:id" ;
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}

	public function getAvailableCourse($traineeId){
		try{
			$conn = $this->connect();

			$sql="SELECT 			course.id,
									major.name,
									account.fullName,
									course.startDate,
									course.endDate
			from  			course, major, account
			where 			course.majorId = major.id
			and 				account.id=course.trainerId
			and 				course.state ='approved'
			and 				course.id not in (SELECT courseId from  learning where traineeId=:traineeId)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':traineeId',$traineeId);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}

	public function checkConflictCourse($traineeId, $startTime, $endTime){
		try{
			$conn = $this->connect();

			$sql = "SELECT lesson.id, lesson.roomId, lesson.startTime, lesson.endTime
							FROM course, learning, lesson
							WHERE lesson.courseId=learning.courseId
							AND learning.courseId=course.id
							AND learning.traineeId = :traineeId
							AND		((lesson.startTime<=:startTime and lesson.endTime>=:startTime)
									or(lesson.startTime<=:endTime and lesson.endTime>=:endTime)
									or(lesson.startTime>=:startTime and lesson.endTime<=:endTime))";

			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':traineeId'=>$traineeId,':startTime'=>$startTime, ':endTime'=>$endTime));
			// $result = $stmt->fetchAll();
			return $stmt->rowCount();
		}catch(PDOException $e){
			return false;
		}
	}

	public function checkCourseCondition($courseId)
	{
		try{
			$conn = $this->connect();

			$sql = "SELECT status
							FROM learning
							WHERE courseId= :courseId";

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':courseId',$courseId);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;

		}
	}

	public function registerCourse($traineeId, $courseId){
		try{
			$conn = $this->connect();
			$sql="INSERT INTO learning(traineeId, courseId, status)
						VALUES (:traineeId, :courseId, 'waiting')";

			$stmt = $conn->prepare($sql);
			$stmt->execute(array(':traineeId'=>$traineeId,':courseId'=>$courseId));
				return true;
	    }catch(PDOException $e){
	    	return false;//error 404
	    }
	}

	public function deleteRoom($id) {
		try{
			$conn = $this->connect();
			$sql = "UPDATE course SET roomId = NULL WHERE roomId = :id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
			return true;
		}catch(PDOException $e){
			return false;
		}
		return false;
	}

	public function deleteMajor($id){
		try{
			$conn = $this->connect();
			$sql = "UPDATE course SET majorId = NULL WHERE majorId = :id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
			return true;
		}catch(PDOException $e){
			return false;
		}
		return false;
	}

	//HUNTER
	public function selectConflictLessons($courseId, $lessonId, $trainerId, $roomId, $startTime, $endTime){
		try{
			$conn = $this->connect();

			$sql = "SELECT courseId, major.name 'major', account.fullName 'trainer', roomId 'room', startTime, endTime, course.state
					FROM lesson, course, major, account
					WHERE
						lesson.courseId=course.id AND
						course.majorId= major.id AND
						course.trainerId= account.id AND
						(roomId=:roomId or course.trainerId=:trainerId) AND
						((startTime<=:startTime AND endTime>=:startTime) OR (startTime<=:endTime AND endTime>=:endTime) OR
						(startTime>=:startTime AND endTime<=:endTime)) AND course.state NOT IN ('closed', 'reopened')";
			$param=[];
			$param['roomId']= $roomId;
			$param['trainerId']= $trainerId;
			$param['startTime']= $startTime;
			$param['endTime']= $endTime;
			if($courseId){
				$sql.=" AND course.id<>:courseId";
				$param['courseId']= $courseId;
			}
			if($lessonId){
				$sql.=" AND lesson.id<>:lessonId";
				$param['lessonId']= $lessonId;
			}
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			// $stmt->execute();
			$stmt->execute($param);
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}


	//HUNTER
	public function selectConflictTrainees($lessonId, $startTime, $endTime){
		try{
			$conn = $this->connect();
			$sql = "SELECT
						traineeId,
						account.fullName 'traineeName',
						course.id,
						major.name 'major',
						lesson.roomId 'room',
						lesson.startTime,
						lesson.endTime,
						course.state
					FROM
						lesson,
						learning,
						account,
						major,
						course
					WHERE
						learning.traineeId= account.id AND
						learning.courseId= course.id AND
						lesson.courseId= course.id AND
						course.majorId= major.id AND
						course.state NOT IN ('closed', 'reopened') AND
						lesson.id<>:lessonId AND
						(traineeId in(SELECT traineeId from learning, course, lesson where lesson.id=:lessonId AND learning.courseId= course.id AND lesson.courseId= course.id)) AND
						((startTime<=:startTime AND endTime>=:startTime) OR (startTime<=:endTime AND endTime>=:endTime) OR (startTime>=:startTime AND endTime<=:endTime));";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':lessonId'=>$lessonId, ':startTime'=>$startTime, ':endTime'=>$endTime));
			$result=$stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}

	//HUNTER
	public function insertCourse($course){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO course VALUES (0, :majorId, :trainerId, :state, 0, :numOfLessons, :startDate, :endDate)";
			$stmt = $conn->prepare($sql);
			$stmt->execute(
				array(
					':majorId'=>$course['majorId'],
					':trainerId'=>$course['trainerId'],
					':state'=>$course['state'],
					':numOfLessons'=>$course['numOfLessons'],
					':startDate'=>$course['startDate'],
					':endDate'=>$course['endDate'],
				)
			);
			return $conn->lastInsertId();
	    }catch(PDOException $e){
	    	return false;
	    }
	}

	//HUNTER
	public function updateCourse($course)
	{
		try {
			$conn = $this->connect();
			$sql= "UPDATE course SET id=:id";
			if(isset($course['majorId'])) $sql.=", majorId=:majorId";
			if(isset($course['trainerId'])) $sql.=", trainerId=:trainerId";
			if(isset($course['state'])) $sql.=", state=:state";
			if(isset($course['required'])) $sql.=", required=:required";
			if(isset($course['numOfLessons'])) $sql.=", numOfLessons=:numOfLessons";
			if(isset($course['startDate'])) $sql.=", startDate=:startDate";
			if(isset($course['endDate'])) $sql.=", endDate=:endDate";
			$sql.=" WHERE id=:id";

			$stmt = $conn->prepare($sql);
			 return $stmt->execute($course);
		} catch (PDOException $e) {
				return false;
		}
	}

	public function setDefaultCourse($courseId){
		try {
			$conn = $this->connect();
			$sql1 =	"UPDATE course SET required = '1', state= 'opened' WHERE id = :courseId";
			$sql	= "INSERT INTO learning (learning.courseId, learning.status, learning.traineeId)
							SELECT :courseId,'learning', id FROM trainee";
			$stmt = $conn->prepare($sql);
			$stmt1 = $conn->prepare($sql1);
			$stmt->bindParam(':courseId', $courseId);
			$stmt1->bindParam(':courseId', $courseId);
			$stmt->execute();
			$stmt1->execute();
			return true;
	    }catch(PDOException $e){
	    	return false;//error 404
	    }
	}

	public function updateStateCourse($id, $state)
	{
		try {
			$conn = $this->connect();
			$sql  = "	UPDATE course
								SET course.state = :state
								WHERE id=:id";

			$stmt = $conn->prepare($sql);
			$stmt->execute(array(':state'=>$state, ':id'=>$id));
				return true;
		} catch (PDOException $e) {
				return false;
		}
	}

	public function learningTrainee($id)
	{
		try {
			$conn = $this->connect();
			$sql  = "	UPDATE learning
						SET learning.status = 'learning'
						WHERE courseId= :id";

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
				return true;
		} catch (PDOException $e) {
				return false;
	}
}
	public function getDetailScheduleByCourseId($id){
		try{
			$conn = $this->connect();

			$sql="SELECT
							course.roomId,
							course.startTime,
							course.endTime
				from course where id=:id";
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
	public function getRegisteredCourse($traineeId){
		try{
			$conn=$this->connect();
			$sql="SELECT 	learning.courseId,
							major.name,
							account.fullName,
							learning.status
				  	from  learning, course, major, account
          	where learning.courseId=course.id
          	and   course.majorId = major.id
				  	and 	account.id=course.trainerId
				  	and 	learning.status='waiting'
				  	and 	learning.traineeId=:traineeId";
		    $stmt = $conn->prepare($sql);
		    $stmt->bindParam(':traineeId',$traineeId);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}

	}


	public function checkReopenConflict($courseId, $startTime, $endTime){
		try{
			$conn = $this->connect();
			$sql= "SELECT 	DISTINCT 	course.id,
											course.majorId,
											course.trainerId,
											course.roomId,
											course.startTime,
											course.endTime,
											learning.traineeId
					FROM  course	INNER JOIN 	learning  ON course.id = learning.courseId

					WHERE 	traineeId		IN (SELECT 	traineeId	FROM 	learning	WHERE 	courseId = :courseId AND 	learning.status='failed')

					AND ((course.startTime<=:startTime and course.endTime>=:startTime)
					OR	(course.startTime<=:endTime	 and course.endTime>=:endTime)
					OR	(course.startTime>=:startTime and course.endTime<=:endTime))";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':courseId'=>$courseId,':startTime'=>$startTime, ':endTime'=>$endTime));
			return !($stmt->rowCount());
		}catch(PDOException $e){
			return false;
		}
	}


	public function getConflictOfTrainee($courseId, $lessonId, $startTime, $endTime){
		try{
		$conn = $this->connect();
		$sql	= "SELECT course.id, learning.traineeId,  major.name, lesson.roomId,
								  lesson.startTime, lesson.endTime,  course.state
							from course, major, learning, lesson
							where course.majorId=major.id
							AND lesson.courseId=learning.courseId
							AND learning.courseId=course.id
							AND course.state not in ('closed', 'pending')
							AND learning.traineeId in(SELECT traineeId FROM learning ";

				$param=[];
				$param['startTime']=$startTime;
				$param['endTime']=$endTime;
				if($lessonId==NULL){
					$sql.="WHERE courseId = :courseId AND learning.status='failed')
					AND	((lesson.startTime<=:startTime AND lesson.endTime>=:startTime)
					OR (lesson.startTime<=:endTime AND lesson.endTime>=:endTime)
					OR	(lesson.startTime>=:startTime AND lesson.endTime<=:endTime))";
					$param['courseId']= $courseId;
				}else {
					$sql.="WHERE courseId = :courseId)
					AND	((lesson.startTime<=:startTime AND lesson.endTime>=:startTime)
					OR (lesson.startTime<=:endTime AND lesson.endTime>=:endTime)
					OR	(lesson.startTime>=:startTime AND lesson.endTime<=:endTime))";
					$param['courseId']= $courseId;
				}
		 		$stmt = $conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$stmt->execute($param);
				$result=$stmt->fetchAll();
				return $result;
		}catch(PDOException $e){
			return false;
		}
	}

	public function getConflictOfTrainer($trainerId,$startTime,$endTime){
		try{
			$conn = $this->connect();
			$sql = "SELECT course.id, major.name, roomId, startTime, endTime, state
							from course, major
							where majorId=major.id and state not in ('closed', 'pending') and trainerId=:trainerId
							AND	((course.startTime<=:startTime AND course.endTime>=:startTime)
				   		OR 	(course.startTime<=:endTime AND course.endTime>=:endTime)
				   		OR	(course.startTime>=:startTime AND course.endTime<=:endTime))" ;
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':trainerId'=>$trainerId,':startTime'=>$startTime, ':endTime'=>$endTime));
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}
	public function getConflictOfRoom($roomId,$startTime,$endTime){
		try{
			$conn = $this->connect();
			$sql = "SELECT course.id, major.name, account.fullName, course.startTime, course.endTime, course.state from course, account, major
				where course.majorId=major.id
				and account.id=course.trainerId
				and course.state not in  ('closed', 'pending')
				and course.roomId=:roomId
				AND	((course.startTime<=:startTime AND course.endTime>=:startTime)
		   		OR (course.startTime<=:endTime AND course.endTime>=:endTime)
		   		OR	(course.startTime>=:startTime AND course.endTime<=:endTime))";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':roomId'=>$roomId,':startTime'=>$startTime, ':endTime'=>$endTime));
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}

	public function deleteCourse($courseId)
	{
		try{
			$conn = $this->connect();
			$learning = new Learning();
			$sql = "DELETE from course WHERE id = :courseId";
			$sql1 = "DELETE from lesson WHERE courseId = :courseId";
			$stmt = $conn->prepare($sql);
			$stmt1 = $conn->prepare($sql1);
			$stmt->bindParam(':courseId',$courseId);
			$stmt1->bindParam(':courseId',$courseId);
			if($stmt1->execute()){
				if($learning->deleteLearning($courseId)){
					return $stmt->execute();
				}else{
					return false;
				}	
			}else{
				return false;
			}
			
		}catch(PDOException $e){
			return false;
		}
	}
	public function insertLearning($newId,$oldId){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO learning(learning.courseId, learning.status, learning.traineeId)
					SELECT $newId, 'learning', learning.traineeId
					FROM learning WHERE learning.courseId=:oldId
					and learning.status='failed'";
			$stmt = $conn->prepare($sql);
			$stmt->execute(
				array(
					':oldId'=>$oldId
				)
			);
			return true;
	    }catch(PDOException $e){
	    	return false;
	    }
	}
	public function getSuggestNewCourse($id){
		try{
			$conn=$this->connect();
			$sql="SELECT distinct type, content from new_notification where sender = :id";
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


	public function countTotalCourse()
	{
		try{
			$conn = $this->connect();
			$sql = "SELECT count(*) as total FROM course
					where majorId is not NULL";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			if($result){
				return $result[0]['total'];
			}else{
				return false;
			}
		}catch(PDOException $e){
			return false;
		}
	}

	public function countTimeByStatus($status)
	{
		try{
			$conn = $this->connect();
			$sql = "SELECT m.name, count(l.traineeId) as num
					from course c
					inner join learning l on l.courseId = c.id
					inner join major m on c.majorId = m.id
					where l.status = :status
					group by m.name";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':status',$status);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			if($result){
				foreach ($result as $key => $value) {
					$rs[$value['name']] = $value['num'];
				}
				return $rs;
			}else{
				return false;
			}
		}catch(PDOException $e){
			return false;
		}
	}
	public function getMajorInCourse()
	{
		try{
			$conn = $this->connect();
			$sql = "SELECT distinct m.name FROM course c
					inner join learning l on l.courseId = c.id
					inner join major m on c.majorId = m.id";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();

			if($result){
				foreach ($result as $key => $value) {
					$rs[$value['name']] = true;
				}
				return $rs;
			}else{
				return false;
			}
		}catch(PDOException $e){
			return false;
		}
	}
//
	public function getNumberOfFailer($courseId){
		try {
			$conn = $this->connect();
			$sql  = "SELECT count(traineeId) 'num' FROM learning WHERE courseId=:courseId and status='failed'";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':courseId',$courseId);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result[0]['num'];
	    }catch(PDOException $e){
	    	return 0;
	    }
//
	}
	public function removeDefaultCourse($courseId){
		try{
			$conn = $this->connect();
			$sql  = "DELETE from learning where courseId=:courseId";
			$sql1 = "UPDATE course set required='0', state='approved' where id=:courseId";
			$stmt = $conn->prepare($sql);
			$stmt1= $conn->prepare($sql1);
			$stmt->bindParam(':courseId',$courseId);
			$stmt1->bindParam(':courseId',$courseId);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt1->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$stmt1->execute();
			return true;
		}catch(PDOException $e){
	    	return 0;
	    }
	}
	public function getCourseForChangingTime($courseId, $dayOfWeek){
        try{
			$conn = $this->connect();
			$sql = "SELECT course.id 'id', numOfLessons, startDate, endDate, startTime, endTime, roomId
					from course, lesson
					where course.id=:courseId and dayOfWeek=:dayOfWeek and course.id= lesson.courseId ";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':courseId'=>$courseId, ':dayOfWeek'=>$dayOfWeek));
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}

}
