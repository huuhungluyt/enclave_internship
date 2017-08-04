<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';

/**
* 
*/
class Lesson extends Model
{
    public function insertLesson($lesson){
        try {
			$conn = $this->connect();
			$sql  = "INSERT INTO lesson VALUES (0, :courseId, :roomId, :startTime, :endTime, :dayOfWeek)";
			$stmt = $conn->prepare($sql);
			$stmt->execute(
				array(
					':courseId'=>$lesson['courseId'],
					':roomId'=>$lesson['roomId'],
					':startTime'=>$lesson['startTime'],
                    ':endTime'=>$lesson['endTime'],
					':dayOfWeek'=>$lesson['dayOfWeek']
				)
			);
			return $conn->lastInsertId();
	    }catch(PDOException $e){
	    	return false;
	    }
    }

	public function selectLessonById($lessonId){
		try{
			$conn = $this->connect();
			$sql = "SELECT * from lesson where id=:lessonId";			
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':lessonId'=>$lessonId));
			$result = $stmt->fetchAll();
			if($result) return $result[0];
			else return false;
		}catch(PDOException $e){
			return false;
		}
	}


	public function updateLesson($lesson){
		try {
			$conn = $this->connect();
			$sql  = "UPDATE lesson SET id=:id";
			if(isset($lesson['courseId'])){
				$sql.=", courseId=:courseId";
			}
			if(isset($lesson['roomId'])){
				$sql.=", roomId=:roomId";
			}
			if(isset($lesson['startTime'])){
				$sql.=", startTime=:startTime";
			}
			if(isset($lesson['endTime'])){
				$sql.=", endTime=:endTime";
			}
			if(isset($lesson['dayOfWeek'])){
				$sql.=", dayOfWeek=:dayOfWeek";
			}
			$sql.= " WHERE id=:id";
			$stmt = $conn->prepare($sql);			
			return $stmt->execute($lesson);
	    }catch(PDOException $e){	
	    	return false;
	    }
	}
}