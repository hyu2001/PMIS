<?php
session_start();
include("month3.php");
include("connectsql.php");

//清空篩選條件
$_SESSION['filtered_member'] = array('0');
$_SESSION['filtered_dept'] = array(0);
$_SESSION['filtered_state'] = array(0);
$_SESSION['filtered_daterange'] = "null";

$_SESSION['current_member'] = array();
$_SESSION['current_dept'] = array();
$_SESSION['current_state'] = array();
$_SESSION['current_daterange'] = "null";

// header("Location:../welcome.php");
$url = "../welcome.php"; 
echo "<script type='text/javascript'>";
echo "window.location.href='$url'";
echo "</script>";
?>