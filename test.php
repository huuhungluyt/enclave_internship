<?php

echo date("Y-m-d", strtotime("+24hours", strtotime(date("Y-m-d H:i:s"))));
// echo date("Y-m-d", strtotime("+24hours", strtotime("2017-07-23 14:00:00")));
if(strtotime(date("Y-m-d"))==strtotime("2017-07-24")) echo "TRUE";
else echo "FALSE";
?>