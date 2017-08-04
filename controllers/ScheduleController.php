<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Trainee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Trainer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Notification.php';

class ScheduleController extends Controller {
	
    public function getSchedule() {

    	if(isset($_SESSION['trainee_infor'])) {
    		$trainee = new Trainee();
    		$trainee_id = $_SESSION['trainee_infor']['id'];
	    	$data['course'] = $trainee->getSchedule($trainee_id);
	    	$this->render('trainee/schedule', $data);
    	}else{
    		$trainer = new Trainer();
			$trainer_id = $_SESSION['trainer_infor']['id'];
			$data = [];
			$data['course'] = $trainer->getSchedule($trainer_id);
			$this->render('trainer/schedule',$data);

    	} 
   	
    }
}
    
