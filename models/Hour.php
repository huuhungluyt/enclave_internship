<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';

/**
* 
*/
class Hour extends Model
{
    public function selectHours(){
       try{
			$conn = $this->connect();
            $sql = "SELECT  *
			        FROM hour";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
		}catch(PDOException $e){
			return false;
		}
    }
}