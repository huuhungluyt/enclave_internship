<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';	
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Major.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
class HomeController extends Controller{
	public function index () {	
		$course = new Course();
		$major = new Major();
		$totalCourse = $course->countTotalCourse();
		$temp = $major->countCourseOfMajor();
		$temp1 = $major->getNameAllMajor();
		if(!($temp && $totalCourse && $temp1)){

		}
		$i = 0;
		foreach ($temp as $item) {
			$a[$item['name']] = 1;
			$tem = (intval($item['numberCourse'])*100)/intval($totalCourse);
			$dataPoints[$i]['y'] = intval($tem);
			$dataPoints[$i]['indexLabel'] = $item['name'].' {y}%';	
			$dataPoints[$i]['legendText'] = $item['name'];	
			$i++;		
		}
		foreach ($temp1 as $tem) {
			if(!isset($a[$tem['name']])) {		
				$dataPoints[$i]['y'] = 0;					
				$dataPoints[$i]['legendText'] = $tem['name'];	
				$i++;
			}			
		}		
		$j = 0;
		$arrayStatus = array('waiting', 'learning', 'passed', 'failed');
		foreach ($arrayStatus as $status) {
			if($temp3 = $course->countTimeByStatus($status)){
				$sta[$status] = $temp3;
			}
		}		

		$majorInCourse = $course->getMajorInCourse();
		$data['majorInCourse'] = $majorInCourse;
		$data['status'] = $sta;
		$data['dataPoints'] = $dataPoints;
		// dump($data);
		$this->render('home',$data);
	}		
}	
