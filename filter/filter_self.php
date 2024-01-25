<?php
session_start();
include("connectsql.php");

$_SESSION['filtered_group'] = 0;
$_SESSION['filtered_member'] = array($_SESSION['UID']);
$_SESSION['filtered_dept'] = array(0);
$_SESSION['filtered_state'] = array(0);
$_SESSION['filtered_daterange'] = "null";
// unset($_SESSION['current_project']); //先清除p_id值

$_SESSION['current_project'] = 0;
$_SESSION['current_group'] = $_SESSION['GROUP'];
$_SESSION['current_member'] = array($_SESSION['UID']);  //先設置current_member=0，這樣到一開始gantt_getdata的才有東西抓
$_SESSION['current_dept'] = array();
$_SESSION['current_state'] = array();
$_SESSION['current_daterange'] = "null";

$uid = $_SESSION['UID'];
//先設置一開始要顯示的project
$sql = "SELECT DISTINCT p_id FROM project 
        INNER JOIN show_all ON project.p_id = show_all.project
        WHERE u_id IN ('$uid') ORDER BY SUBSTR(TRIM(p_name),1,1) ASC";    
$rs = mysqli_query($conn,$sql); 
$pid = array();
while($project = $rs->fetch_assoc())
{
    array_push($pid,$project['p_id']);
};
$_SESSION['current_project'] = $pid;

$url = "../welcome.php";
// header('Location:' . $url);
echo "<script type='text/javascript'>";
echo "window.location.href='$url'";
echo "</script>";
    
exit;
?>