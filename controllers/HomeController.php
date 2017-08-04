<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/framework/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/CVarDumper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Major.php';
class HomeController extends Controller {
	
    public function index() {
    	$major = new Major();
    	$data['top'] = $major->getTop4PopularMajor();
    	// dump($data);
    	$this->render('home',$data);
    }
}
