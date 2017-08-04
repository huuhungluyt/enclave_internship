<?php
$_SESSION["loginUser"]="trainer";
include($_SERVER['DOCUMENT_ROOT']."/training_course/views/inc/_header.php");
echo $_SERVER['QUERY_STRING'];
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/training_course/views/inc/_footer.php");?>