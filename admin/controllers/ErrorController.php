<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';	
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/CVarDumper.php';

class ErrorController extends Controller{
	public function error404 () {
		$this->render('error-404');			
	}		
}	
