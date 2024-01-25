<?php
session_start();
include("get_pid.php");
include("connectsql.php");

//設定預設篩選值
$dptid = array(0);    //部門預設為0->不選
$uid = array('0');      //成員預設為0->不選
$gid = $_POST['gid']; //部門預設用當前選擇的group
$state = array('0');           //狀態預設為1->in-progress

//若條件有被設置的話，就更改其值
if(isset($_POST['dept'])) 
{
    $dptid = $_POST['dept'];
}
if(isset($_POST['member'])) 
{
    $uid = $_POST['member'];
} 
if(isset($_POST['state']))
{  
    $state = $_POST['state'];
}

//判斷時間filter有沒有被設置,如果有的話才要設置$_SESSION['current_daterange']
$daterange = $_POST['datefilter'];
echo $daterange;
if($daterange == '')
{
    $daterange = "null";
}

if($daterange == "null"){
    $_SESSION['filtered_daterange'] = "null";
    $_SESSION['current_daterange'] = "null";
}else{
    $_SESSION['filtered_daterange'] = $daterange;
    $_SESSION['current_daterange'] = $daterange;
}


//將當前filter的選擇存在SESSION中
$_SESSION['filtered_member'] = $uid;
$_SESSION['filtered_dept'] = $dptid;
$_SESSION['filtered_state'] = $state;
$_SESSION['current_group'] = $gid;
if($gid==0)
{
    $sql = "SELECT u_id FROM user_info 
            INNER JOIN user_group ON user_info.u_group = user_group.g_id 
            WHERE g_outside = 'n'";
    $rs = mysqli_query($conn,$sql);
    $total_member = mysqli_num_rows($rs);
    if(count($uid) == $total_member)
    {
        $_SESSION['current_member'] = array('0');
    }
    else
    {
        $_SESSION['current_member'] = $uid;
    }
}
else
{
    $_SESSION['current_member'] = $uid;
}
$_SESSION['current_dept'] = $dptid;
$_SESSION['current_state'] = $state;
$pid = get_pid($gid,$dptid,$_SESSION['current_member'],$state,$_SESSION['current_daterange']); //去取得符合條件的pid

if($pid == null) 
{
    $pid = array(0);
}
$_SESSION['current_project'] = $pid;

$url = "../welcome.php?";
header('Location:' . $url);
exit;

?>