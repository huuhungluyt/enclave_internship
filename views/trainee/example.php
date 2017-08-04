<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';
$_SESSION["loginUser"]="trainee";
// include("../inc/_header.php");



$abc= getEnumOf('course', 'state');
foreach($abc as $t){
  echo $t;
}    
?>
