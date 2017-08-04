<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/framework/Controller.php';
session_start();
//Controller and Function are default
$ctr = 'home';
$act = 'index';
$temp = false;
// Check and get Controller and Function from Request
$redirect = new controller();
if (isset($_GET['ctr'])) {
    $ctr = $_GET['ctr'];
}
if($ctr != 'error') {
    if(!isset($_SESSION['admin_infor'])) {
        $redirect->redirect('ctr=auth&act=getLogin','');
    }
}

$controllerName = ucfirst($ctr) . 'Controller'; // UserController

if (file_exists(__DIR__ . '/controllers/'.$controllerName.'.php')) {
    include_once(__DIR__ . '/controllers/'.$controllerName.'.php');
    if (class_exists($controllerName)) {
        
        
        if(isset($_GET['act'])){
            $act = $_GET['act'];
        }

        $controller = new $controllerName();
        if (method_exists($controller, $act)) {
          $controller->$act();
        } else {
          // redirect to 404
          $redirect->redirect('ctr=error&act=error404');
        }
    } else {
        // redirect to 404
        $redirect->redirect('ctr=error&act=error404');
    }
} else {
    // redirect to 404
    $redirect->redirect('ctr=error&act=error404');
}