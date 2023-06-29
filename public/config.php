<?php
date_default_timezone_set("Asia/Yangon");
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_database = "marubeni";
ini_set('memory_limit','1G');
set_time_limit(0);
$bd = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Something went wrong");
mysqli_select_db($bd,$mysql_database) or die("Something went wrong");
//echo "TTH Hello";
?>
