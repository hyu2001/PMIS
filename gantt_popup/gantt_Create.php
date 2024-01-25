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

//取出POST資料內容
$gantt_id = $data['gantt_id'];
$desc = $data['desc'];
$type = $data['type'];
$status = $data['status'][0];

$start_date = substr($data['start_date'],0,10);
$end_date = substr($data['end_date'],0,10);

//因為不知道為甚麼送過來的日期會減1天，所以在這裡處理把天數都+1回去
$start_date = date("Y-m-d",strtotime("+1 day",strtotime($start_date)));
$end_date = date("Y-m-d",strtotime("+1 day",strtotime($end_date)));

//建立者 = 當前登入者
$creator = $_SESSION['UID']; 

//取出各類型另外自有的內容
if($type == "project")
{
    $dept = $data['dept'];

}
else if($type == "task")
{
    $owner = $data['owner'];    
    $duration = $data['duration'];
    $parent = $data['parent'];
}
else
{
    $parent = $data['parent'];
}

//剛新增的項目是否有設為Urgent，沒有的話就預設為父專案的status
if($type != "project" && $status != 5){
    //取得父專案的status作為剛新增的項目的status
    $sql = "SELECT `p_state` FROM `project` WHERE p_id = $parent";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    $status = $rst['p_state'];
}



//分開執行Project、Task、Milestone的SQL新增語法
if($type=="task")
{
    $newOrder = getNewOrder($parent);
    $sql = "INSERT INTO `task` (`t_name`, `t_project`, `t_creator`, `t_user`, `t_date1`, `t_date2`, `t_duration`, `gantt_id`, `task_status`, `row_order`) 
        VALUES ('$desc', '$parent', '$creator', '$owner', '$start_date', '$end_date', $duration, '$gantt_id', '$status', '$newOrder')";
    $result = mysqli_query($conn,$sql);
    
    //抓到剛剛新增的任務id
    $sql2 = "SELECT * FROM `task`  ORDER BY t_id DESC LIMIT 0 , 1";
    $rs = mysqli_query($conn,$sql2);
    $rst = mysqli_fetch_array($rs);
    
    //Mark
    MarkCreateTask($creator,$parent,$rst['t_id'],$desc);
    
    //更新project開始-結束時間
    proj_date_range($parent);
}
else if($type=="milestone")
{
    $newOrder = getNewOrder($parent);
    //執行SQL新增資料語句
    $sql = "INSERT INTO `milestone` (`mst_name`, `mst_project`, `mst_creator`, `mst_user`, `mst_date`, `gantt_id`, `task_status`, `row_order`) 
            VALUES ('$desc', '$parent', '$creator', '$creator', '$start_date', '$gantt_id', '$status', '$newOrder')";
    mysqli_query($conn,$sql);
    

    //抓到剛剛新增的Milestone id
    $sql2 = "SELECT * FROM `milestone`  ORDER BY mst_id DESC LIMIT 0 , 1";
    $rs = mysqli_query($conn,$sql2);
    $rst = mysqli_fetch_array($rs);

    //Mark
    MarkCreateMilestone($creator,$parent,$rst['mst_id'],$desc);

    //更新project開始-結束時間
    proj_date_range($parent);
}
else
{
    $today = date("Y-m-d");
    $sql = "INSERT INTO `project` (`p_name`, `p_department`, `p_creator`,`p_date1`,`p_date2`, `gantt_id`)
                    VALUE ('$desc', $dept, '$creator','$today','$today','$gantt_id')";
    mysqli_query($conn,$sql);

    //抓到剛剛新增的專案id
    $sql2 = "SELECT * FROM `project`  ORDER BY p_id DESC LIMIT 0 , 1";
    $rs = mysqli_query($conn,$sql2);
    $rst = mysqli_fetch_array($rs);

    //Mark
    MarkCreateProject($creator,$rst['p_id']);
}

?>