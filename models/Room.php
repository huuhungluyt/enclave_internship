<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';

/**
* 
*/
class Room extends Model
{

	public function getRoom(){
		try{
			$conn = $this->connect();
			$sql = "SELECT * from room";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}
	public function getRoomById($id){
		try{
			$conn = $this->connect();
			$sql = "SELECT * from room where id=:id";
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
	public function editRoom($args = []){
		try {
			$conn = $this->connect();
			$sql  = "UPDATE room 
				 	SET capacity=:capacity,quality =:quality					
				 	WHERE id=:id";
			$stmt = $conn->prepare($sql);			
			$stmt->bindParam(':capacity',$args['capacity']);
			$stmt->bindParam(':quality',$args['quality']);
			$stmt->bindParam(':id',$args['id']);
			$stmt->execute();
			
			return true;
	    }catch(PDOException $e){	
	    	return false;
	    }
	}


	public function addRoom($args=[]){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO room (id,capacity,quality)"
					." VALUES (:id,:capacity,:quality)";

			$stmt = $conn->prepare($sql);

			$stmt->bindParam(':id',$args['id']);
			$stmt->bindParam(':capacity',$args['capacity']);
			$stmt->bindParam(':quality',$args['quality']);
			
			return $stmt->execute();
	    }catch(PDOException $e){
	    	return $e->getCode();
	    }
	}


	public function isUsingRoom($id){
		try{
			$conn = $this->connect();
			$sql="SELECT * from course WHERE roomId=:id and state<>'closed'";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id',$id);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
            return $stmt->rowCount();
		}catch(PDOException $e){
			return false;
		}
		return false;
	}


	public function deleteRoom($id) {
		try{
			$conn = $this->connect();
			$course= new Course();
			if($this->isUsingRoom($id)){
				return false;
			}
			if($course->deleteRoom($id)){
				$sql = "DELETE from room WHERE id = :id";			
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':id',$id);

				return $stmt->execute();
			}
		}catch(PDOException $e){
			return false;
		}
		return false;
	}
	
}