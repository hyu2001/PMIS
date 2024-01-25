<?php
session_start();
include("get_pid.php");
include("connectsql.php");

$class = $_POST['class'];
$is_all = $_POST['is_all'];
$gid = $_SESSION['filtered_group'];

//將新的filter選項 新增進陣列 或 從陣列移除
switch($class){
    case 'member':
        if($is_all == "true"){
            unset($_SESSION['current_member']);
            $sql = "SELECT u_id FROM user_info WHERE u_email IS NOT NULL";
            $rs = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($rs)){
                $_SESSION['current_member'][] = $row['u_id'];
            }
        }else{
            $_SESSION['current_member'] = array();
        }
        break;

    case 'dept':
        if($is_all == "true"){
            unset($_SESSION['current_dept']);
            $sql = "SELECT dpt_id FROM department";
            $rs = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($rs)){
                $_SESSION['current_dept'][] = $row['dpt_id'];
            }
        }else{
            $_SESSION['current_dept'] = array();
        }
        break;
        
    case 'state':
        if($is_all == "true"){
            unset($_SESSION['current_state']);
            $_SESSION['current_state'] = array(1,2,3,4,5);
        }else{
            $_SESSION['current_state'] = array();
        }
        break;
}

//將當前filter的選擇存在SESSION中
$_SESSION['filtered_member'] = $_SESSION['current_member'];
$_SESSION['filtered_dept'] = $_SESSION['current_dept'];
$_SESSION['filtered_state'] = $_SESSION['current_state'];
$_SESSION['current_group'] = $gid;

// echo "<br>group:".$_SESSION['current_group'];
// echo "<br>dept:";print_r($_SESSION['current_dept']);
// echo "<br>member:";print_r($_SESSION['current_member']);
// echo "<br>state:";print_r($_SESSION['current_state']);

$pid = get_pid($_SESSION['current_group'],$_SESSION['current_dept'],$_SESSION['current_member'],$_SESSION['current_state'],$_SESSION['current_daterange']); //去取得符合條件的pid

if($pid == null) 
{
    $pid = array(0);
}
$_SESSION['current_project'] = $pid;
// echo "pid:";print_r($_SESSION['current_project']);
//============================================================================================//

include("../connectsql.php");

$u_id = $_SESSION['current_member'];
$pid = implode(',', $pid);
$uid = array_map(function($uid) { return "'$uid'"; }, $u_id);
$uid = implode(',', $uid);

if($pid == 0){  //若沒有符合條件的pid，就存這個
    $today = date("d-m-Y");
    $row = array(
        "id" => "1",    //id不可為0
        "text" => "查無資料",
        "start_date" => "$today",
        "duration" => "1",
        "status" => "1",
        "type" => "project"
    );
    $project["data"][] = $row;
}
//==================專案的SQL===========================//
if(isset($_SESSION['current_project']))  //清空篩選時會進入這邊
{
    $sql1 = "SELECT `p_id` AS `id`, `p_name` AS `text`, `p_date1` AS `start_date`, `p_state` AS `status`, `p_department` AS `dept_id` FROM project INNER JOIN department ON project.p_department = department.dpt_id 
              WHERE p_id IN ($pid) ORDER BY SUBSTR(TRIM(p_name),1,1) ASC";
}
else
{
    $sql1 = "SELECT `p_id` AS `id`, `p_name` AS `text`, `p_date1` AS `start_date`, `p_state` AS `status`, `p_department` AS `dept_id` FROM project INNER JOIN department ON project.p_department = department.dpt_id
             WHERE p_state = 1 ORDER BY p_id DESC";    
}
$rs1 = mysqli_query($conn,$sql1); 
//=====================================================//

//==================任務和Milestone的SQL===========================//
if($u_id != null)   //有選擇使用者
{
  $sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid) AND u_id IN ($uid)
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, NULL AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid) AND u_id IN ($uid)
  ) AS subquery
  ORDER BY `row_order` ASC";
}
else if($gid!=0)     //沒有選擇使用者，但有選擇group
{
  $sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid) AND g_id = $gid
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, 'NULL' AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid) AND g_id = $gid
  ) AS subquery
  ORDER BY `row_order` ASC";
}
else
{                //使用者和group都沒選
  $sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid)
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, 'NULL' AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid)
  ) AS subquery
  ORDER BY `row_order` ASC";
}
$rs2 = mysqli_query($conn,$sql2);
//====================================================//


//1.專案資料
if ($rs1->num_rows > 0) {
    while($row1 = $rs1->fetch_assoc()) {
      $row1['start_date'] = date("d-m-Y", strtotime($row1['start_date']));
      $row1["duration"] = "10";  //附加type
      $row1["type"] = "project";  //附加type
      $project["data"][] = $row1;
    }
}

//2.任務和milestone資料
if($rs2){
  if ($rs2->num_rows > 0) {
      while($row2 = $rs2->fetch_assoc()) {
        if($row2['type']=='2')  //task
        {
          $row2['id'] = "t".$row2['id'];
          $row2['start_date'] = date("d-m-Y", strtotime($row2['start_date']));
          $row2["type"] = "task";  //附加type
          $project["data"][] = $row2;
        }
        else                  //milestone
        {
          $row2['id'] = "m".$row2['id'];
          $row2['start_date'] = date("d-m-Y", strtotime($row2['start_date']));
          $row2["type"] = "milestone";  //附加type
          $row2['rollup'] = true;  //milestone會在合併時間軸中被標示
          $project["data"][] = $row2;
        }
      }
    }
}

  
//從資料庫抓所有Staff資料
$sql = "SELECT `u_id` AS `key`, `u_name` AS `label` FROM user_info";
$rs = mysqli_query($conn, $sql);

if ($rs) {
  if ($rs->num_rows > 0) {
    while($row = $rs->fetch_assoc()) {
      $project["staff"][] = $row;
    }
  }
}

//從資料庫抓Department資料
$sql = "SELECT `dpt_id` AS `key`, `dpt_name` AS `label` FROM department";
$rs = mysqli_query($conn, $sql);

if ($rs) {
  if ($rs->num_rows > 0) {
    while($row = $rs->fetch_assoc()) {
      $project["dept"][] = $row;
    }
  }
}

$options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT; 
/*
設定json參數，
JSON_UNESCAPED_UNICODE: 取消unicode編碼，才能顯示中文
JSON_PRETTY_PRINT: 格式化JSON，以便於閱讀
*/

header('Content-Type: application/json');
$json_data = json_encode($project,$options);
echo $json_data;

?>