<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Room.php';

class RoomController extends Controller{
	public function getRoom () {
		$room = new Room();
		$data['room'] = $room->getRoom();
		$this->render('roomManagement',$data);
		
	}
	public function getRoomById(){
		$room= new Room();
		$data['detail'] = $room->getRoomById($_GET['id']);
		echo json_encode($data['detail']);
	}
	public function postUpdateRoom ()
	{
		$data=[];
		$room = new Room();
		$args['id'] = !empty($_POST['updateRoomId']) ? $_POST['updateRoomId'] : false;
		$args['capacity'] = !empty($_POST['updateRoomCapacity']) ? $_POST['updateRoomCapacity'] : false;
		$args['quality'] = !empty($_POST['updateRoomQuality']) ? $_POST['updateRoomQuality'] : false;

		foreach ($args as $key => $arg) {				
			if($arg === false){
				$data['error'] = "[ERROR] Have empty field !";
				echo json_encode($data);
				return;					
			}
		}		

		$rs = $room->editRoom($args);		
		if($rs==true) {
			$data['success'] = 'You updated successfully !';					
		} else{
			$data['error'] = "Can't update room!";
		}
		echo json_encode($data);
	}


	public function postAddRoom ()
	{
		$data=[];
		$room = new Room();
		$args['id'] = !empty($_POST['roomId']) ? $_POST['roomId'] : false;
		$args['capacity'] = !empty($_POST['roomCapacity']) ? $_POST['roomCapacity'] : false;
		$args['quality'] = !empty($_POST['roomQuality']) ? $_POST['roomQuality'] : false;

		foreach ($args as $key => $arg) {				
			if($arg === false){
				$data['error'] = "[ERROR] Have empty field !";
				echo $data;
				return;					
			}
		}		
		$rs = $room->addRoom($args);		
		if($rs==1) {
			$data['success'] = '[SUCCESS] You added successfully a new room !';					
		} elseif ($rs==23000){
			$data['error'] = '[ERROR] This room already existed !';
		}else{ 
			$data['error'] = "[ERROR] Can't add room!";
		}
		echo json_encode($data);
	}

	public function postDeleteRoom()
	{
		$data=[];
		$room= new Room();
		if($room->deleteRoom($_POST['id'])) $data["success"]= "Delete room success !";
		else $data["error"]= "Delete room failed !";
		echo json_encode($data);
	}
}

?>
