<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';

/**
*
*/
class Learning extends Model
{

	public function updateStatus($traineeId,$courseId,$status){
		try {
			$conn = $this->connect();

			$sql  = " UPDATE `learning` "
			."SET status=:status "
			."WHERE traineeId=:traineeId and courseId=:courseId";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':status',$status);
			$stmt->bindParam(':traineeId',$traineeId);
			$stmt->bindParam(':courseId',$courseId);
			$stmt->execute();

			return true;
	    }catch(PDOException $e){
	    	echo $sql;
	    	echo $e;
	    	return false;//error 404
	    }
	}
	public function deleteLearning($courseId)
	{
		try{
			$conn = $this->connect();
			$sql = "DELETE from learning WHERE courseId = :courseId";			
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':courseId',$courseId);
			return $stmt->execute();
		}catch(PDOException $e){
			return false;
		}
	}
}
