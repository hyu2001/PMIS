<?php

session_start();
include("../connectsql.php");
include("../lib/mark.php");
include("../lib/normallib.php");

// 獲取POST請求中的資料
$inputJSON = file_get_contents('php://input');
// 解碼JSON
$data = json_decode($inputJSON, true);

//取出POST資料內容
$id = $data['id'];
$type = $data['type'];
$text = $data['text'];
$project = $data['parent'];
$creator = $_SESSION['UID'];
$assign = $data['owner'];
$department = $data['department'];
$duration = $data['duration'];
$status = $data['status'][0];
$start_day = substr($data['start_day'],0,10);
//因為不知道為甚麼送過來的日期會減1天，所以在這裡處理把天數+1回去
$start_day = date("Y-m-d",strtotime("+1 day",strtotime($start_day))); 
$end_day = substr($data['end_date'],0,10);
//因為不知道為甚麼送過來的日期會減1天，所以在這裡處理把天數+1回去
$end_day = date("Y-m-d",strtotime("+1 day",strtotime($end_day))); 
$update_reason = $data['update_reason'];


if($type == "project")  //編輯project
{
    if(strlen($id)>10)  //剛新增的project沒若沒刷新頁面，id會是gantt隨機產生的一長串id，就靠這組id去找p_id
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

    $sql = "SELECT * FROM project WHERE p_id = $p_id";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);

    //先儲存更新前和更新後的資料準備Mark紀錄
    $edit=array(
        "name1"=>$rst['p_name'],
        "name2"=>$text,
        "dept1"=>$rst['p_department'],
        "dept2"=>$department,
        "state1"=>null,
        "state2"=>null,
        "reason"=>$update_reason
        );
    //Mark紀錄
    MarkUpdateProject($_SESSION['UID'],$p_id,$edit);

    $sql = "UPDATE `project` SET `p_name`='$text', `p_department`='$department' WHERE `p_id`= $p_id";
    mysqli_query($conn,$sql);
}
else if($type == "task")  //編輯task
{
    if(substr($id,0,1) != 't')  //剛新增的task沒若沒刷新頁面，id會是gantt隨機產生的一長串id，就靠這組id去找t_id
    {
        $sql = "SELECT t_id FROM task WHERE `gantt_id` = '$id'";
        $rs = mysqli_query($conn,$sql);
        $rst = mysqli_fetch_array($rs);
        $t_id = $rst['t_id'];
    }
    else
    {
        $t_id = substr($id,1); //去掉前面的't',只留id數字
    }
    
    $sql = "SELECT * FROM task WHERE t_id = $t_id";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);

    //處理status:如果是null，status值就與parent一樣，如果有勾選，就是urgent(value=5)
    if($status != 5){
        $sql2 = "SELECT `p_state` FROM `project` INNER JOIN `task` ON project.p_id = task.t_project WHERE task.t_id = $t_id";
        $rs2 = mysqli_query($conn,$sql2);
        $rst2 = mysqli_fetch_array($rs2);
        $status = $rst2["p_state"];
    }
    //mark紀錄
    $edit=array(
        "name1"=>$rst['t_name'],
        "name2"=>$text,
        "user1"=>$rst['t_user'],
        "user2"=>$assign,
        "date11"=>$rst['t_date1'],
        "date12"=>$start_day,
        "date21"=>$rst['t_date2'],
        "date22"=>$end_day,
        "reason"=>$update_reason
        );
    MarkUpdateTask($_SESSION['UID'],$rst['t_project'],$t_id,$edit);

    $sql = "UPDATE `task` 
            SET 
           `t_name`= '$text', 
           `t_date1`= '$start_day', 
           `t_date2`= '$end_day',
            `t_duration`='$duration', 
            `t_user`= '$assign',
            `task_status`= '$status'
            WHERE 
            `t_id`= $t_id";
    $rs = mysqli_query($conn,$sql);
    
    if($rs)
    {
        //更新project開始-結束時間
        proj_date_range($rst['t_project']);
    }
    else
    {
        echo mysqli_error($conn);
    }
    
}
else   //編輯milestone
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

    $sql = "SELECT * FROM milestone WHERE mst_id = '$m_id'";
    $result = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($result);

    //處理status:如果是null，status值就與parent一樣，如果有勾選，就是urgent(value=5)
    if($status != 5){
        $sql2 = "SELECT `p_state` FROM `project` INNER JOIN `milestone` ON project.p_id = milestone.mst_project WHERE milestone.mst_id = $m_id";
        $rs2 = mysqli_query($conn,$sql2);
        $rst2 = mysqli_fetch_array($rs2);
        $status = $rst2["p_state"];
    }
    //mark紀錄
    $edit=array(
        "name1"=>$rst['mst_name'],
        "name2"=>$text,
        "user1"=>null,
        "user2"=>null,
        "date1"=>$rst['mst_date'],
        "date2"=>$start_day,
        "reason"=>$update_reason
        );
    MarkUpdateMilestone($_SESSION['UID'],$rst['mst_project'],$m_id,$edit);

    $sql = "UPDATE `milestone` 
            SET 
            `mst_name`= '$text', 
            `mst_date`= '$start_day',
            `task_status`= '$status'
            WHERE 
            `mst_id`= $m_id";
    $rs = mysqli_query($conn,$sql);
    if($rs){
        //更新project開始-結束時間
        proj_date_range($rst['mst_project']);
    }
    else{
        echo mysqli_error($conn);
    }
}

?>