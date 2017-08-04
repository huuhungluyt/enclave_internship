<?php
class Controller {

    public function render($viewFile, $data = []) {
        ob_start();
        extract($data, EXTR_SKIP);
        include $_SERVER['DOCUMENT_ROOT'] . '/views/' . $viewFile . '.php';
    }

    public function redirect($url, $auth="") {
        die("<script>window.location.href = 'http://" . $_SERVER['HTTP_HOST'] . $auth . "/index.php?" . $url . "';</script>");
    }

}
