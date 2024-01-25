<?php
session_start();
include("../connectsql.php");
include("../lib/mark.php");
include("../lib/normallib.php");
include("../lib/rowDrag.php");

// 獲取POST請求中的資料
$inputJSON = file_get_contents('php://input');
// 解碼JSON
$data = json_decode($inputJSON, true);

$id = $data['id'];
$type = $data['type'];
$parent = $data['parent'];

if($type == "project")  //刪除project
{
    if(strlen($id)>10)
    {
        $sql = "SELECT p_id FROM project WHERE `gantt_id` = '$id'";
        $rs = mysqli_query($conn,$sql);
        $rst = mysqli_fetch_array($rs);
        $p_id = $rst['p_id'];
    }
    else
    {
        $p_id = $id; //去掉前面的't',只留id數字
    }

    $sql = "DELETE FROM `project`  WHERE `project`.`p_id` = '$p_id'";
    //MARK紀錄
    MarkDeleteProject($_SESSION['UID'],$p_id);
    
    if (mysqli_query($conn, $sql)) {
        echo "成功刪除專案<br>";
    } 
    else{
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
else if ($type == "task")  //刪除task
{
    if(substr($id,0,1) != 't')  //剛新增的task沒若沒刷新頁面，id會是gantt隨機產生的一長串id，就靠這組id去找t_id
    {
        $sql = "SELECT * FROM task WHERE `gantt_id` = '$id'";
        $rs = mysqli_query($conn,$sql);
        $rst = mysqli_fetch_array($rs);
        $t_id = $rst['t_id'];
    }
    else
    {
        $t_id = substr($id,1); //去掉前面的't',只留id數字
    }

    //找到order
    $sql = "SELECT row_order FROM task WHERE t_id = $t_id";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    $order = $rst['row_order'];
    //Update order
    orderDelete($parent, $order);

    $sql = "DELETE FROM `task`  WHERE `task`.`t_id` = '$t_id'";
    
    //Mark
    MarkDeleteTask($_SESSION['UID'],$t_id);
    

    if (!mysqli_query($conn, $sql)) 
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    else
    {
        //更新project開始-結束時間
        proj_date_range($parent);
    }
}
else  //刪除milestone
{
    if(substr($id,0,1) != 'm')  //剛新增的milestone沒若沒刷新頁面，id會是gantt隨機產生的一長串id，就靠這組id去找mst_id
    {
        $sql = "SELECT mst_id FROM milestone WHERE `gantt_id` = '$id'";
        $rs = mysqli_query($conn,$sql);
        $rst = mysqli_fetch_array($rs);
        $m_id = $rst['mst_id'];
    }
    else
    {
        $m_id = substr($id,1); //去掉前面的't',只留id數字
    }

    //找到order
    $sql = "SELECT row_order FROM milestone WHERE mst_id = $m_id";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    $order = $rst['row_order'];
    //Update order
    orderDelete($parent, $order);

    $sql = "DELETE FROM `milestone` WHERE `milestone`.`mst_id` = '$m_id'";

    //Mark
    MarkDeleteMilestone($_SESSION['UID'],$m_id);

    if (!mysqli_query($conn, $sql)) 
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    else
    {
        //更新project開始-結束時間
        proj_date_range($parent);
    }
}
?>