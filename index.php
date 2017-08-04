<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
require_once $_SERVER['DOCUMENT_ROOT'] . '/framework/Controller.php';
session_start();

//Controller and Function are default
$ctr = 'home';
$act = 'index';
$temp = false;
// Check and get Controller and Function from Request
if (isset($_GET['ctr'])) {
    $ctr = $_GET['ctr'];
}
if($ctr != 'error') {
    if(!isset($_SESSION['user_infor'])) {
        $ctr = 'auth';
        if(isset($_GET['act']) && ($_GET['act'] == 'postLogin' || $_GET['act'] == 'forgotPassword') ) {
            $act = $_GET['act'];
        }else{
            $act = 'getLogin';
        }
        $temp = true;

    }
}

$controllerName = ucfirst($ctr) . 'Controller'; // UserController

$redirect = new Controller();

if (file_exists(__DIR__ . '/controllers/' . $controllerName . '.php')) {
    include_once(__DIR__ . '/controllers/' . $controllerName . '.php');

    if (class_exists($controllerName)) {
        if($temp == false) {
            if(isset($_GET['act'])){
                $act = $_GET['act'];
            }
        }
        
        $controller = new $controllerName();
        if (method_exists($controller, $act)) {
           // die($ctr. ' ' . $act);
            $controller->$act();
            // die('gfs');
        } else {
            // Don't exist action in class Controller => redirect to 404
            $redirect->redirect('ctr=error&act=error404');
        }
    } else {
        // Don't exist class Controller => redirect to 404
        $redirect->redirect('ctr=error&act=error404');
    }
} else {
    // Don't exist File Controller => redirect to 404
    $redirect->redirect('ctr=error&act=error404');
}