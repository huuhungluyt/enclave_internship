<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Trainer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';

/**
* 
*/
class Major extends Model
{
	public function getAllMajorVer2(){
		try{
			$conn = $this->connect();
			$sql = "SELECT id, name, description from major";	
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}

	public function getAllMajor(){
		try{
			$conn = $this->connect();
			$sql = "SELECT id, name, description from major where id in (select majorId from trainer)";			
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}
	

	public function selectMajorById($majorId){
		try{
			$conn = $this->connect();
			$sql = "SELECT * from major where id=:majorId";			
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute(array(':majorId'=>$majorId));
			$result = $stmt->fetchAll();
			if($stmt->rowCount()) return $result[0];
			else return false;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function editMajor($args = []){
		try {
			$conn = $this->connect();
			$sql  = "UPDATE major 
				 	SET name=:name,description =:description					
				 	WHERE id=:id";
			$stmt = $conn->prepare($sql);			
			$stmt->bindParam(':name',$args['name']);
			$stmt->bindParam(':description',$args['description']);
			$stmt->bindParam(':id',$args['id']);
			$stmt->execute();
			
			return true;
	    }catch(PDOException $e){	
	    	return false;
	    }
	}
	public function addMajor($args=[]){
		try {
			$conn = $this->connect();
			$sql  = "INSERT INTO major (id,name,description) VALUES (0, :name, :description)";

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':name',$args['name']);
			$stmt->bindParam(':description',$args['description']);
			return $stmt->execute();
	    }catch(PDOException $e){
	    	//echo $e;
	    	return $e->getCode();
	    }

	}

	public function isExistedMajor($major){
		try {
			$conn = $this->connect();
			$sql  = "SELECT count(id) 'num' FROM major WHERE name=:name and id<>:id";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$param=[];
			$param['name']= $major['name'];
			$param['id']= isset($major['id'])?$major['id']:'xxx';
			$stmt->execute($param);
			$result = $stmt->fetchAll();
			return $result[0]['num'];
	    }catch(PDOException $e){
	    	return 0;
	    }
	}

	public function isTeaching($majorId){
		try{
			$conn = $this->connect();
			$sql="SELECT * from course WHERE majorId=:majorId and state<>'closed'";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':majorId',$majorId);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
            return $stmt->rowCount();
		}catch(PDOException $e){
			return false;
		}
		return false;
	}
	public function deleteMajor($majorId){
		try{
			$conn = $this->connect();
			$course= new Course();
			$trainer=new Trainer();
			if($this->isTeaching($majorId)){
				return false;
			}
			$course->deleteMajor($majorId);
			$trainer->deleteMajor($majorId);
			// if($course->deleteMajor($majorId)&&$trainer->deleteMajor($majorId)){
				$sql = "DELETE from major WHERE id = :majorId";			
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':majorId',$majorId);
				return $stmt->execute();
			// } 
			
		}catch(PDOException $e){
			return false;
		}
		return false;
	}
	public function countCourseOfMajor()
	{
		try{
			$conn = $this->connect();
			$sql = "SELECT m.name, COUNT(*) as numberCourse
					FROM major m
					JOIN course c ON c.majorId = m.id
					GROUP BY m.name";
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);	
			$stmt->execute();		
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
	public function getNameAllMajor()
	{
		try{
			$conn = $this->connect();
			$sql = "SELECT name from major";			
			$stmt = $conn->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}
	public function getTop4PopularMajor()
	{
		try{
			$conn = $this->connect();
			$sql = "SELECT m.id,m.name,m.description,m.image, count(m.id) as dem FROM major m
					inner join course c on c.majorId = m.id
					group by m.id
					order by dem DESC, m.name
					limit 4";			
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