<?php
session_start();
include("get_pid.php");


$_SESSION['filtered_group'] = $_GET['group'];
$_SESSION['filtered_dept'] = array(0);
$_SESSION['filtered_state'] =array(0);
$_SESSION['filtered_daterange'] = "null";

$_SESSION['current_group'] = $_GET['group'];
$_SESSION['current_dept'] = array();
$_SESSION['current_state'] = array();
$_SESSION['current_daterange'] = "null";

//設置filter預選好的group
if($_GET['group']==0)  //如果group選擇"全部",就選擇全部的"user id"
{
    $sql = "SELECT u_id FROM user_info WHERE u_email IS NOT NULL";
    $rs = mysqli_query($conn,$sql);
    while($rst = mysqli_fetch_array($rs))
    {
        $user_id[] = $rst['u_id'];
    } 
    $_SESSION['filtered_member'] = $user_id;
    $_SESSION['current_member'] = array();
    $pid = get_pid($_SESSION['current_group'],$_SESSION['current_dept'],$_SESSION['current_member'],$_SESSION['current_state'],$_SESSION['current_daterange']);
}
else    //如果group選擇特定一個,就選擇該group的"user id"
{
    $sql = "SELECT u_id FROM user_info WHERE u_group = {$_GET['group']}";
    $rs = mysqli_query($conn,$sql);
    while($rst = mysqli_fetch_array($rs))
    {
        $user_id[] = $rst['u_id'];
    } 
    if($user_id)
    {
        $_SESSION['filtered_member'] = $user_id;
        $_SESSION['current_member'] = $user_id;
        $pid = get_pid($_SESSION['current_group'],$_SESSION['current_dept'],$_SESSION['current_member'],$_SESSION['current_state'],$_SESSION['current_daterange']);
    }
}

if($pid == null){     //若找不到符合條件的pid
    $pid = array(0);  //把pid設為0(至少要送一個值過去show_board)
}

$_SESSION['current_project'] = $pid; 
$url = "../welcome.php";
// header('Location:' . $url);
echo "<script type='text/javascript'>";
echo "window.location.href='$url'";
echo "</script>";
exit;

?>