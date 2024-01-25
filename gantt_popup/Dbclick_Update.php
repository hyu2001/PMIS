<?php
session_start();
include("../connectsql.php");
include("../lib/mark.php");
// 獲取POST請求中的資料
$inputJSON = file_get_contents('php://input');
// 解碼JSON
$data = json_decode($inputJSON, true);

$p_id = $data['pid'];
$p_state = $data['status'];

$sql = "SELECT * FROM project WHERE p_id = $p_id";
$rs = mysqli_query($conn,$sql);
$rst = mysqli_fetch_array($rs);
$edit=array(
    "name1"=>null,
    "name2"=>null,
    "dept1"=>null,
    "dept2"=>null,
    "state1"=>$rst['p_state'],
    "state2"=>$p_state,
    );
//mark紀錄
MarkUpdateProject($_SESSION['UID'],$p_id,$edit);

$sql = "UPDATE `project` SET `p_state`='$p_state' WHERE `p_id`= $p_id ";
mysqli_query($conn,$sql);

//取得更新前的state
$old_state = $rst['p_state'];
if($old_state == 5){  //若更新前狀態為urgent，那就把所有更新，不要加條件(不然子任務都全都更新不了)
    $add_condition = " ";
}else{  
    $add_condition = "AND task_status != 5";
}

//把子任務的status更新成跟父專案一樣(除了任務本身為urgent的)
$sql = "UPDATE task SET task_status = (
        SELECT p_state FROM project WHERE p_id = $p_id
        )WHERE t_project = $p_id ".$add_condition;
mysqli_query($conn,$sql);

//更新子milesone的status
$sql = "UPDATE milestone SET task_status = (
    SELECT p_state FROM project WHERE p_id = $p_id
    )WHERE mst_project = $p_id ".$add_condition;
mysqli_query($conn,$sql);

?>
